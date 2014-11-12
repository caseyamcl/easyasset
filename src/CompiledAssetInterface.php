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
     * Compile an asset
     *
     * @param resource $outStream      Writable stream
     * @throws CompiledAssetException  If compilation errors occur, throw this exception
     */
    function compile($outStream);
}

/* EOF: CompiledAssetInterface.php */ 