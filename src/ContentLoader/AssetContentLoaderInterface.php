<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/12/14
 * Time: 11:33 AM
 */

namespace EasyAsset\ContentLoader;

/**
 * Asset Content Loader Interface
 *
 * @package EasyAsset
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