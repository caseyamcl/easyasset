<?php

namespace EasyAsset;

use Assetic\AssetManager;
use EasyAsset\AssetContentLoader;
use Skyzyx\Components\Mimetypes\Mimetypes;

/**
 * Asset Controller
 *
 * @package EasyAsset
 */
abstract class AssetController
{
    /**
     * @var AssetManager
     */
    private $assetManager;

    /**
     * @var Mimetypes
     */
    private $mimeTypes;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param AssetManager $assetManager
     * @param Mimetypes $mimeTypes
     */
    public function __construct(AssetManager $assetManager, Mimetypes $mimeTypes = null)
    {
        $this->assetManager = $assetManager;
        $this->mimeTypes    = $mimeTypes ?: new Mimetypes();
    }

    // ----------------------------------------------------------------

    /**
     * Load asset and return/send response
     *
     * @param $path
     * @return mixed
     */
    public function loadAction($path)
    {
        // Setup streamer
        $streamer = function() use ($path) {
            echo $this->assetManager->get($path)->dump();
        };

        // If content exists, then return a response, else return a 404
        return ($this->assetManager->has($path))
            ? $this->sendContentResponse($streamer, $this->getMime($path))
            : $this->sendNotFoundResponse($path);
    }

    // ----------------------------------------------------------------

    /**
     * Return or send HTTP response
     *
     * @param callable $contentCallback  Callback that sends content to Stdout
     * @param string $mimeType  Mime type of content
     */
    abstract protected function sendContentResponse(\Closure $contentCallback, $mimeType);

    // ----------------------------------------------------------------

    /**
     * Return or send HTTP Not Found (404) Response for missing asset
     *
     * @param string $path  URL Path of asset
     */
    abstract protected function sendNotFoundResponse($path);

    // ----------------------------------------------------------------

    /**
     * Get mime type for a given path
     *
     * @param string $path
     * @return string  Returns mime type or 'application/octet-stream' as catchall
     */
    protected function getMime($path)
    {
        return $this->mimeTypes->fromFilename($path) ?: 'application/octet-stream';
    }
}

/* EOF: AssetController.php */