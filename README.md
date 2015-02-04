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

### Serving Assets via HTTP



### Compiling Assets

todo: write this

### Writing Assets to Output Files

todo: write this

### Creating your own compiled asset types

todo: write this

### Custom MIME types

Usage with Silex
----------------

If you use Silex, you can use the `EasyAsset\Provider\Silex\AssetServiceProvider`:

The provider accepts the following parameters:

* `assets.paths` - Array of real system path to where regular assets (images, etc) are and where compiled assets should
   be compiled to.  This does not have to be in your document root if you want to use the asset controller (see below).
   You can pass as many paths in as you need to; they will be searched in-order when retrieving assets.
* `assets.compilers` - Associative array of asset compilers.  Keys are relative URLs to the compiled asset output 
   file location; values are a compiler class.  See example below.
* `assets.force_compile` - If you want EasyAsset to compile your assets every time you load them,
   set this to `TRUE`.  Defaults to value of `$app['debug']`
* `assets.write_path` - If your application will be writing assets

Services:

* `assets.loader`     - Asset loader class; you typically do not need to use this directly
* `assets.controller` - Asset controller.  Expects URI parameter `{path}`. (e.g. `/assets/{path}`)
* `assets.writer`     - Asset writer.  Writes compiled assets to the filesystem
* `assets.command`    - Asset writer console command.  Provides a Symfony Console command to write assets to the filesystem

Example Bootstrap code:

    use EasyAsset\Provider\Silex\AssetServiceProvider;
    use EasyAsset\CompiledAsset;

    $app->register(new AssetServiceProvider(), array(
        'assets.paths'        => ['/path/to/assets', '/another/asset/path'],
        'assets.write_path'   => '/path/to/assets' // you can omit this if you want to use the first value from 'assets.path'
        'assets.force_compile => false, // you may want to use TRUE for development
        'assets.compilers     => [
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

You can also use the `assets.writer` and `assets.command` in your application if you wish to enable writing of
assets to the filesystem.

Usage with Other Frameworks
---------------------------

If your framework uses the Symfony `HttpKernel` package, just use the
`EasyAsset\Provider\Symfony\AssetController` class, and route GET requests
(e.g. *GET /asset/{path}*) to the` loadAction($path)` method.
 
If your framework doesn't use Symfony, then you can extend the abstract
`EasyAsset\AssetController` class.  Your methods can either return some sort
of HTTP Response object, or send the output immediately.

The `AssetController::sendContentResponse()` accepts a closure which echos the
asset content to STDOUT, along with a mime type for the content.
 
If you wish to instead get the asset content as a string, you can use
the following code:

    function sendContentResponse(\Closure $contentCallback, $mimeType)
    {
       // code here...
       
       ob_start();
       $contentCallback->__invoke();
       $assetContent = ob_get_contents();
       ob_end_clean();
       
       // code here...
    }

That's all there is to it.
