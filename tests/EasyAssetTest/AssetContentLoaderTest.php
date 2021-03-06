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

namespace EasyAssetTest;

use EasyAsset\AssetContentLoader;
use EasyAsset\CompiledAssetsCollection;
use EasyAsset\Exception\CompiledAssetException;

/**
 * Asset Content Loader Test
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class AssetContentLoaderTest extends \PHPUnit_Framework_TestCase
{
    use TestHelpersTrait;

    // ----------------------------------------------------------------

    public function testInstantiateSucceeds()
    {
        $obj = $this->getObject();
        $this->assertInstanceOf('\EasyAsset\AssetContentLoader', $obj);
    }

    // ----------------------------------------------------------------

    public function testLoadReturnsContentForExistingPath()
    {
        $str = $this->getOutputStream();

        $obj = $this->getObject();
        $streamer = $obj->load('js/01-test.js', $str);
        call_user_func($streamer);

        $this->assertContains('1234', $this->getOutStreamContents($str));
    }

    // ----------------------------------------------------------------

    public function testLoadReturnsContentForNonExistingCompiledAsset()
    {
        $str = $this->getOutputStream();

        $obj = $this->getObject();
        $streamer = $obj->load('style.css', $str);
        call_user_func($streamer);

        $this->assertContains('compiled', $this->getOutStreamContents($str));
    }

    // ----------------------------------------------------------------

    public function testLoadThrowsAssetNotFoundExceptionForNonExistentAssetWithNoCompiler()
    {
        $this->setExpectedException('\EasyAsset\Exception\AssetNotExistsException');

        $obj = $this->getObject();
        $obj->load('nuthin.js');
    }

    // ----------------------------------------------------------------

    public function testFailedAssetCompilationThrowsAppropriateException()
    {
        $this->setExpectedException('\EasyAsset\Exception\CompiledAssetException');

        $str = $this->getOutputStream();

        $obj = $this->getObject();
        $streamer = $obj->load('script.js', $str);
        call_user_func($streamer);
    }

    // ----------------------------------------------------------------

    public function testLoadReturnedCallableWritesToRegularOutputWhenNoStreamProvided()
    {
        $obj = $this->getObject();
        $streamer = $obj->load('js/01-test.js');

        ob_start();
        call_user_func($streamer);
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertContains('1234', $output);
    }

    // ----------------------------------------------------------------

    /**
     * Get object
     *
     * @param null $basePath  Base Path for compilation
     * @return AssetContentLoader
     */
    protected function getObject($basePath = null)
    {
        // A fake compiled asset that writes a dummy file
        $fakeGoodCompiledAsset = \Mockery::mock('\EasyAsset\CompiledAssetInterface');
        $fakeGoodCompiledAsset->shouldReceive('compile')->andReturnUsing(
            function($outStr) use ($basePath) {
                fwrite($outStr, 'compiled');
            }
        );

        // A fake bad compiled asset that fails
        $fakeBadCompiledAsset = \Mockery::mock('\EasyAsset\CompiledAssetInterface');
        $fakeBadCompiledAsset->shouldReceive('compile')->andThrow(new CompiledAssetException());

        // Return the loader
        return new AssetContentLoader([$basePath ?: $this->getFixtureDir()], new CompiledAssetsCollection([
            'style.css' => $fakeGoodCompiledAsset,
            'script.js' => $fakeBadCompiledAsset
        ]));
    }
}

/* EOF: AssetContentLoaderTest.php */ 
