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

namespace EasyAsset;

use Skyzyx\Components\Mimetypes\Mimetypes;

/**
 * Generic Asset Controller
 *
 * Extend this class to use the semantics of your framework's
 * HTTP request/response layer.
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
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
     * @param AssetContentLoaderInterface $loader
     * @param bool                        $forceCompile  If TRUE, force compiliation of assets even if the exist
     * @param Mimetypes                   $mimeTypes
     */
    public function __construct(AssetContentLoaderInterface $loader, $forceCompile = false, Mimetypes $mimeTypes = null)
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
        // If content exists, then return a response, else return a 404
        return ($this->assetLoader->exists(($path)))
            ? $this->sendContentResponse($this->assetLoader->load($path, null, $this->forceCompile), $this->getMime($path))
            : $this->sendNotFoundResponse($path);
    }

    // ----------------------------------------------------------------

    /**
     * Return or send HTTP response
     *
     * Depending on how your framework works, you can either build and send
     * (echo to STDOUT) a HTTP response here, or you can build and return it
     *
     * @param callable $contentCallback  Callback that sends content to Stdout
     * @param string $mimeType  Mime type of content
     * @return mixed
     */
    abstract protected function sendContentResponse(\Closure $contentCallback, $mimeType);

    // ----------------------------------------------------------------

    /**
     * Return or send HTTP Not Found (404) Response for missing asset
     *
     * Depending on how your framework works, you can either build and send
     * (echo to STDOUT) an error HTTP response here, or you can return some sort
     * of error response object, or throw an exception to indicate a 404.
     *
     * @param string $path  URL Path of asset
     * @return mixed
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
