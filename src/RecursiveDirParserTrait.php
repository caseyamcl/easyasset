<?php

namespace EasyAsset;

/**
 * Adds recursive directory parsing to asset processors that support it
 */
trait RecursiveDirParserTrait
{
    /**
     * Get file iterator for path (file or directory)
     *
     * @param $fileOrDirPath
     * @throws \RuntimeException
     * @return \SplFileInfo[]
     */
    protected function getFileIterator($fileOrDirPath)
    {
        if ( ! is_readable($fileOrDirPath)) {
            throw new \RuntimeException("Could not read files at path: " . $fileOrDirPath);
        }

        //If is a file, just return an iterator with a single file
        if (is_file($fileOrDirPath)) {
           $arr = [$fileOrDirPath];
        }
        else {
            // Else if directory, get all files as basic iterator..
            $allFiles = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($fileOrDirPath));

            // Convert it to an array..
            $arr = iterator_to_array($allFiles);
            usort($arr, function(\SplFileInfo $a, \SplFileInfo $b) {
                return strcmp($a->getPathname(), $b->getPathname());
            });
        }

        return new \ArrayIterator($arr);
    }

    // ----------------------------------------------------------------

    /**
     * Get a string of a whole bunch of files
     *
     * Can be memory-intensive
     *
     * @param $fileOrDirPath
     * @return string
     */
    protected function getCombinedFiles($fileOrDirPath)
    {
        $outStr = '';

        foreach ($this->getFileIterator($fileOrDirPath) as $file) {
            $outStr .= file_get_contents($file) . PHP_EOL;
        }

        return $outStr;
    }

}

/* EOF: RecursiveDirParserTrait.php */ 