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

/*
 * PHPUnit Test Bootstrap File
 */

// What files need to be present
$checkFiles['autoload'] = __DIR__ . '/../vendor/autoload.php';
$checkFiles[] = __DIR__ . '/../vendor/mockery/mockery/library/Mockery.php';

// Check that files exist
foreach($checkFiles as $file) {
    if ( ! file_exists($file)) {
        die('Install dependencies with --dev option to run test suite (# composer.phar install)' . "\n");
    }
}

//Away we go
$autoload = require_once($checkFiles['autoload']);

/* EOF: bootstrap.php */
