<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/7/14
 * Time: 2:17 PM
 */

namespace EasyAsset\Provider\Silex;

use Assetic\AssetManager;
use Assetic\AssetWriter;
use EasyAsset\AssetContentLoader;
use EasyAsset\Provider\Symfony\AssetController;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Silex Asset Provider
 *
 * Parameters:
 * - ['assets.paths']                    Paths to look for normal file assets, in priority order
 * - ['assets.assetic_assets']           Array of assetic assets (key is web path, value is assetic asset object)
 * - ['assets.assetic_always_compile']   If true, compiles upon every page load (defaults to $app['debug'] value)
 * - ['assets.assetic_write_on_compile'] If true, writes out assets to 'assetic_write_path' upon compiling assets
 * - ['assets.assetic_write_path']       If provided, is a path to write Assetic assets to
 *
 * Services:
 * - ['assets.controller']       Asset controller
 * - ['assets.loader']           Loads/streams assets
 * - ['assets.assetic_manager']  Assetic Manager
 *
 * @package EasyAsset
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
        // Default params
        $app['assets.paths']                  = function() { return []; };
        $app['assets.assetic']                = function() { return null; };
        $app['assets.assetic_write_path']     = function() { return sys_get_temp_dir(); };
        $app['assets.assetic_always_compile'] = function($app) { return $app['debug']; };

        // Assetic Manager
        $app['assets.assetic_manager'] = $app->share(function(Application $app) {
            return new AssetManager();
        });

        // Assetic File Writer
        $app['assets.assetic_file_writer'] = $app->share(function(Application $app) {
            new AssetWriter($app['assets.assetic_write_path']);
        });

        // Asset Loaders
        $app['assets.loader'] = $app->share(function(Application $app) {
            return new AssetContentLoader($app['assets.path'], $app['app.assetic_manager']);
        });

        // Asset Controller
        $app['assets.controller'] = $app->share(function(Application $app) {
            return new AssetController(
                $app['assets.loader'],
                $app['assets.assetic_always_compile'],
                $app['assets.assetic_manager']
            );
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
        // Write assets after page load if configured to do so
        if ($app['assets.assetic_write_on_compile']) {

            $app->after(function() use ($app) {
                $app['assets.assetic_file_writer']->writeManagerAssets($app['asset.assetic_manager']);
            });

        }
    }
}

/* EOF: AssetServiceProvider.php */ 