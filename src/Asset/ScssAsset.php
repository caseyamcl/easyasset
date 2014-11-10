<?php

namespace EasyAsset\Asset;

use Assetic\Filter\Sass\ScssFilter;

/**
 * SASS Compiled Asset
 *
 * @package CompiledAsset
 */
class ScssAsset extends RecursiveDirAsset
{
    public function __construct($dirs, array $filters = array(), $root = null, array $vars = array())
    {
        $filters = array_merge([new ScssFilter()], $filters);
        parent::__construct($dirs, $filters, $root, $vars);
    }
}

/* EOF: ScssAsset.php */