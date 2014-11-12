<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/7/14
 * Time: 1:27 PM
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
 * @package EasyAsset\CompiledAsset
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