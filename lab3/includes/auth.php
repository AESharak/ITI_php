<?php
require_once 'functions.php';

/**
 * Register a new user
 */
function registerUser($userData, $profileImage) {
    // Validate email using both methods
    if (!validateEmailFilter($userData['email']) || !validateEmailRegex($userData['email'])) {
        return "Invalid email format";
    }
    
    // Check if user already exists
    if (userExists($userData['email'])) {
        return "Email already registered";
    }
    
    // Validate password
    if (!validatePassword($userData['password'])) {
        return "Password must be exactly 8 characters, contain no capitals, and only allow underscores as special characters";
    }
    
    // Confirm passwords match
    if ($userData['password'] !== $userData['confirm_password']) {
        return "Passwords do not match";
    }
    
    // Upload profile image
    $imageResult = validateImage($profileImage);
    if ($imageResult !== true) {
        return $imageResult;
    }
    
    $fileName = uploadImage($profileImage);
    if (!$fileName) {
        return "Failed to upload profile image";
    }
    
    // Create user record
    $user = [
        'name' => $userData['name'],
        'email' => $userData['email'],
        'password' => password_hash($userData['password'], PASSWORD_DEFAULT),
        'room' => $userData['room'],
        'profile_image' => $fileName,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    // Save user
    $users = getUsers();
    $users[] = $user;
    
    if (saveUsers($users)) {
        return true;
    } else {
        return "Failed to save user data";
    }
}

/**
 * Authenticate user
 */
function loginUser($email, $password) {
    $users = getUsers();
    
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            if (password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user'] = [
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'room' => $user['room'],
                    'profile_image' => $user['profile_image']
                ];
                
                return true;
            } else {
                return "Invalid credentials";
            }
        }
    }
    
    return "Invalid credentials";
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