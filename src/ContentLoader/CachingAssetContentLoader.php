<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/12/14
 * Time: 12:31 PM
 */

namespace EasyAsset\ContentLoader;

use Doctrine\Common\Cache\Cache;
use EasyAsset\CompiledAssetsCollection;


/**
 * Auto-writing asset content loader
 *
 * Writes asset content to cache (filesystem, etc)
 *
 * @package ContentLoader
 */
class CachingAssetContentLoader extends AssetContentLoader
{
    /**
     * @var Cache
     */
    private $cache;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param array $assetPaths
     * @param Cache $cache
     * @param CompiledAssetsCollection $compiledAssets
     */
    public function __construct(array $assetPaths, Cache $cache, CompiledAssetsCollection $compiledAssets = null)
    {
        parent::__construct($assetPaths, $compiledAssets);
        $this->cache = $cache;
    }

    // ----------------------------------------------------------------

    public function load($path, $outStream = null, $forceCompile = false)
    {
        if ( ! $forceCompile && $this->cache->contains($path)) {
            return $this->cache->fetch($path);
        }

        return parent::load($path, $outStream, $forceCompile);
    }

    // ----------------------------------------------------------------

    public function exists($path)
    {
        if ($this->cache->contains($path)) {
            return true;
        }
        else {
            return parent::exists($path);
        }
    }

    // ----------------------------------------------------------------

    // LEFT OFF HERE .. NEED TO TEST THIS...
    protected function compileContent($path, $outStream)
    {
        parent::compileContent($path, $outStream);

        $cacheStream = fopen("php://temp", 'w');
        $this->getCompiledAsset($path)->compile($cacheStream);
        fclose($cacheStream);

        $this->cache->save($path, file_get_contents($cacheStream));
    }

}

/* EOF: CachingAssetContentLoader.php */