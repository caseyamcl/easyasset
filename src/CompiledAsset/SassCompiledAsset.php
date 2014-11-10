<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/7/14
 * Time: 1:37 PM
 */

namespace EasyAsset\CompiledAsset;

use EasyAsset\RecursiveDirParserTrait;
use Leafo\ScssPhp\Compiler as ScssCompiler;

/**
 * SASS Compiled Asset
 *
 * @package CompiledAsset
 */
class SassCompiledAsset extends BaseCompiledAsset
{
    use RecursiveDirParserTrait;

    // ----------------------------------------------------------------

    /**
     * @var ScssCompiler
     */
    private $sassParser;

    /**
     * @var string
     */
    private $sassSourcePath;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param $sassSourcePath
     * @param ScssCompiler $less
     */
    public function __construct($sassSourcePath,  $less = null)
    {
        $this->sassParser     = $less ?: new ScssCompiler(['compress' => true]);
        $this->sassSourcePath = $sassSourcePath;
    }

    // ----------------------------------------------------------------

    /**
     * Compile
     *
     * @return \ArrayIterator|Traversable  Traversable strings consisting of the compiled content
     */
    protected function doCompile()
    {
        $content = $this->sassParser->compile($this->getCombinedFiles($this->sassSourcePath));
        return new \ArrayIterator([$content]);
    }
}

/* EOF: SassCompiledAsset.php */