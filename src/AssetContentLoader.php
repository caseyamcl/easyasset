<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/7/14
 * Time: 1:55 PM
 */

namespace EasyAsset;

use EasyAsset\Exception\AssetNotExistsException;

class AssetContentLoader
{
    /**
     * @var string
     */
    private $assetBasePath;

    /**
     * @var CompiledAssetsCollection
     */
    private $compiledAssets;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param $assetBasePath  Base path to compiled assets
     * @param CompiledAssetsCollection $compiledAssets
     */
    public function __construct($assetBasePath, CompiledAssetsCollection $compiledAssets = null)
    {
        $this->assetBasePath  = rtrim($assetBasePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->compiledAssets = $compiledAssets ?: new CompiledAssetsCollection();
    }

    // ----------------------------------------------------------------

    /**
     * Load asset content
     *
     * Checks if the content should be compiled and does so, and then returns
     * the content
     *
     * @param string $path  Path to asset, as supplied by URI request (e.g. '/assets/style.css')
     * @param bool $forceCompile
     * @return string
     */
    public function load($path, $forceCompile = false)
    {
        // Does the asset exist?
        $realPath = $this->getRealPath($path);

        // Compile if asset missing or we force it to be compiled upon every load
        if ($this->compiledAssets->has($path) && ($forceCompile OR ! is_readable($realPath))) {
            $this->compiledAssets->get($path)->compile($realPath);
        }

        // Check if asset file exists
        if ( ! is_readable($realPath)) {
            throw new AssetNotExistsException("Could not find asset: " . $path);
        }

        return file_get_contents($realPath);
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
        return ($this->compiledAssets->has($path) OR is_readable($this->getRealPath($path)));
    }

    // ----------------------------------------------------------------

    /**
     * Get real path for asset
     *
     * @param string $relPath
     * @return string
     */
    protected function getRealPath($relPath)
    {
        return $this->assetBasePath . ltrim($relPath,'/');
    }
}

/* EOF: AssetContentLoader.php */