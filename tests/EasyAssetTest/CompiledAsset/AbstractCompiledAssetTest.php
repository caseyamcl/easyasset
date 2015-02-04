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

use EasyAsset\CompiledAssetInterface;
use EasyAssetTest\TestHelpersTrait;

/**
 * Class AbstractCompiledAssetTest
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
abstract class AbstractCompiledAssetTest extends \PHPUnit_Framework_TestCase
{
    use TestHelpersTrait;

    // ----------------------------------------------------------------

    /**
     * @dataProvider goodObjectsProvider
     * @param CompiledAssetInterface $obj
     */
    public function testCompileReturnsStringWithValidSourcePath(CompiledAssetInterface $obj)
    {
        $str = $this->getOutputStream();
        $obj->compile($str);
        $output = $this->getOutStreamContents($str);

        $this->assertInternalType('string', $output);
        $this->assertNotEmpty($output);
    }

    // ----------------------------------------------------------------

    /**
     * @dataProvider badObjectsProvider
     * @param CompiledAssetInterface $obj
     */
    public function testCompileThrowsCompileExceptionForInvalidSourcePath(CompiledAssetInterface $obj)
    {
        $this->setExpectedException('\EasyAsset\Exception\CompiledAssetException');
        $obj->compile($this->getOutputStream());
    }

    // ----------------------------------------------------------------

    /**
     * @param string $basePath
     * @return CompiledAssetInterface
     */
    abstract protected function getObject($basePath);

    /**
     * @return array  ['goodpath', 'goodpath', etc..]
     */
    abstract protected function getGoodPaths();

    // ----------------------------------------------------------------

    /**
     * @return array
     */
    public function goodObjectsProvider()
    {
        $out = array();
        foreach ($this->getGoodPaths() as $goodPath) {
            $out[] = [$this->getObject($goodPath)];
        }
        return $out;
    }

    // ----------------------------------------------------------------

    /**
     * @return array
     */
    public function badObjectsProvider()
    {
        $out = array();
        foreach ($this->getBadPaths() as $badPath) {
            $out[] = [$this->getObject($badPath)];
        }
        return $out;
    }

    // ----------------------------------------------------------------

    /**
     * @return array  ['badpath', 'badpath', etc..]
     */
    protected function getBadPaths()
    {
        return [
            $this->getFixtureDir() . 'badPath' . rand(1000, 9999) // Totally fake path
        ];
    }
}

/* EOF: AbstractCompiledAssetTest.php */
