<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all required fields are provided
    $requiredFields = ['name', 'email', 'password', 'confirm_password', 'room'];
    $missingFields = [];
    
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $missingFields[] = ucfirst(str_replace('_', ' ', $field));
        }
    }
    
    // Check if profile image was uploaded
    if (empty($_FILES['profile_image']['name'])) {
        $missingFields[] = 'Profile Image';
    }
    
    if (!empty($missingFields)) {
        redirectWithError('register.php', 'Please fill in: ' . implode(', ', $missingFields));
    } else {
        // Process registration
        $result = registerUser($_POST, $_FILES['profile_image']);
        
        if ($result === true) {
            redirectWithSuccess('login.php', 'Registration successful! Please login.');
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
        <h1 class="text-xl font-semibold text-gray-900">Register New User</h1>
        <p class="mt-1 text-sm text-gray-500">Please fill in all required fields</p>
    </div>
    
    <div class="p-6">
        <form action="register.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <!-- Name -->
            <div class="space-y-2">
                <label for="name" class="block text-sm font-semibold text-gray-900">Name</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200" placeholder="Enter your name">
            </div>
            
            <!-- Email -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-semibold text-gray-900">Email</label>
                <input type="email" id="email" name="email" class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200" placeholder="you@example.com">
            </div>
            
            <!-- Password -->
            <div class="space-y-2">
                <label for="password" class="block text-sm font-semibold text-gray-900">Password</label>
                <input type="password" id="password" name="password" class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200" placeholder="••••••••">
                <p class="text-xs text-gray-500">Must be exactly 8 characters, no capital letters, and only underscore as special character.</p>
            </div>
            
            <!-- Confirm Password -->
            <div class="space-y-2">
                <label for="confirm_password" class="block text-sm font-semibold text-gray-900">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200" placeholder="••••••••">
            </div>
            
            <!-- Room No -->
            <div class="space-y-2">
                <label for="room" class="block text-sm font-semibold text-gray-900">Room No</label>
                <select id="room" name="room" class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                    <option value="">Select Room</option>
                    <option value="Application1">Application1</option>
                    <option value="Application2">Application2</option>
                    <option value="Cloud">Cloud</option>
                </select>
            </div>
            
            
            <!-- Profile Picture -->
            <div class="space-y-2">
                <label for="profile_image" class="block text-sm font-semibold text-gray-900">Profile Picture</label>
                <div class="flex items-center">
                    <input type="file" id="profile_image" name="profile_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
                <p class="text-xs text-gray-500">Only JPG, PNG, or GIF. Max size: 2MB.</p>
            </div>
            
            <!-- Buttons -->
            <div class="flex space-x-4 pt-6">
                <button type="submit" class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200">Register</button>
                <a href="login.php" class="flex-1 bg-white text-gray-700 px-6 py-3 rounded-lg font-medium border border-gray-200 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-2 transition-all duration-200 text-center">Login</a>
            </div>
        </form>
    </div>
</div>

</div>
</body>
</html>