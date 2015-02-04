EasyAsset
=========

A library to make using assets (CSS, JS, etc) in your applications a little easier.
 
Key features:

* Compiles LESS, SASS/SCSS, JS, or any [Assetic](https://github.com/kriswallsmith/assetic) asset (or asset collection)
* Recursively compiles (combines) asset files in a directory
* Includes Silex provider, but will work with any framework, router, or HTTP library
* Includes 'force compile' option that will allow you to compile assets on every page load
* Unit Tested, fully PSR-4/PSR-2 compliant

Why?
----

Most people will use external tools to compile LESS, SASS, and JS during development, such as an IDE or a GUI/CLI tool.
Sometimes, however, you may want your application itself to be able to compile these resources in a transparent way.
This way, asset compilation remains consistent in any environment.  EasyAsset does this.

I built this not to replace the functionality of a fully-featured Asset library, such as [Assetic](https://github.com/kriswallsmith/assetic),
but to complement it by adding an API for a common use-case.  EasyAsset can, however, be used as a stand-alone tool.

Installation
------------

This package requires PHP >= 5.4

Include the following in your `composer.json` file:

    require {
        ...
        "caseyamcl/easyasset": "@stable"
        ...
    }
    
Usage
-----

The EasyAsset library can do a few different things:

1. Serve assets from multiple paths via HTTP, sending the correct MIME-types and HTTP headers.  This enables you to
   (optionally) keep your assets outside of the web document root.
2. Compile LESS, SASS, JS, or any Assetic assets on-the-fly.
3. Write compiled assets to output files.

### Using the AssetLoader

The core of EasyAsset is the `EasyAsset\AssetContentLoader` class.  This class streams static (non-compiled) and/or
compiled assets.  You should pass in the file path(s) to where your assets are located (or will be compiled to):

    $loader = new AssetContentLoader(['/path/to/assets']);
    
This basic usage will allow you to load static assets, but not compile any assets.  For example, if you have an image
at `/path/to/assets/img.jpg`, you can load the content:

    // Returns TRUE if the file exists, otherwise false
    $loader->exists('img.jpg');
    
    // Get a callable function (closure) that will echo the output of the 'img.jpg' file:
    $output = $loader->load('img.jpg');
    
    // And invoke the output to send the content to php://output
    $output->__invoke();

You can pass in multiple asset paths to the constructor if you wish for it to search multiple paths:

    $loader = new AssetContentLoader(['/path/to/assets', '/another/path']);
    
    // Will search both paths, in the order they are specified, until a match is found
    $loader->exists('img.jpg');

If an asset does not exist, an `\EasyAsset\Exception\AssetNotExistsException` will be thrown, which you can
catch and turn into a 404 Error in your framework, or do something else with.

    // Throws an AssetNotExistsException
    $loader->load('does-not-exist.jpg');

### Using compiled assets (LESS, SASS, JS, Assetic..) with the AssetLoader

The AssetContentLoader will also compile any assets that you desire if a static version doesn't already exist, or if you
specify that it should be compiled every time, regardless of whether a static file exists.

Compiled assets should specify an output filename (e.g. `style.css` or `js/scripts.js`) and indicate a class that provides
the raw content.  This class must be an instance of `\EasyAsset\CompiledAssetInterface`:

First, create an `EasyAsset\CompiledAssetsCollection` class:

    $compiledAssets = new CompiledAssetsCollection();

Then, add compiled assets:

    $compiledAssets->add('styles.css', new EasyAsset\CompiledAsset\LessCompiledAsset('/path/to/less'));
    $compiledAssets->add('script.js',  new EasyAsset\CompiledAsset\JsCompiledAsset('/path/to/js-source'));
    
If you wish to use CompiledAssets, you must send the `CompiledAssetsCollection` when instantiating the
`AssetContentLoader` class:

    $loader = new AssetContentLoader(['/path/to/assets'], $compiledAssets);
    
Now, when the `AssetContentLoader` is instructed to find `styles.css`, it will first see if there is a file in
one of the paths by that name, and if not, it will build the content using the CompiledAsset class.

    // If the 'styles.css' exists in any specified asset path, use that; otherwise will compile
    $output = $loader->load('styles.css');
    
    // prints the raw CSS content
    $output->__invoke();

You can also force the Loader to compile the asset, even if a file exists, by passing `true` as the third parameter
to `AssetContentLoader::load`.  This is useful for when you are doing development/design:

    $output = $loader->load('styles.css', null, true):

### Built-in Compiled Assets

EasyAsset contains four built-in compilers:

* `EasyAsset\CompiledAsset\LessCompiledAsset` - Compiles LESS using the [oyejorge/less.php]() library
* `EasyAsset\CompiledAsset\JsCompiledAsset` - Compiles and minifies JS using the [patchwork/jsqueeze]() library
* `EasyAsset\CompiledAsset\SassCompiledAsset` - Compiles and minifies SASS/SCSS using the [leafo/scssphp]() library 
* `EasyAsset\CompiledAsset\AsseticAsset` - Wraps any instance of `Assetic\Asset\AssetInterface`, so you can use it with EasyAsset

Additionally, the LESS, JS, and SASS/SCSS compilers will recursively combine and minify all of their respective filetypes
within the path you specify.

For example, suppose you have the following JS files:

    /asset_source/js
        /01-jquery
            /01-jquery.js
            /02-jquery-ui.min.js
        /02-vendor
            /01-chosen.js
            /02-mappify.js
        /03-local
            /scripts.js
            
If you pass the `/asset_source/js` path into the `JsCompiledAsset` class, it will combine all files and minify them,
in alphabetical order by full pathname.  This makes it very easy to both organize large numbers of assets, and also
minimize HTTP requests for your assets.

You can also pass in a single filename to the constructor of the Compiler, e.g.:

    $lessCompiler = new EasyAsset\CompiledAsset\LessCompiledAsset('/path/to/less/01-main.less');

The Assetic Asset class allows you to use any Assetic Asset as a compiled asset.  For example:
 
    $compiledAssets->add('image.png', new Assetic\Asset\FileAsset('asset_src/image.png', [new AsseticPngFilter()]);

### Creating your own compiled asset types

If none of the built-in compiled asset types suit your needs, you can create your own by implementing the
`EasyAsset\CompiledAssetInterface`.
  
If you want your asset to be able to recursively combine files, you can use the `EasyAsset\RecursiveDirParserTrait`.
For example:

    class MyCompiledAsset implements CompiledAssetInterface
    {
         // .. code here..
         
         use RecursiveDirParserTrait;
    
         public function compile($outStream)
         {
             // ..code here..
         
             // getCombinedFiles comes from the trait
             $combinedFiles = $this->getCombinedFiles($this->pathToSource);
             
             // ..or you can use getFileIterator(), which accepts a directory or file path
             // and returns an iterator with all files listed in alphabetical order by path
             $allAssetSourceFiles = $this->getFileIterator($this->pathToSource);
             
             // ..code here..
         }   
    }

### Serving Assets via HTTP

EasyAsset includes an abstract controller class to enable serving assets via a PHP HTTP 
framework: `EasyAsset\AssetController`. If you are using a Framework that uses the Symfony HttpKernel
component, you can use the included `EasyAsset\Provider\Symfony\AssetController` controller.  If
not, you extend the `EasyAsset\AssetController` in your own class.  For example:

    /**
     * A simple Asset Controller that uses PHP-built in functions to deliver a response
     */
    class MyAssetController extends EasyAsset\AssetController
    {
        /**
         * This method prints out the response directly.. Alternatively, your
         * controller could return some kind of response object, which would be handled
         * by your framework.
         */
        protected function sendContentResponse(\Closure $contentCallback, $mimeType)
        {
            header("HTTP/1.0 200 OK");
            header("Content-Type: " . $mimeType);
            $contentCallback->__invoke(); // prints the raw asset content
        }
               
        /**
         * {@inheritdoc}
         */
        protected function sendNotFoundResponse($path)
        {
            header("HTTP/1.0 404 Not Found");
            header("Content-Type: text/plain");
            echo "Asset at path: {$path} not found";
        }
    }
    
Using your class is easy:

    $assetController = new MyAssetController($assetLoader);

    // You'll probably be using a more sophisticated router than this...
    $route = $_SERVER['REQUEST_URI']
    if (substr($route, 0, strlen('asset')) == 'asset') {
    
        // Get the asset path from the URI; again, this is crude for example purposes..
        $assetPath = ltrim(substr($route, strlen('asset')), '/');
   
        // Your implementation of the AssetController class may return a value or simply echo output,
        // like this example does
        $assetController->loadAction($assetPath);
        
        exit();
    }
    
If you are able to, using the built-in Symfony Controller is even easier.  For example, in Silex
(note: detailed Silex integration documentation is below):

    $controller = new EasyAsset\Provider\Symfony\AssetController($loader);
    $app->get('assets/{path}', [$controller, 'loadAction']);

### Writing Assets to Output Files

EasyAsset includes a class to enable writing compiled assets to your chosen path.  This might happen as part of the build
process, deploy process, on-demand, or you can configure your app to write them every time they are compiled:

    $writer = new AssetFileWriter('/path/to/assets');
    
    // Write the whole collection of assets
    $writer->writeAssetCollection($compiledAssets);
    
    // Write a single asset
    $writer->write('style.css', $lessCompiledAsset);
    
If your project uses the Symfony Console component, you can take advantage of a built-in command to write assets:

    use EasyAsset\Provider\AssetWriterCommand;

    $mySymfonyConsoleApp->add(new AssetWriterCommand($compiledAssets, $writer));
    
Then, on the command line:

    # Write assets
    $ app/console assets:compile
    
    # Or, override the default directory to write to:
    $ app/console assets:compile /some/other/asset/path
    
Usage with Silex
----------------

If you use Silex, you can use the `EasyAsset\Provider\Silex\AssetServiceProvider`:

Parameters:

* `assets.paths` - **REQUIRED** - Array of real system paths to where regular assets (images, etc) are and where compiled assets should
   be compiled to.  You can pass as many paths in as you need to; they will be searched in-order when retrieving assets.
* `assets.compiled` - Associative array of asset compilers.  Keys are relative URLs to the compiled asset output 
   file location; values are a compiler class.  See example below.
* `assets.force_compile` - TRUE/FALSE; If you want EasyAsset to compile your assets every time you load them, set this to `TRUE`.  
   Defaults to value of `$app['debug']`
* `assets.write_path` - If your application will be writing assets, indicate the path to where files should be written.
   Defaults to the first value in `assets.paths`
* `assets.write_on_compile` - TRUE/FALSE; If you specified `assets.write_path`, you can configure Silex to dump assets to files every
   time they are compiled.  Defaults to `false`

Services:

* `assets.loader` - Asset loader class; you typically do not need to use this directly
* `assets.controller` - Asset controller.  Expects URI parameter `{path}`. (e.g. `/assets/{path}`)
* `assets.writer` - Asset writer.  Writes compiled assets to the filesystem
* `assets.command` - Asset writer console command.  Provides a Symfony Console command to write assets to the filesystem

Example Bootstrap code:

    use EasyAsset\Provider\Silex\AssetServiceProvider;
    use EasyAsset\CompiledAsset;

    $app->register(new AssetServiceProvider(), array(
        'assets.paths'            => ['/path/to/assets', '/another/asset/path'],
        'assets.write_path'       => '/path/to/assets' // you can omit this if you want to use the first value from 'assets.path'
        'assets.force_compile'    => false, // you may want to use TRUE for development
        'assets.write_on_compile' => true, // defaults to FALSE 
        'assets.compilers         => [
            'style.css',   new CompiledAsset\LessCompiledAsset('/path/to/less'),
            'script.js',   new CompiledAsset\JsCompiledAsset('/path/to/js'),
            'fancypng.png, new CompiledAsset\AsseticAsset($someAsseticAsset)
        ];
    ));

Example Controller code:

    $app->get('/asset/{path}, [$app['assets.controller'], 'loadAction']); 
    
If you are using the `UrlGenerator` and `Twig` in your application, you can easily create asset
URLS in your templates, by binding the route to a name:

    $app->get('/asset/{path}, [$app['assets.controller'], 'loadAction'])->bind('asset'); 
        
Then, in your templates, use the `url()` function to create asset URLs.  If the asset is a compiled
asset, and the output file doesn't exist, the app will transparently compile it.

    <head>
       {# ... #}
       <link rel='stylesheet' href="{{ url('asset', {'path': 'style.css'}); }}" />
       <script src="{{ url('asset', {'path': 'script.js'}); }}"></script>
       {# ... #}
    </head>

## Contributing

Contributions are welcome!  I am especially interested in pull requests that include providers other than Symfony/Silex.

See the <CONTRIBUTING.md> files for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
