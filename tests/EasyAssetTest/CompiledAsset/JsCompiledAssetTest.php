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

use EasyAsset\CompiledAsset\JsCompiledAsset;
use EasyAsset\CompiledAssetInterface;

/**
 * Class JsAssetProcessorTest
 * @package IggyTest\CompiledAsset
 */
class JsCompiledAssetTest extends AbstractCompiledAssetTest
{
    public function testJsDirReturnsExpectedOutput()
    {
        $obj = $this->getObject($this->getFixtureDir() . 'js/');
        $obj->compile($this->getOutpath());
        $output = file_get_contents($this->getOutpath());

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