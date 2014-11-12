<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/7/14
 * Time: 1:37 PM
 */

namespace EasyAsset\CompiledAsset;

use EasyAsset\CompiledAssetInterface;
use EasyAsset\Exception\CompiledAssetException;
use EasyAsset\RecursiveDirParserTrait;
use Leafo\ScssPhp\Compiler as ScssCompiler;

/**
 * SASS/SCSS Compiled Asset
 *
 * @package CompiledAsset
 */
class SassCompiledAsset implements CompiledAssetInterface
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

    public function compile($outStream)
    {
        try {
            fwrite(
                $outStream,
                $this->sassParser->compile($this->getCombinedFiles($this->sassSourcePath))
            );
        }
        catch (\RuntimeException $e) {
            throw new CompiledAssetException("Compiled asset exception: " . $e->getMessage(), $e->getCode(), $e);
        }
    }
}

/* EOF: SassCompiledAsset.php */