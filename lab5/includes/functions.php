<?php
require_once 'config.php';
require_once 'db.php';

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
    if (strlen($password) !== 8) {
        return false;
    }
    
    if (preg_match('/[A-Z]/', $password)) {
        return false;
    }
    
    if (preg_match('/[^a-z0-9_]/', $password)) {
        return false;
    }
    
    return true;
}

/**
 * Validate uploaded image
 */
function validateImage($file) {
    // Check if a file was uploaded
    if ($file['size'] == 0) {
        return "Please select an image file";
    }
    
    // Check file size (max 4MB)
    if ($file['size'] > 4 * 1024 * 1024) {
        return "Image size should not exceed 4MB";
    }
    

    // Check file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/webp', 'image/svg+xml', 'image/bmp', 'image/tiff'];
    if (!in_array($file['type'], $allowedTypes)) {
        return "Only JPG, PNG, GIF, WEBP, SVG, BMP, or TIFF images are allowed";
    }
    
    return true;
}

/**
 * Upload image and return the filename
 */
function uploadImage($file) {
    // Create a unique filename
    $fileName = time() . '_' . $file['name'];
    $targetPath = UPLOADS_DIR . $fileName;
    
    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        error_log("Failed to move uploaded file from {$file['tmp_name']} to {$targetPath}");
        return false;
    }
    
    return $fileName;
}

/**
 * Initialize users table if it doesn't exist
 */
function initializeUsersTable() {
    $conn = dbConnect();
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        room VARCHAR(50) NOT NULL,
        profile_image VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
}
?>