<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/7/14
 * Time: 2:17 PM
 */

namespace EasyAsset\Provider\Silex;

use EasyAsset\AssetContentLoader;
use EasyAsset\CompiledAssetsCollection;
use EasyAsset\Provider\Symfony\AssetController;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Silex Asset Provider
 *
 * Parameters:
 * - ['assets.path']            REQUIRED; Base path for assets
 * - ['assets.compilers']       OPTIONAL; An array (keys are asset URL path, and values are CompiledAssetInterface objects)
 * - ['assets.force_compile']   OPTIONAL; True/False (boolean) Force asset compilation for every load (defaults to value of $app['debug'])
 *
 * Collection:
 * - ['assets.compiled_assets'] Compiled Assets collection
 *
 * Services:
 * - ['assets.loader']          Asset loader (\EasyAsset\AssetContentLoader)
 * - ['assets.controller']      Asset controller service (\EasyAsset\Provider\Symfony\AssetController)
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
        // Default Parameters
        $app['assets.compilers']     = function() { return []; };
        $app['assets.force_compile'] = function($app) { return $app['debug']; };

        // Compiled Asset Collection
        $app['assets.compiled_assets'] = $app->share(function(Application $app) {
            return new CompiledAssetsCollection($app['assets.compilers']);
        });

        // Loader service
        $app['assets.loader'] = $app->share(function(Application $app) {

            if ($app->offsetExists('assets.path')) {
                throw new \RuntimeException("'assets.path' is a required parameter for " . __CLASS__);
            }

            return new AssetContentLoader($app['assets.path'], $app['assets.compiled_assets']);
        });

        // Controller service
        $app['assets.controller'] = $app->share(function(Application $app) {
            return new AssetController($app['assets.loader'], $app['assets.force_compile']);
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
        // pass
    }
}

/* EOF: AssetServiceProvider.php */ 