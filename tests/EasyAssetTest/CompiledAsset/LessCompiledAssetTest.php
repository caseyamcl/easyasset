<?php

/**
 * Iggy Rapid Prototyping App
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/caseyamcl/iggy
 * @version 1.0
 * @package caseyamcl/iggy
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
 * @package IggyTest\CompiledAsset
 */
class LessCompiledAssetTest extends AbstractCompiledAssetTest
{
    public function testLessDirReturnsExpectedOutput()
    {
        $obj = $this->getObject($this->getFixtureDir() . 'less');
        $obj->compile($this->getOutpath());
        $output = file_get_contents($this->getOutpath());

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