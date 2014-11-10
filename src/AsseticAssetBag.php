<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/10/14
 * Time: 4:37 PM
 */

namespace EasyAsset;


use Assetic\Asset\AssetInterface;
use Assetic\AssetManager;

/**
 * Assetic Asset Bag is just a sane version of the asset manager
 *
 * @package EasyAsset
 */
class AsseticAssetBag extends AssetManager
{
    public function get($name)
    {
        return parent::get($this->encodeName($name));
    }

    public function has($name)
    {
        return parent::has($this->encodeName($name));
    }

    public function set($name, AssetInterface $asset)
    {
        parent::set($this->encodeName($name), $asset);
    }

    public function getNames()
    {
        $arr = [];
        foreach (parent::getNames() as $name) {
            $arr[] = $this->decodeName($name);
        }
        return $arr;
    }

    // ----------------------------------------------------------------

    public function getEncodedNames()
    {
        return parent::getNames();
    }

    // ----------------------------------------------------------------

    private function encodeName($name)
    {
        $name = str_replace('/', '_SLASH_', $name);
        $name = str_replace('.', '_DOT_', $name);

        return $name;
    }

    private function decodeName($name)
    {
        $name = str_replace('_SLASH_', '/', $name);
        $name = str_replace('_DOT_', '.', $name);

        return $name;
    }
}


/* EOF: AsseticAssetBag.php */