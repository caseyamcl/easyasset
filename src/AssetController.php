<?php

namespace EasyAsset;

use EasyAsset\AssetContentLoader;
use EasyAsset\Exception\AssetNotExistsException;
use Skyzyx\Components\Mimetypes\Mimetypes;

/**
 * Asset Controller
 *
 * @package EasyAsset
 */
abstract class AssetController
{
    /**
     * @var AssetContentLoader
     */
    private $assetLoader;

    /**
     * @var bool
     */
    private $forceCompile;

    /**
     * @var Mimetypes
     */
    private $mimeTypes;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param AssetContentLoader $loader
     * @param bool $forceCompile
     * @param Mimetypes $mimeTypes
     */
    public function __construct(AssetContentLoader $loader, $forceCompile = false, Mimetypes $mimeTypes = null)
    {
        $this->assetLoader  = $loader;
        $this->mimeTypes    = $mimeTypes ?: new Mimetypes();
        $this->forceCompile = $forceCompile;
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
            echo $this->assetLoader->load($path, $this->forceCompile);
        };

        // If content exists, then return a response, else return a 404
        return ($this->assetLoader->exists(($path)))
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