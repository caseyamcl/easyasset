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

use EasyAsset\CompiledAsset\LessCompiledAsset;

/**
 * Class LessCompiledAssetTest
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class LessCompiledAssetTest extends AbstractCompiledAssetTest
{
    public function testLessDirReturnsExpectedOutput()
    {
        $str = $this->getOutputStream();

        $obj = $this->getObject($this->getFixtureDir() . 'less');
        $obj->compile($str);
        $output = $this->getOutStreamContents($str);

        $this->assertContains("background-color: 'blue'", $output);
    }

    // ----------------------------------------------------------------

    /**
     * @return array
     */
    public function getGoodPaths()
    {
        return [
            $this->getFixtureDir() . 'less/01-test.less', // Test Single File
            $this->getFixtureDir() . 'less'               // Test Dir
        ];
    }

    // ----------------------------------------------------------------

    /**
     * @param string $basePath
     * @return \EasyAsset\CompiledAssetInterface
     */
    protected function getObject($basePath)
    {
        return new LessCompiledAsset($basePath);
    }
}

/* EOF: LessCompiledAssetTest.php */
