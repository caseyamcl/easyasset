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

namespace EasyAsset\Provider\Symfony;

use EasyAsset\AssetController as BaseAssetController;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Symfony Asset Controller
 *
 * Works with any application that uses Symfony HttpKernel
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
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
     * @return void
     * @throws NotFoundHttpException
     */
    protected function sendNotFoundResponse($path)
    {
        throw new NotFoundHttpException("Could not find asset: " . $path);
    }
}

/* EOF: AssetController.php */
