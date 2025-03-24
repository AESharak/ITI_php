<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USER', 'aesharak'); // Make sure this is correct
define('DB_PASS', 'aeey'); // Make sure this is correct
define('DB_NAME', 'php_lab4');

// Application paths
define('SITE_ROOT', realpath(dirname(__FILE__) . '/..'));
define('UPLOADS_DIR', SITE_ROOT . '/uploads/');

// Ensure directories exist
if (!file_exists(UPLOADS_DIR)) {
    mkdir(UPLOADS_DIR, 0755, true);
}
?>