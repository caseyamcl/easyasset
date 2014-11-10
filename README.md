EasyAsset
=========

A library to make using assets (CSS, JS, etc) stupidly easy in your applications.
 
Key features:

* Compiles LESS, SASS/SCSS, JS, or any [Assetic](https://github.com/kriswallsmith/assetic) asset (or asset collection)
* Allows recursive compilation of directories
* Automatically loads compiled asset, or falls back on asset compilation
* Includes Silex/Symfony controller service
* Unit Tested, fully PSR-0, PSR-2 compliant

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

@TODO: This


Usage with Silex
----------------

If you use Silex, you can use the `EasyAsset\Provider\Silex\AssetServiceProvider`:

The provider accepts the following parameters:

* `assets.path` - Real system path to where compiled assets are to be written/read
* `assets.compilers` - Associative array of asset compilers.  Keys are relative URLs to asset;
  values are a compiler class.  See example below.
* `assets.force_compile` - If you want EasyAsset to compile your assets every time you load them,
  set this to TRUE.  Defaults to value of `$app['debug']`

Services:

* `assets.loader`     - Asset loader class; you typically do not need to use this directly
* `assets.controller` - Asset controller.  Expects parameter `{path}`.

Example Bootstrap code:

    $app->register(new \EasyAsset\Provider\Silex\AssetServiceProvider(), array(
        'assets.path'         => '/path/to/assets',
        'assets.force_compile => false, // you may want to use TRUE for development
        'assets.compilers     => [
            'style.css',   new \EasyAsset\CompiledAsset\LessCompiledAsset('/path/to/less'),
            'script.js',   new \EasyAsset\CompiledAsset\JsCompiledAsset('/path/to/js'),
            'fancypng.png, new \EasyAsset\CompiledAsset\AsseticAsset($someAsseticAsset)
        ];
    ));

Example Controller code:

    $app->get('/asset/{path}, function($path) use ($app) {
        return $app['assets.controller']->loadAction($path);
    });

This will allow you to use asset URLs in your application the same as any other URL.  If
you are using the `UrlGenerator` and `Twig` in your application, you can easily create asset
URLS in your templates:

    $app->get('/asset/{path}, function($path) use ($app) {
        return $app['assets.controller']->loadAction($path);
    })->bind('asset');
    
In your templates:

    <head>
       {# ... #}
       <link rel='stylesheet' href="{{ url('asset', {'path': 'style.css'}); }}" />
       <script src="{{ url('asset', {'path': 'script.js'}); }}"></script>
       {# ... #}
    </head>


Usage with Other Frameworks
---------------------------

If your framework uses the Symfony `HttpKernel` package, just use the
`EasyAsset\Provider\Symfony\AssetController` class, and route GET requests
(e.g. *GET /asset/{path}*) to the` loadAction($path)` method.
 
If your framework doesn't use Symfony, then you can extend the abstract
`EasyAsset\AssetController` class.  Your methods can either return some sort
of HTTP Response object, or send the output immediately.

The `AssetController::sendContentResponse()` receives a closure which echos the
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