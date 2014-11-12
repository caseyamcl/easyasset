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
 * Assetic Asset Adapter
 *
 * Allows use of any assetic asset inside of EasyAsset
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
     * @return AssetInterface
     */
    public function getAsseticAsset()
    {
        return $this->asseticAsset;
    }

    // ----------------------------------------------------------------

    /**
     * @return callable
     */
    function compile($outStream)
    {
        try {
            fwrite($outStream, $this->getAsseticAsset()->dump());
        }
        catch (AsseticException $e) {
            throw new CompiledAssetException("Assetic Exception: " . $e->getMessage(), $e->getCode(), $e);
        }
    }
}

/* EOF: AsseticAsset.php */ 