<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/7/14
 * Time: 1:27 PM
 */

namespace EasyAsset\CompiledAsset;

use EasyAsset\RecursiveDirParserTrait;
use Less_Parser;

/**
 * Class LessCompiledAsset
 * @package EasyAsset\CompiledAsset
 */
class LessCompiledAsset extends BaseCompiledAsset
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
     * @return \Traversable  Traversable strings consisting of the compiled content
     */
    protected function doCompile()
    {
        foreach ($this->getFileIterator($this->lessSourcePath) as $file) {
            $this->less->parseFile($file);
        }

        return new \ArrayIterator([$this->less->getCss()]);
    }
}

/* EOF: LessCompiledAsset.php */ 