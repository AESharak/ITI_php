<?php
require_once 'db.php';
require_once 'functions.php';


function registerUser($userData, $profileImage) {
    if (!validateEmailFilter($userData['email']) || !validateEmailRegex($userData['email'])) {
        return "Please enter a valid email address";
    }
    
    if (userExists($userData['email'])) {
        return "This email is already registered";
    }
    
    if (!validatePassword($userData['password'])) {
        return "Password must be exactly 8 characters, no capital letters, only underscore as special character";
    }
    
    $imageResult = validateImage($profileImage);
    if ($imageResult !== true) {
        return $imageResult;
    }
    
    $fileName = uploadImage($profileImage);
    if (!$fileName) {
        return "Failed to upload profile image";
    }
    
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


function loginUser($email, $password) {
    $user = getUserByEmail($email);
    
    if (!$user) {
        return "Invalid email or password";
    }
    
    if (!password_verify($password, $user['password'])) {
        return "Invalid email or password";
    }
    
    $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'room' => $user['room'],
        'profile_image' => $user['profile_image']  
    ];
    
    return true;
}


function logoutUser() {
    if (isset($_SESSION['user'])) {
        unset($_SESSION['user']);
    }
    
    session_destroy();
}


function isAuthenticated() {
    return isset($_SESSION['user']);
}


function requireAuth() {
    if (!isAuthenticated()) {
        redirectWithError('login.php', 'Please login to access this page');
    }
}
?>