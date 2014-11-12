<?php

namespace EasyAssetTest\Provider\Symfony;

use EasyAsset\Provider\Symfony\AssetController;

/**
 * Class AssetControllerTest
 * @package EasyAssetTest\Provider\Symfony
 */
class AssetControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateSucceeds()
    {
        $obj = $this->getObject();
        $this->assertInstanceOf('\EasyAsset\Provider\Symfony\AssetController', $obj);
    }

    // ----------------------------------------------------------------

    public function testLoadActionReturnsSymfonyResponseForGoodAsset()
    {
        $obj = $this->getObject();
        $resp = $obj->loadAction('style.css');

        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $resp);
    }

    // ----------------------------------------------------------------

    public function testLoadActionThrowsHttpNotFoundExceptionForBadAsset()
    {
        $this->setExpectedException('\Symfony\Component\HttpKernel\Exception\NotFoundHttpException');

        $obj = $this->getObject();
        $obj->loadAction('script.js');
    }

    // ----------------------------------------------------------------

    protected function getObject()
    {
        // Get loader
        $loader = \Mockery::mock('\EasyAsset\AssetContentLoader');
        $loader->shouldReceive('exists')->with('style.css')->andReturn(true);
        $loader->shouldReceive('load')->with('style.css', '', false)->andReturn(function() { echo 'test'; });
        $loader->shouldReceive('exists')->with('script.js')->andReturn(false);
        $loader->shouldReceive('load')->with('script.js', '', false)->andThrow('\EasyAsset\Exception\AssetNotExistsException');

        return new AssetController($loader);
    }
}

/* EOF: AssetControllerTest.php */ 