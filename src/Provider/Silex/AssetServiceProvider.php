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
use EasyAsset\CompiledAssetsCollection;
use EasyAsset\Provider\Symfony\AssetController;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Silex Asset Provider
 *
 * Parameters:
 * - ['assets.paths']          Base path(s) for assets
 * - ['assets.compilers']      An array assets (keys are paths, values are compiled asset object) or instance of \EasyAsset\CompiledAssetsCollection
 * - ['assets.force_compile']  True/False (boolean) Force asset compilation for every load (defaults to value of $app['debug'])
 *
 * Services:
 * - ['assets.loader']         Asset loader (\EasyAsset\AssetContentLoader)
 * - ['assets.controller']     Asset controller service (\EasyAsset\Provider\Symfony\AssetController)
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
        $app['assets.compilers'] = $app->share(function(Application $app) {
            return new CompiledAssetsCollection([]);
        });

        // Loader service
        $app['assets.loader'] = $app->share(function(Application $app) {

            if ( ! $app->offsetExists('assets.paths')) {
                throw new \RuntimeException("'assets.paths' is a required parameter for " . __CLASS__);
            }

            if ( ! $app['assets.compilers'] instanceOf CompiledAssetsCollection) {
                $app['assets.compilers'] = new CompiledAssetsCollection($app['assets.compilers']);
            }

            return new AssetContentLoader((array) $app['assets.paths'], $app['assets.compilers']);
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
    }
}

/* EOF: AssetServiceProvider.php */ 
