<?php
require_once 'db.php';
require_once 'functions.php';

/**
 * Register a new user
 */
function registerUser($userData, $profileImage) {
    // Validate email using both methods
    if (!validateEmailFilter($userData['email']) || !validateEmailRegex($userData['email'])) {
        return "Please enter a valid email address";
    }
    
    // Check if user already exists
    if (userExists($userData['email'])) {
        return "This email is already registered";
    }
    
    // Validate password
    if (!validatePassword($userData['password'])) {
        return "Password must be exactly 8 characters, no capital letters, only underscore as special character";
    }
    
    // Validate image
    $imageResult = validateImage($profileImage);
    if ($imageResult !== true) {
        return $imageResult;
    }
    
    // Upload image
    $fileName = uploadImage($profileImage);
    if (!$fileName) {
        return "Failed to upload profile image";
    }
    
    // Insert user into database
    try {
        insertUser(
            $userData['name'],
            $userData['email'],
            $userData['password'],
            $userData['room'],
            $fileName
        );
        return true;
    } catch (Exception $e) {
        error_log("Error registering user: " . $e->getMessage());
        return "Registration failed. Please try again later.";
    }
}

/**
 * Login user
 */
function loginUser($email, $password) {
    // Get user by email
    $user = getUserByEmail($email);
    
    if (!$user) {
        return "Invalid email or password";
    }
    
    // Verify password
    if (!password_verify($password, $user['password'])) {
        return "Invalid email or password";
    }
    
    // Set session data
    $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'room' => $user['room'],
        'profile_image' => $user['profile_image']  // Add this line
    ];
    
    return true;
}

/**
 * Logout user
 */
function logoutUser() {
    if (isset($_SESSION['user'])) {
        unset($_SESSION['user']);
    }
    
    session_destroy();
}

/**
 * Check if user is authenticated
 */
function isAuthenticated() {
    return isset($_SESSION['user']);
}

/**
 * Require authentication to access a page
 */
function requireAuth() {
    if (!isAuthenticated()) {
        redirectWithError('login.php', 'Please login to access this page');
    }
}
?>