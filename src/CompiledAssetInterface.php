<?php

namespace EasyAsset;

use EasyAsset\Exception\CompiledAssetException;

/**
 * Interface CompiledAssetInterface
 * @package EasyAsset
 */
interface CompiledAssetInterface
{
    /**
     * @param string $outPath
     * @throws CompiledAssetException  Compilation errors occur?
     */
    function compile($outPath);
}

/* EOF: CompiledAssetInterface.php */ 