<?php
// Configuration settings
session_start();

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USER', 'aesharak'); // 
define('DB_PASS', 'aeey'); // Replace with your database password
define('DB_NAME', 'php_lab4'); // Replace with your database name

// Application paths
define('SITE_ROOT', realpath(dirname(__FILE__) . '/..'));
define('UPLOADS_DIR', SITE_ROOT . '/uploads/');

// Ensure directories exist
if (!file_exists(UPLOADS_DIR)) {
    mkdir(UPLOADS_DIR, 0755, true);
}
?>