<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Function to load environment variables from .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        throw new Exception(".env file not found");
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        // Remove quotes if present
        if (preg_match('/^"(.+)"$/', $value, $matches)) {
            $value = $matches[1];
        } else if (preg_match("/^'(.+)'$/", $value, $matches)) {
            $value = $matches[1];
        }
        
        putenv("$name=$value");
        $_ENV[$name] = $value;
    }
}

// Load environment variables
$envPath = realpath(dirname(__FILE__) . '/../.env');
try {
    loadEnv($envPath);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

// Database connection settings
define('DB_HOST', getenv('DB_HOST'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASS', getenv('DB_PASS'));
define('DB_NAME', getenv('DB_NAME'));

// Application paths
define('SITE_ROOT', realpath(dirname(__FILE__) . '/..'));
define('UPLOADS_DIR', SITE_ROOT . '/uploads/');

// Ensure directories exist
if (!file_exists(UPLOADS_DIR)) {
    mkdir(UPLOADS_DIR, 0755, true);
}
?>