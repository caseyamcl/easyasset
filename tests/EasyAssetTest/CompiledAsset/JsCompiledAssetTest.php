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

namespace EasyAssetTest\CompiledAsset;

use EasyAsset\CompiledAsset\JsCompiledAsset;
use EasyAsset\CompiledAssetInterface;

/**
 * Class JsAssetProcessorTest
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class JsCompiledAssetTest extends AbstractCompiledAssetTest
{
    public function testJsDirReturnsExpectedOutput()
    {
        $str = $this->getOutputStream();

        $obj = $this->getObject($this->getFixtureDir() . 'js/');
        $obj->compile($str);
        $output = $this->getOutStreamContents($str);

        $this->assertContains('1234', $output);
        $this->assertContains('alert', $output);
    }

    // ----------------------------------------------------------------

    /**
     * @param $basePath
     * @return CompiledAssetInterface
     */
    protected function getObject($basePath)
    {
        return new JsCompiledAsset($basePath);
    }

    // ----------------------------------------------------------------

    /**
     * @return array
     */
    protected function getGoodPaths()
    {
        return [
            $this->getFixtureDir() . 'js/01-test.js', // File
            $this->getFixtureDir() . 'js/'            // Directory
        ];
    }
}

/* EOF: JsCompiledAssetTest.php */
