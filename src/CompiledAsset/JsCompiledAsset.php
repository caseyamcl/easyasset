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
use JSqueeze;

/**
 * JSCompiled asset
 *
 * Provide filepath or directory to get squeezed/compressed output
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
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
