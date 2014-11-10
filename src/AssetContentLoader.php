<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/7/14
 * Time: 1:55 PM
 */

namespace EasyAsset;

use Assetic\AssetManager;
use EasyAsset\Exception\AssetNotExistsException;

/**
 * Asset Content Loader
 *
 * Loads assets based on the specified path
 *
 * If the path is missing, then checks the asset manager to see if we can
 * compile the asset from source
 *
 * @package EasyAsset
 */
class AssetContentLoader
{
    /**
     * @var array
     */
    private $assetPaths;

    /**
     * @var AssetManager
     */
    private $assetManager;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * Specify filesystem paths to assets
     *
     * @param string|array  $assetPaths    Paths to look for assets, in search order
     * @param AssetManager  $assetManager  Asset manager for compiled asses
     */
    public function __construct($assetPaths, AssetManager $assetManager = null)
    {
        $this->assetPaths   = (array) $assetPaths;
        $this->assetManager = $assetManager ?: new AssetManager();
    }

    // ----------------------------------------------------------------

    /**
     * Load an asset as a string
     *
     * @param $path
     * @param bool $forceCompile
     * @return string
     */
    public function load($path, $forceCompile = false)
    {
        $streamer = $this->stream($path, $forceCompile);

        ob_start();
        $streamer();
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    // ----------------------------------------------------------------

    /**
     * Get a function to stream asset content
     *
     * Checks if the content should be compiled and does so, and then returns
     * the content
     *
     * @param string $path  Path to asset, as supplied by URI request (e.g. '/assets/style.css')
     * @param bool $forceCompile
     * @return \Closure
     */
    public function stream($path, $forceCompile = false)
    {
        $realPath = $this->getAssetRealPath($path);

        if ( ! $forceCompile && $realPath) {
            return function() use ($realPath) { readfile($realPath); };
        }
        elseif ($this->assetManager->has($path)) {
            return function() use ($path) { $this->assetManager->get($path)->dump(); };
        }
        else {
            throw new AssetNotExistsException("Could not find asset: " . $path);
        }
    }

    // ----------------------------------------------------------------

    /**
     * Does the asset exist?
     *
     * @param string $path  Path to asset, as supplied by URI request (e.g. '/assets/style.css')
     * @return bool
     */
    public function exists($path)
    {
        return (boolean) $this->getAssetRealPath($path);
    }

    // ----------------------------------------------------------------

    /**
     * Get the real path to the asset
     *
     * @param string $path  Path to asset, as supplied by URI request (e.g. '/assets/style.css')
     * @return string|null
     */
    protected function getAssetRealPath($path)
    {
        $path = ltrim($path, '/');

        foreach ($this->assetPaths as $basePath) {
            $fullPath = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $path;

            if (is_readable($fullPath)) {
                return $fullPath;
            }
        }

        // Return null if made it here.
        return null;
    }
}

/* EOF: AssetContentLoader.php */