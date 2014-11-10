<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/7/14
 * Time: 3:07 PM
 */

namespace EasyAssetTest;


use EasyAsset\AssetContentLoader;
use EasyAsset\CompiledAssetsCollection;
use EasyAsset\Exception\CompiledAssetException;

class AssetContentLoaderTest extends \PHPUnit_Framework_TestCase
{
    private $filesToClean = [];

    protected function tearDown()
    {
        parent::tearDown();
        foreach ($this->filesToClean as $file) {
            @unlink($file);
        }
    }

    // ----------------------------------------------------------------

    public function testInstantiateSucceeds()
    {
        $obj = $this->getObject();
        $this->assertInstanceOf('\EasyAsset\AssetContentLoader', $obj);
    }

    // ----------------------------------------------------------------

    public function testLoadReturnsContentForExistingPath()
    {
        $obj = $this->getObject();
        $output = $obj->load('js/01-test.js');

        $this->assertContains('1234', $output);
    }

    // ----------------------------------------------------------------

    public function testLoadReturnsContentForNonExistingCompiledAsset()
    {
        $obj = $this->getObject(sys_get_temp_dir());
        $output = $obj->load('style.css');
        $this->assertContains('compiled', $output);
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
        $obj = $this->getObject();
        $obj->load('script.js');
    }

    // ----------------------------------------------------------------

    /**
     * @param null $basePath  Basepath for compilation
     * @return AssetContentLoader
     */
    protected function getObject($basePath = null)
    {
        // A fake compiled asset that writes a dummy file
        $fakeGoodCompiledAsset = \Mockery::mock('\EasyAsset\CompiledAssetInterface');
        $fakeGoodCompiledAsset->shouldReceive('compile')->andReturnUsing(function($pth) use ($basePath) {
            $this->write($pth, 'compiled');
        });

        // A fake bad compiled asset that fails
        $fakeBadCompiledAsset = \Mockery::mock('\EasyAsset\CompiledAssetInterface');
        $fakeBadCompiledAsset->shouldReceive('compile')->andThrow(new CompiledAssetException());

        return new AssetContentLoader($basePath ?: __DIR__ . '/Fixtures/', new CompiledAssetsCollection([
            'style.css' => $fakeGoodCompiledAsset,
            'script.js' => $fakeBadCompiledAsset
        ]));
    }

    // ----------------------------------------------------------------

    private function write($fullPath, $content)
    {
        file_put_contents($fullPath, $content);
        $this->filesToClean[] = $fullPath;
    }
}

/* EOF: AssetContentLoaderTest.php */ 