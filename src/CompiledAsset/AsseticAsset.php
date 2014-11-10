<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/10/14
 * Time: 12:01 PM
 */

namespace CompiledAsset;

use Assetic\Asset\AssetInterface;
use Assetic\Exception\Exception as AsseticException;
use EasyAsset\CompiledAssetInterface;
use EasyAsset\Exception\CompiledAssetException;

/**
 * Assetic Asset
 *
 * @package CompiledAsset
 */
class AsseticAsset implements CompiledAssetInterface
{
    /**
     * @var AssetInterface
     */
    private $asseticAsset;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param AssetInterface $asseticAsset
     */
    public function __construct(AssetInterface $asseticAsset)
    {
        $this->asseticAsset = $asseticAsset;
    }

    // ----------------------------------------------------------------

    /**
     * Compile
     *
     * @param string $outPath
     * @throws CompiledAssetException  Compilation errors occur?
     */
    function compile($outPath)
    {
        try {
            return new \ArrayIterator([$this->asseticAsset->dump()]);
        }
        catch (AsseticException $e) {
            throw new CompiledAssetException("Assetic Exception: " . $e->getMessage(), $e->getCode(), $e);
        }
    }
}

/* EOF: AsseticAsset.php */ 