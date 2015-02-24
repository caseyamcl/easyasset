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

/**
 * Interface AssetControllerInterface
 *
 * @package EasyAsset
 */
interface AssetControllerInterface
{
    /**
     * Load Action
     *
     * @param string $path  Path to asset
     * @return mixed  Some type of response object
     */
    function loadAction($path);
}
