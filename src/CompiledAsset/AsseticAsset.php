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
 * @author Casey McLaughlin <caseyamcl@gmail.com>
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
