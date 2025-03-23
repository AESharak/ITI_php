<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

// Check if user is already logged in
if (isAuthenticated()) {
    header("Location: welcome.php");
    exit;
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        redirectWithError('login.php', 'Please provide both email and password');
    } else {
        $result = loginUser($email, $password);
        
        if ($result === true) {
            header("Location: welcome.php");
            exit;
        } else {
            redirectWithError('login.php', $result);
        }
    }
}

// Display login form
include 'includes/header.php';
?>

<div class="max-w-lg mx-auto bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h1 class="text-xl font-semibold text-gray-900">Login</h1>
        <p class="mt-1 text-sm text-gray-500">Enter your credentials to access your account</p>
    </div>
    
    <div class="p-6">
        <form action="login.php" method="POST" class="space-y-6">
            <!-- Email -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-semibold text-gray-900">Email</label>
                <input type="email" id="email" name="email" required class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200" placeholder="you@example.com">
            </div>
            
            <!-- Password -->
            <div class="space-y-2">
                <label for="password" class="block text-sm font-semibold text-gray-900">Password</label>
                <input type="password" id="password" name="password" required class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200" placeholder="••••••••">
            </div>
            
            <!-- Buttons -->
            <div class="pt-6">
                <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200">Login</button>
                <div class="mt-4 text-center">
                    <a href="register.php" class="text-sm text-indigo-600 hover:text-indigo-500">Don't have an account? Register</a>
                </div>
            </div>
        </form>
    </div>
</div>

</div>
</body>
</html>