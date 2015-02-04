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
use Leafo\ScssPhp\Compiler as ScssCompiler;

/**
 * SASS/SCSS Compiled Asset
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
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
