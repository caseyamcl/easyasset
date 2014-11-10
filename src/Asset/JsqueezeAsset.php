<?php

namespace EasyAsset\Asset;

use Assetic\Filter\JSqueezeFilter;

/**
 * Class JsqueezeAsset
 * @package CompiledAsset
 */
class JsqueezeAsset extends RecursiveDirAsset
{
    public function __construct($dirs, array $filters = array(), $root = null, array $vars = array())
    {
        $filters = array_merge([new JSqueezeFilter()], $filters);
        parent::__construct($dirs, $filters, $root, $vars);
    }
}

/* EOF: JsqueezeAsset.php */