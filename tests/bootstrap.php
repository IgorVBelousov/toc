<?php

// HEADER HERE
// ---------------------------------------------------------------

/**
 * PHP TOC Unit Tests Bootstrap File
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/igorvbelousov/toc
 * @version 1.0
 * @package igorvbelousov/toc
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 * @author Igor V Belousov <igor@belousovv.ru>
 */

//Files to ensure exist
$checkFiles['autoload'] = __DIR__.'/../vendor/autoload.php';
$checkFiles[] = __DIR__.'/../vendor/sunra/php-simple-html-dom-parser/README.md';

//Check 'Em
foreach($checkFiles as $file) {

    if ( ! file_exists($file)) {
        throw new RuntimeException('Install development dependencies to run test suite.');
    }
}

//Away we go
$autoload = require_once $checkFiles['autoload'];

/* EOF: bootstrap.php */