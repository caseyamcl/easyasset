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
 * Compiled Assets Collection
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class CompiledAssetsCollection implements \IteratorAggregate
{
    /**
     * @var array
     */
    private $compilers;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param array $compilers
     */
    public function __construct(array $compilers = [])
    {
        $this->compilers = [];
        $this->set($compilers);
    }

    // ----------------------------------------------------------------

    /**
     * Set compilers
     *
     * @param array  Key/value  keys are paths, values are CompiledAssetInterface objects
     */
    public function set(array $compilers)
    {
        foreach ($compilers as $path => $compiler) {
            $this->add($path, $compiler);
        }
    }

    // ----------------------------------------------------------------

    /**
     * Add (set) a compiler
     *
     * @param $path
     * @param CompiledAssetInterface $compiler
     */
    public function add($path, CompiledAssetInterface $compiler)
    {
        $this->compilers[$path] = $compiler;
    }

    // ----------------------------------------------------------------

    /**
     * Compiler exists?
     *
     * @param $path
     * @return bool
     */
    public function has($path)
    {
        return isset($this->compilers[$path]);
    }

    // ----------------------------------------------------------------

    /**
     * Get compiler
     *
     * @return CompiledAssetInterface
     */
    public function get($path)
    {
        return $this->compilers[$path];

    }

    // ----------------------------------------------------------------

    /**
     * Get iterator
     *
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->compilers);
    }
}

/* EOF: CompiledAssetsCollection.php */ 
