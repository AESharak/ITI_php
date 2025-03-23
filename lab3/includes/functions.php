<?php
require_once 'config.php';

/**
 * Validate email using filter_var
 */
function validateEmailFilter($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate email using regex pattern
 */
function validateEmailRegex($email) {
    $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    return preg_match($pattern, $email);
}

/**
 * Validate password
 * - Exactly 8 chars
 * - No special chars except underscore
 * - No capital letters
 */
function validatePassword($password) {
    // Check length is exactly 8
    if (strlen($password) !== 8) {
        return false;
    }
    
    // Check for no capital letters
    if (preg_match('/[A-Z]/', $password)) {
        return false;
    }
    
    // Check for special chars (only underscore allowed)
    if (preg_match('/[^a-z0-9_]/', $password)) {
        return false;
    }
    
    return true;
}

/**
 * Get users from file
 */
function getUsers() {
    // Initialize file if needed
    initializeUsersFile();
    
    if (file_exists(USERS_FILE)) {
        $jsonData = file_get_contents(USERS_FILE);
        $users = json_decode($jsonData, true);
        return is_array($users) ? $users : [];
    }
    
    return [];
}

/**
 * Save users to file
 */
function saveUsers($users) {
    // Make sure users directory exists with proper permissions
    $userDir = dirname(USERS_FILE);
    if (!file_exists($userDir)) {
        if (!mkdir($userDir, 0777, true)) {
            error_log("Cannot create directory: $userDir");
            return false;
        }
        // Set directory permissions to be writable by web server
        chmod($userDir, 0777);
    }
    
    // Create JSON data
    $jsonData = json_encode($users, JSON_PRETTY_PRINT);
    if ($jsonData === false) {
        error_log("JSON encoding failed");
        return false;
    }
    
    // Try to write file with proper permissions
    $result = file_put_contents(USERS_FILE, $jsonData);
    if ($result === false) {
        error_log("Failed to write to file: " . USERS_FILE);
        return false;
    }
    
    // Ensure the file is readable/writable
    chmod(USERS_FILE, 0666);
    
    return true;
}

/**
 * Check if user exists
 */
function userExists($email) {
    $users = getUsers();
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return true;
        }
    }
    return false;
}

/**
 * Validate image upload
 */
function validateImage($file) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 2 * 1024 * 1024; // 2MB
    
    if (!in_array($file['type'], $allowedTypes)) {
        return "Only JPG, PNG, and GIF files are allowed";
    }
    
    if ($file['size'] > $maxSize) {
        return "File is too large (max 2MB)";
    }
    
    return true;
}

/**
 * Upload image
 */
function uploadImage($file) {
    $fileName = time() . '_' . basename($file['name']);
    $targetPath = UPLOADS_DIR . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $fileName;
    }
    
    return false;
}

/**
 * Redirect with error message
 */
function redirectWithError($page, $message) {
    $_SESSION['error'] = $message;
    header("Location: $page");
    exit;
}

/**
 * Redirect with success message
 */
function redirectWithSuccess($page, $message) {
    $_SESSION['success'] = $message;
    header("Location: $page");
    exit;
}

/**
 * Check if user is authenticated
 */
function isAuthenticated() {
    return isset($_SESSION['user']);
}

/**
 * Require authentication
 */
function requireAuth() {
    if (!isAuthenticated()) {
        redirectWithError('login.php', 'Please login to access this page');
    }
}

/**
 * Initialize users file if it doesn't exist
 */
function initializeUsersFile() {
    if (!file_exists(USERS_FILE)) {
        $emptyArray = [];
        $jsonData = json_encode($emptyArray, JSON_PRETTY_PRINT);
        file_put_contents(USERS_FILE, $jsonData);
        
        // Set proper permissions
        chmod(USERS_FILE, 0664);
    }
}