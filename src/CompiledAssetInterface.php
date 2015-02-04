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

namespace EasyAsset;

use EasyAsset\Exception\CompiledAssetException;

/**
 * A compiled asset
 *
 * This interface represents an asset that is compiled from source, rather
 * than simply loaded from its relative path
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
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
