<?php

/**
 * EasyAsset Library
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/caseyamcl/easyasset
 * @version 1.0
 * @package caseyamcl/easyasset
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * ------------------------------------------------------------------
 */

namespace EasyAsset\Provider\Silex;

use EasyAsset\AssetContentLoader;
use EasyAsset\AssetControllerInterface;
use EasyAsset\AssetFileWriter;
use EasyAsset\CompiledAssetsCollection;
use EasyAsset\Provider\Symfony\AssetController;
use EasyAsset\Provider\Symfony\AssetWriterCommand;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Silex Asset Provider
 *
 * Parameters:
 * - ['assets.paths']             Base path(s) for assets
 * - ['assets.compiled']          An array assets (keys are paths, values are compiled asset object) or instance of \EasyAsset\CompiledAssetsCollection
 * - ['assets.force_compile']     True/False (boolean) Force asset compilation for every load (defaults to value of $app['debug'])
 * - ['assets.write_path']        Write path for the assets; omit if you wish to use the first path defined in the 'assets.paths' parameter
 * - ['assets.write_on_compile']  True/False (boolean) Write assets to the filesystem every time they are compiled.  (defaults to FALSE)
 *
 * Services:
 * - ['assets.loader']         Asset loader (\EasyAsset\AssetContentLoader)
 * - ['assets.controller']     Asset controller service (\EasyAsset\Provider\Symfony\AssetController)
 * - ['assets.writer']         Asset writer (\EasyAsset\AssetFileWriter]
 * - ['assets.command']        Asset file writer console command (\EasyAsset\Provider\Symfony\AssetWriterCommand)
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class AssetServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app An Application instance
     */
    public function register(Application $app)
    {
        // Default Parameters
        $app['assets.compilers']     = function() { return []; };
        $app['assets.force_compile'] = function($app) { return $app['debug']; };

        // Compiled Asset Collection
        $app['assets.compilers'] = $app->share(function() {
            return new CompiledAssetsCollection([]);
        });

        //
        // Services
        //

        // Asset Compilers Collection (not advertised)
        $app['assets.compilers'] = $app->share(function(Application $app) {
            return new CompiledAssetsCollection($app['assets.compiled']);
        });

        // Loader service
        $app['assets.loader'] = $app->share(function(Application $app) {

            if ( ! $app->offsetExists('assets.paths')) {
                throw new \RuntimeException("'assets.paths' is a required parameter for " . __CLASS__);
            }

            return new AssetContentLoader((array) $app['assets.paths'], $app['assets.compilers']);
        });

        // Controller service
        $app['assets.controller'] = $app->share(function(Application $app) {
            return new AssetController($app['assets.loader'], $app['assets.force_compile']);
        });

        // Writer Service
        $app['assets.writer'] =  $app->share(function(Application $app) {

            // Determine the asset path to write to (either the first asset path or specified by parameter)
            $assetPaths = (array) $app['assets.paths'];
            $writePath = (in_array('assets.write_path', $app->keys()))
                ? $app['assets.write_path']
                : current($assetPaths);

            return new AssetFileWriter($writePath);
        });

        // Console Command
        $app['assets.command'] = $app->share(function(Application $app) {
            return new AssetWriterCommand($app['assets.compilers'], $app['assets.writer']);
        });
    }

    // ----------------------------------------------------------------

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     *
     * @param Application $app
     */
    public function boot(Application $app)
    {
        // Register events

        // After event that writes files if specified
        if (in_array('assets.write_on_compile', $app->keys()) && $app['assets.write_on_compile'] == true) {

            $app->after(function(Request $req) use ($app) {

                // If the controller from the request is not the the asset controller, don't do anything
                if ( ! $req->attributes->get('_controller')[0] instanceOf AssetControllerInterface) {
                    return;
                }

                // Get the relative asset path from the request
                $path = $req->attributes->get('_route_params')['path'];

                // If it is a compiled asset, then write it to the filesystem
                if ($app['assets.compilers']->has($path)) {
                    $app['assets.writer']->writeAsset($path, $app['assets.compilers']->get($path));
                }
            });
        }
    }
}

/* EOF: AssetServiceProvider.php */ 
