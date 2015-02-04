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

use EasyAsset\CompiledAsset\SassCompiledAsset;

/**
 * Class SassCompiledAssetTest
 * @package IggyTest\CompiledAsset
 */
class SassCompiledAssetTest extends AbstractCompiledAssetTest
{
    public function testScssDirReturnsExpectedOutput()
    {
        $str = $this->getOutputStream();

        $obj = $this->getObject($this->getFixtureDir() . 'scss');
        $obj->compile($str);
        $output = $this->getOutStreamContents($str);

        $this->assertContains("background-color: 'red'", $output);
    }

    // ----------------------------------------------------------------

    /**
     * @return array
     */
    protected function getGoodPaths()
    {
        return [
            $this->getFixtureDir() . 'scss/01-test.scss', // Single file
            $this->getFixtureDir() . 'scss' // Directory
        ];
    }

    // ----------------------------------------------------------------

    /**
     * @param string $basePath
     * @return \EasyAsset\CompiledAssetInterface
     */
    protected function getObject($basePath)
    {
        return new SassCompiledAsset($basePath);
    }
}

/* EOF: SassCompiledAssetTest.php */
