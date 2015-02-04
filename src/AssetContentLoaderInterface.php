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

namespace EasyAsset;

/**
 * Asset Content Loader Interface
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
interface AssetContentLoaderInterface
{
    /**
     * Load an asset
     *
     * @param string    $path         Path
     * @param resource  $outStream     Writable stream
     * @param bool      $forceCompile  Force compile?
     * @return callable  A callable that outputs the content to the outStream
     */
    function load($path, $outStream = null, $forceCompile = false);

    /**
     * Does a given asset exist?
     *
     * @param $path
     * @return boolean
     */
    function exists($path);
}

/* EOF: AssetContentLoaderInterface.php */ 
