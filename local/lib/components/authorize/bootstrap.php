<?php

/**
 * Bootstrap the library
 */
require_once SERVER_PATH_ROOT . '/local/lib/PHPoAuthLib/vendor/autoload.php';

/**
 * Setup error reporting
 */

/**
 * Setup the timezone
 */

/**
 * Create a new instance of the URI class with the current URI, stripping the query string
 */
$uriFactory = new \OAuth\Common\Http\Uri\UriFactory();
$currentUri = $uriFactory->createFromSuperGlobalArray($_SERVER);
$currentUri->setQuery('');

/**
 * Load the credential for the different services
 */
require_once __DIR__ . '/init.php';
