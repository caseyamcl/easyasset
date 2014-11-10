<?php

namespace EasyAsset\Provider\Symfony;

use EasyAsset\AssetController as BaseAssetController;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Symfony Asset Controller
 *
 * @package EasyAsset
 */
class AssetController extends BaseAssetController
{
    /**
     * Load an asset
     *
     * @param string $path
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loadAction($path)
    {
        return parent::loadAction($path);
    }

    // ----------------------------------------------------------------

    /**
     * Return or send HTTP response
     *
     * @param callable $contentCallback Callback that sends content to Stdout
     * @param string $mimeType Mime-type of content
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function sendContentResponse(\Closure $contentCallback, $mimeType)
    {
        return new StreamedResponse($contentCallback, 200, ['Content-type' => $mimeType]);
    }

    // ----------------------------------------------------------------

    /**
     * Return or send HTTP Not Found (404) Response for missing asset
     *
     * @param string $path URL Path of asset
     * @throws NotFoundHttpException
     */
    protected function sendNotFoundResponse($path)
    {
        throw new NotFoundHttpException("Could not find asset: " . $path);
    }
}

/* EOF: AssetController.php */