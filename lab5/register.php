<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Check if user is already logged in
if (isAuthenticated()) {
    header("Location: welcome.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all required fields are provided
    $requiredFields = ['name', 'email', 'password', 'confirm_password', 'room'];
    $missingFields = [];
    
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $missingFields[] = $field;
        }
    }
    
    if (!empty($missingFields)) {
        redirectWithError('register.php', 'Please fill in all required fields');
    } else {
        // Validate passwords match
        if ($_POST['password'] !== $_POST['confirm_password']) {
            redirectWithError('register.php', 'Passwords do not match');
        }
        
        // Prepare user data
        $userData = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'room' => $_POST['room']
        ];
        
        // Check file upload
        if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] === UPLOAD_ERR_NO_FILE) {
            redirectWithError('register.php', 'Please upload a profile image');
        }
        
        // Register the user
        $result = registerUser($userData, $_FILES['profile_image']);
        
        if ($result === true) {
            redirectWithSuccess('login.php', 'Registration successful! You can now login.');
        } else {
            redirectWithError('register.php', $result);
        }
    }
}

// Display registration form
include 'includes/header.php';
?>

<div class="max-w-lg mx-auto bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h1 class="text-xl font-semibold text-gray-900">Create an account</h1>
        <p class="mt-1 text-sm text-gray-500">Fill in your details to register</p>
    </div>
    
    <div class="p-6">
        <form action="register.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <!-- Name -->
            <div class="space-y-2">
                <label for="name" class="block text-sm font-semibold text-gray-900">Full Name</label>
                <input type="text" id="name" name="name" required class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
            </div>
            
            <!-- Email -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-semibold text-gray-900">Email</label>
                <input type="email" id="email" name="email" required class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
            </div>
            
            <!-- Password -->
            <div class="space-y-2">
                <label for="password" class="block text-sm font-semibold text-gray-900">Password</label>
                <input type="password" id="password" name="password" required class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                <p class="text-xs text-gray-500">Password must be exactly 8 characters, no capital letters, only underscore allowed as special character</p>
            </div>
            
            <!-- Confirm Password -->
            <div class="space-y-2">
                <label for="confirm_password" class="block text-sm font-semibold text-gray-900">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
            </div>
            
            <!-- Room Selection -->
            <div class="space-y-2">
                <label for="room" class="block text-sm font-semibold text-gray-900">Room</label>
                <select id="room" name="room" required class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                    <option value="">Select Room</option>
                    <option value="Application1">Application 1</option>
                    <option value="Application2">Application 2</option>
                    <option value="Cloud">Cloud</option>
                </select>
            </div>
            
            <!-- Profile Image -->
            <div class="space-y-2">
                <label for="profile_image" class="block text-sm font-semibold text-gray-900">Profile Image</label>
                <input type="file" id="profile_image" name="profile_image" required accept="image/*" class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
            </div>
            
            <!-- Submit Button -->
            <div class="pt-6">
                <button type="submit" class="w-full px-6 py-3 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200">Register</button>
                <p class="mt-4 text-center text-sm text-gray-600">Already have an account? <a href="login.php" class="text-indigo-600 hover:text-indigo-500 font-medium">Login here</a></p>
            </div>
        </form>
    </div>
</div>
</body>
</html>