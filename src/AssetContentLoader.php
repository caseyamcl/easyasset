<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/7/14
 * Time: 1:55 PM
 */

namespace EasyAsset;

use EasyAsset\Exception\AssetNotExistsException;

/**
 * Asset Content Loader
 *
 * Loads assets from looking through filesystem, or if the asset
 * can be compiled, compiles it
 *
 * @package EasyAsset
 */
class AssetContentLoader implements AssetContentLoaderInterface
{
    /**
     * @var array
     */
    private $assetPaths;

    /**
     * @var CompiledAssetsCollection
     */
    private $compiledAssets;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param array $assetPaths  Array of paths in which to search for assets
     * @param CompiledAssetsCollection $compiledAssets
     */
    public function __construct(array $assetPaths, CompiledAssetsCollection $compiledAssets = null)
    {
        $this->setAssetPaths($assetPaths);
        $this->compiledAssets = $compiledAssets ?: new CompiledAssetsCollection();
    }

    // ----------------------------------------------------------------

    /**
     * Load asset content
     *
     * Checks if the content should be compiled and does so, and then returns
     * the content
     *
     * @param string $path Path to asset, as supplied by URI request (e.g. '/assets/style.css')
     * @param resource $outStream  Writable output stream
     * @param bool $forceCompile
     * @return \Closure  a function that can be called to output the content
     */
    public function load($path, $outStream = null, $forceCompile = false)
    {
        // Does the asset exist?
        $realPath = $this->getRealPath($path);

        // Setup streamer..
        $outStream = $outStream ?: fopen('php://output', 'w');

        // If path resolves to a compiled asset..
        if (($forceCompile OR ! $realPath) && $this->compiledAssets->has($path)) {

            return function() use ($path, $outStream) {
                $this->compiledAssets->get($path)->compile($outStream);
                fflush($outStream);
            };
        }
        elseif ($realPath) { //if realpath exists..

            return function() use ($realPath, $outStream) {
                $this->pipeContent($realPath, $outStream);
                fflush($outStream);
            };
        }
        else {
            throw new AssetNotExistsException("Cannot find asset: " . $path);
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
        return ($this->compiledAssets->has($path) OR $this->getRealPath($path));
    }

    // ----------------------------------------------------------------

    /**
     * Set asset paths
     *
     * @param array $paths
     */
    protected function setAssetPaths(array $paths)
    {
        foreach ($paths as $path) {
            $this->assetPaths[] = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }
    }

    // ----------------------------------------------------------------

    /**
     * Get real path for an asset
     *
     * @param string $relPath
     * @return string|null  Null if could not resolve a real path for the asset
     */
    protected function getRealPath($relPath)
    {
        foreach ($this->assetPaths as $basePath) {
            $fPath = $basePath . ltrim($relPath,'/');
            if (is_readable($fPath)) {
                return $fPath;
            }
        }
        // if made it here..
        return null;
    }

    // ----------------------------------------------------------------

    /**
     * Helper method to pipe content from one file to an output stream
     *
     * @param string   $filePath
     * @param resource $outStream  Writable stream
     */
    private function pipeContent($filePath, $outStream)
    {
        $inFile = fopen($filePath, 'r');
        while ( ! feof($inFile)) {
            fwrite($outStream, fread($inFile, 8 * 1024));
        }
    }
}

/* EOF: AssetContentLoader.php */