<?php

/**
 * Entry point untuk Hostinger shared hosting.
 * Web root mengarah ke public_html/ bukan public_html/public/.
 * File ini meneruskan semua request ke public/index.php.
 */

define('LARAVEL_START', microtime(true));

// Redirect ke public/index.php dengan path yang benar
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/public/index.php';
$_SERVER['SCRIPT_NAME']     = '/public/index.php';

require __DIR__ . '/public/index.php';
