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
 * Get users from the database
 */
function getUsers() {
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT * FROM users");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Save user to the database
 */
function saveUser($user) {
    $conn = dbConnect();
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, room, profile_image, created_at) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([
        $user['name'],
        $user['email'],
        password_hash($user['password'], PASSWORD_DEFAULT),
        $user['room'],
        $user['profile_image'],
        date('Y-m-d H:i:s')
    ]);
}

/**
 * Check if user exists
 */
function userExists($email) {
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetchColumn() > 0;
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