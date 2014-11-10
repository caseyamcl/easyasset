<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/7/14
 * Time: 2:17 PM
 */

namespace EasyAsset\Provider\Silex;

use Assetic\Asset\AssetCache;
use Assetic\Asset\FileAsset;
use Assetic\AssetManager;
use Assetic\AssetWriter;
use Assetic\Cache\FilesystemCache;
use EasyAsset\Provider\Symfony\AssetController;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Silex Asset Provider
 *
 * Parameters:
 * - ['asset.assets']          Array of assetic assets
 * - ['asset.cache_path']      Path to cache assets to, so they don't have to be recompiled
 * - ['asset.force_compile']   If true, compiles upon every page load (defaults to $app['debug'] value)
 *
 * Services:
 * - ['asset.cache']       Override with your own instance of the Assetic\Cache\CacheInterface if you want
 * - ['asset.controller']  Asset controller
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
        $app['asset.cache']      = function() { return null; };
        $app['asset.cache_path'] = function() { return null; };

        // Asset manager
        $app['asset.manager'] = $app->share(function(Application $app) {

            $mgr = new AssetManager();

            if ($app['asset.cache'] OR $app['asset.cache_path']) {
                $app['asset.cache'] = $app['asset.cache'] ?: new FilesystemCache($app['asset.cache_path']);
            }

            foreach ($app['asset.assets'] as $name => $asset) {

                if ($app['asset.cache'] && ! $app['asset.force_compile']) {
                    $asset = new AssetCache($asset, $app['asset.cache']);
                }

                $mgr->set($name, $asset);
            }
        });

        // Asset controller
        $app['asset.controller'] = $app->share(function(Application $app) {
            return new AssetController($app['asset.manager']);
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
        // pass...
    }
}

/* EOF: AssetServiceProvider.php */ 