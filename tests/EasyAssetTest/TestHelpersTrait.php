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

/**
 * Test Helpers
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
trait TestHelpersTrait
{
    /**
     * @return string
     */
    protected function getFixtureDir()
    {
        return realpath(__DIR__ . '/Fixtures') . DIRECTORY_SEPARATOR;
    }

    // ----------------------------------------------------------------

    /**
     * @return resource  Read/write stream
     */
    protected function getOutputStream()
    {
        return fopen('php://temp', 'r+');
    }

    // ----------------------------------------------------------------

    /**
     * @param resource $outStream  Readable stream
     * @return string
     */
    protected function getOutStreamContents($outStream)
    {
        rewind($outStream);

        $content = '';
        while ( ! feof($outStream)) {
            $content .= fread($outStream, 2046);
        }
        return $content;
    }
}

/* EOF: TestHelpersTrait.php */ 
