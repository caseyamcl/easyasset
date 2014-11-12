<?php

namespace EasyAsset;

use EasyAsset\Exception\CompiledAssetException;

/**
 * A compiled asset
 *
 * This interface represents an asset that is compiled from source, rather
 * than simply loaded from its relative path
 *
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