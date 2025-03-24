<?php
require_once 'config.php';
require_once 'db.php';


function validateEmailFilter($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}


function validateEmailRegex($email) {
    $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    return preg_match($pattern, $email);
}


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


function validateImage($file) {
    if ($file['size'] == 0) {
        return "Please select an image file";
    }
    
    if ($file['size'] > 4 * 1024 * 1024) {
        return "Image size should not exceed 4MB";
    }
    

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/webp', 'image/svg+xml', 'image/bmp', 'image/tiff'];
    if (!in_array($file['type'], $allowedTypes)) {
        return "Only JPG, PNG, GIF, WEBP, SVG, BMP, or TIFF images are allowed";
    }
    
    return true;
}

function uploadImage($file) {
    $fileName = time() . '_' . $file['name'];
    $targetPath = UPLOADS_DIR . $fileName;
    
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        error_log("Failed to move uploaded file from {$file['tmp_name']} to {$targetPath}");
        return false;
    }
    
    return $fileName;
}

function initializeUsersTable() {
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        room VARCHAR(50) NOT NULL,
        profile_image VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    
    executeQuery($sql);
}
?>