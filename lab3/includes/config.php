<?php
// Configuration settings
session_start();

// Application paths
define('SITE_ROOT', realpath(dirname(__FILE__) . '/..'));
define('USERS_FILE', SITE_ROOT . '/users/users.json');
define('UPLOADS_DIR', SITE_ROOT . '/uploads/');

// Ensure directories exist
if (!file_exists(UPLOADS_DIR)) {
    mkdir(UPLOADS_DIR, 0755, true);
}

if (!file_exists(dirname(USERS_FILE))) {
    mkdir(dirname(USERS_FILE), 0755, true);
}