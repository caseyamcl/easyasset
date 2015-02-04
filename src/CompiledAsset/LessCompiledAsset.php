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

namespace EasyAsset\CompiledAsset;

use EasyAsset\CompiledAssetInterface;
use EasyAsset\Exception\CompiledAssetException;
use EasyAsset\RecursiveDirParserTrait;
use Less_Parser;

/**
 * LESS compiled asset
 *
 * Provides compressed CSS output for LESS files
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class LessCompiledAsset implements CompiledAssetInterface
{
    use RecursiveDirParserTrait;

    // ----------------------------------------------------------------

    /**
     * @var Less_Parser
     */
    private $less;

    /**
     * @var string
     */
    private $lessSourcePath;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param string $lessSourcePath
     * @param Less_Parser $less
     */
    public function __construct($lessSourcePath, Less_Parser $less = null)
    {
        $this->less           = $less ?: new \Less_Parser(['compress' => true]);
        $this->lessSourcePath = $lessSourcePath;
    }

    // ----------------------------------------------------------------

    /**
     * @param resource $outStream
     */
    public function compile($outStream)
    {
        $less = clone $this->less;

        try {
            foreach ($this->getFileIterator($this->lessSourcePath) as $file) {
                $less->parseFile($file);
            }

            fwrite($outStream, $less->getCss());
        }
        catch (\RuntimeException $e) {
            throw new CompiledAssetException("Compiled asset exception: " . $e->getMessage(), $e->getCode(), $e);
        }
    }
}

/* EOF: LessCompiledAsset.php */ 
