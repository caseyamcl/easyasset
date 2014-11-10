<?php

namespace EasyAsset\Asset;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Filter\FilterInterface;
use Assetic\Util\VarUtils;
use RecursiveIteratorIterator, RecursiveDirectoryIterator;

/**
 * Assetic Asset type for a collection of assets loaded recursively by directory path
 *
 * @author Casey Mclaughlin <caseyamcl@gmail.com>
 */
class RecursiveDirAsset extends AssetCollection
{
    /**
     * @var array  Array of directories to scan
     */
    private $dirs;

    /**
     * @var boolean  Whether or not the class has been initialized
     */
    private $initialized;

    /**
     * Constructor
     *
     * @param string|array $dirs    A single directory or multiple directories
     * @param array        $filters An array of filters
     * @param string       $root    The root directory
     * @param array        $vars
     */
    public function __construct($dirs, $filters = array(), $root = null, array $vars = array())
    {
        $this->dirs = (array) $dirs;
        $this->initialized = false;

        parent::__construct(array(), $filters, $root, $vars);
    }

    public function all()
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        return parent::all();
    }

    public function load(FilterInterface $additionalFilter = null)
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        parent::load($additionalFilter);
    }

    public function dump(FilterInterface $additionalFilter = null)
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        return parent::dump($additionalFilter);
    }

    public function getLastModified()
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        return parent::getLastModified();
    }

    public function getIterator()
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        return parent::getIterator();
    }

    public function setValues(array $values)
    {
        parent::setValues($values);
        $this->initialized = false;
    }

    /**
     * Initializes the collection based on the glob(s) passed in.
     */
    private function initialize()
    {
        foreach ($this->dirs as $dir) {

            $dir = VarUtils::resolve($dir, $this->getVars(), $this->getValues());
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

            // Convert to array and sort ti
            $arr = iterator_to_array($iterator);
            usort($arr, function(\SplFileInfo $a, \SplFileInfo $b) {
                return strcmp($a->getPathname(), $b->getPathname());
            });

            foreach ($arr as $file) {
                if ($file->isFile()) {
                    $this->add(new FileAsset((string) $file, array(), $this->getSourceRoot()));
                }
            }
        }

        $this->initialized = true;
    }
}

/* EOF: RecursiveDirAsset.php */