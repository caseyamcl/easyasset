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
 * Write assets out to file
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class AssetFileWriter
{
    /**
     * @var string
     */
    private $writePath;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param $writePath
     */
    public function __construct($writePath)
    {
        $this->writePath = rtrim($writePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        if ( ! is_dir($writePath)) {
            throw new \InvalidArgumentException("Output path for writing assets must be a directory: " . $writePath);
        }
    }

    // ----------------------------------------------------------------

    /**
     * Get write path
     *
     * @param string|null $assetRelPath
     * @return string
     */
    public function getWritePath($assetRelPath = null)
    {
        return $this->writePath . ltrim($assetRelPath, '/');
    }

    // ----------------------------------------------------------------

    /**
     * Write an asset
     *
     * @param $relPath
     * @param CompiledAssetInterface $compiledAsset
     */
    public function writeAsset($relPath, CompiledAssetInterface $compiledAsset)
    {
        // Determine filename
        $fullPath = $this->getWritePath($relPath);

        // Create relative path if not exists
        if ( ! file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        // Write the asset out to the file
        $outStream = fopen($fullPath, 'w');
        $compiledAsset->compile($outStream);
        fclose($outStream);
    }

    // ----------------------------------------------------------------

    /**
     * Write all assets
     *
     * @param CompiledAssetsCollection $assets
     */
    public function writeAssetCollection(CompiledAssetsCollection $assets)
    {
        foreach ($assets as $relPath => $asset) {
            $this->writeAsset($relPath, $asset);
        }
    }
}

/* EOF: AssetFileWriter.php */ 
