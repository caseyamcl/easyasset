<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/12/14
 * Time: 11:10 AM
 */

namespace EasyAssetTest;

/**
 * Test Helpers
 *
 * @package EasyAssetTest
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