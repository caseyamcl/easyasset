<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/7/14
 * Time: 1:16 PM
 */

namespace EasyAsset\CompiledAsset;

use EasyAsset\CompiledAssetInterface;
use EasyAsset\Exception\CompiledAssetException;

/**
 * Base Compiled Asset
 *
 * @package EasyAsset\CompiledAsset
 */
abstract class BaseCompiledAsset implements CompiledAssetInterface
{
    /**
     * @param string $outPath Full path, including filename to write output file to
     */
    public function compile($outPath)
    {
        if (is_dir($outPath)) {
            throw new CompiledAssetException("Output compilation path cannot be a directory: " . $outPath);
        }
        if ( ! is_writable(dirname($outPath))) {
            throw new CompiledAssetException("Output compilation path not writable: " . $outPath);
        }

        try {
            foreach ($this->doCompile() as $data) {
                file_put_contents($outPath, $data, FILE_APPEND);
            }
        }
        catch (\RuntimeException $e) {
            if ( ! $e instanceOf CompiledAssetException) {
                $e = new CompiledAssetException("Compile Error: " . $e->getMessage(), $e->getCode(), $e);
            }
            throw $e;
        }

    }

    // ----------------------------------------------------------------

    /**
     * @return |Traversable  Traversable strings consisting of the compiled content
     */
    abstract protected function doCompile();

}

/* EOF: BaseCompiledAsset.php */ 