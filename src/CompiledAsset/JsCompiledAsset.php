<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/7/14
 * Time: 1:41 PM
 */

namespace EasyAsset\CompiledAsset;

use EasyAsset\RecursiveDirParserTrait;
use JSqueeze;

/**
 * Class JsCompiledAsset
 * @package CompiledAsset
 */
class JsCompiledAsset extends BaseCompiledAsset
{
    // Allow assets that are specified as directories
    use RecursiveDirParserTrait;

    // ----------------------------------------------------------------

    /**
     * @var string
     */
    private $jsSourcePath;

    /**
     * @var \JSqueeze
     */
    private $jSqueeze;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param string $jsSourcePath
     * @param JSqueeze $jSqueeze
     */
    public function __construct($jsSourcePath, JSqueeze $jSqueeze = null)
    {
        $this->jsSourcePath = $jsSourcePath;
        $this->jSqueeze     = $jSqueeze ?: new JSqueeze();
    }

    // ----------------------------------------------------------------

    /**
     * @return \Traversable  Traversable strings consisting of the compiled content
     */
    protected function doCompile()
    {
        foreach ($this->getFileIterator($this->jsSourcePath) as $file) {
            yield $this->jSqueeze->squeeze(file_get_contents($file));
        }
    }
}

/* EOF: JsCompiledAsset.php */