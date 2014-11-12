<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/7/14
 * Time: 1:41 PM
 */

namespace EasyAsset\CompiledAsset;

use EasyAsset\CompiledAssetInterface;
use EasyAsset\Exception\CompiledAssetException;
use EasyAsset\RecursiveDirParserTrait;
use JSqueeze;

/**
 * Class JsCompiledAsset
 * @package CompiledAsset
 */
class JsCompiledAsset implements CompiledAssetInterface
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

    public function compile($outStream)
    {
        try {
            foreach ($this->getFileIterator($this->jsSourcePath) as $file) {
                fwrite($outStream, $this->jSqueeze->squeeze(file_get_contents($file)));
            }
        }
        catch (\RuntimeException $e) {
            throw new CompiledAssetException("Compiled asset exception: " . $e->getMessage(), $e->getCode(), $e);
        }
    }
}

/* EOF: JsCompiledAsset.php */