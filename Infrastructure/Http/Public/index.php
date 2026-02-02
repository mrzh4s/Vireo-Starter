<?php

/**
 * Vireo Framework - Application Entry Point
 * File: public/index.php
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define global root path (get root path)
define('ROOT_PATH', dirname(__DIR__,3 ));

// Set include path using ROOT_PATH
set_include_path(ROOT_PATH);

// Load Composer autoloader
require_once ROOT_PATH . '/vendor/autoload.php';

// Bootstrap the framework
Vireo\Framework\Bootstrap::boot();

// Import Router class
use Vireo\Framework\Http\Router;


// Execute router
Router::route();
