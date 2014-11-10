<?php

namespace EasyAsset\Asset;

use Assetic\Filter\LessphpFilter;

/**
 * Shortcut Asset for loading LESS resources
 *
 * @package EasyAsset
 */
class LessAsset extends RecursiveDirAsset
{
    public function __construct($dirs, array $filters = array(), $root = null, array $vars = array())
    {
        $filters = array_merge([new LessphpFilter()], $filters);
        parent::__construct($dirs, $filters, $root, $vars);
    }
}

/* EOF: LessAsset.php */