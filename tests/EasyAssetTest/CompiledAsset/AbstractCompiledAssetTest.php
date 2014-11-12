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

use EasyAsset\CompiledAssetInterface;

/**
 * Class AbstractCompiledAssetTest
 * @package IggyTest\CompiledAsset
 */
abstract class AbstractCompiledAssetTest extends \PHPUnit_Framework_TestCase
{
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

    // ----------------------------------------------------------------

    /**
     * @return string
     */
    protected function getFixtureDir()
    {
        return realpath(__DIR__ . '/../Fixtures') . DIRECTORY_SEPARATOR;
    }

    // ----------------------------------------------------------------

    /**
     * @return resource  Read/write stream
     */
    protected function getOutputStream()
    {
        return fopen('php://temp', 'r+');
    }

    // ----------------------------------------------------------------

    /**
     * @param resource $outStream  Readable stream
     * @return string
     */
    protected function getOutStreamContents($outStream)
    {
        rewind($outStream);

        $content = '';
        while ( ! feof($outStream)) {
            $content .= fread($outStream, 2046);
        }
        return $content;
    }
}

/* EOF: AbstractCompiledAssetTest.php */