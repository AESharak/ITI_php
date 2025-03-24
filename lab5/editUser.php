<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Protect this page
requireAuth();

// Check if an ID was provided
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirectWithError('usersListing.php', 'Invalid user ID');
}

$userId = $_GET['id'];
$user = getUserById($userId);

// Check if user exists
if(!$user) {
    redirectWithError('usersListing.php', 'User not found');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare user data
    $userData = [
        'id' => $userId,
        'name' => $_POST['name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'room' => $_POST['room'] ?? ''
    ];
    
    // Validate required fields
    if(empty($userData['name']) || empty($userData['email']) || empty($userData['room'])) {
        redirectWithError("editUser.php?id=$userId", 'All fields are required');
    }
    
    // Check if email belongs to another user
    if($userData['email'] !== $user['email'] && userExistsByEmail($userData['email'])) {
        redirectWithError("editUser.php?id=$userId", 'Email address is already in use');
    }
    
    // Process password change if provided
    if(!empty($_POST['password'])) {
        // Validate password
        if(!validatePassword($_POST['password'])) {
            redirectWithError("editUser.php?id=$userId", 'Password must be exactly 8 characters, no capital letters, only underscore allowed as special character');
        }
        
        if($_POST['password'] !== $_POST['confirm_password']) {
            redirectWithError("editUser.php?id=$userId", 'Passwords do not match');
        }
        
        $userData['password'] = $_POST['password'];
    }
    
    // Process image upload if provided
    if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Validate image
        $imageResult = validateImage($_FILES['profile_image']);
        if($imageResult !== true) {
            redirectWithError("editUser.php?id=$userId", $imageResult);
        }
        
        // Upload image
        $fileName = uploadImage($_FILES['profile_image']);
        if(!$fileName) {
            redirectWithError("editUser.php?id=$userId", 'Failed to upload profile image');
        }
        
        $userData['profile_image'] = $fileName;
    }
    
    // Update user
    $result = updateUser($userData);
    
    if($result === true) {
        // If current user is being updated, update session data
        if($_SESSION['user']['id'] == $userId) {
            $_SESSION['user']['name'] = $userData['name'];
            $_SESSION['user']['email'] = $userData['email'];
            $_SESSION['user']['room'] = $userData['room'];
            if(isset($userData['profile_image'])) {
                $_SESSION['user']['profile_image'] = $userData['profile_image'];
            }
        }
        
        redirectWithSuccess('usersListing.php', 'User updated successfully');
    } else {
        redirectWithError("editUser.php?id=$userId", $result);
    }
}

include 'includes/header.php';
?>

<div class="max-w-lg mx-auto bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h1 class="text-xl font-semibold text-gray-900">Edit User</h1>
        <p class="mt-1 text-sm text-gray-500">Update user information</p>
    </div>
    
    <div class="p-6">
        <form action="editUser.php?id=<?php echo $userId; ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <!-- Name -->
            <div class="space-y-2">
                <label for="name" class="block text-sm font-semibold text-gray-900">Full Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
            </div>
            
            <!-- Email -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-semibold text-gray-900">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
            </div>
            
            <!-- Password (optional) -->
            <div class="space-y-2">
                <label for="password" class="block text-sm font-semibold text-gray-900">New Password (leave blank to keep current)</label>
                <input type="password" id="password" name="password" class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                <p class="text-xs text-gray-500">Password must be exactly 8 characters, no capital letters, only underscore allowed as special character</p>
            </div>
            
            <!-- Confirm Password -->
            <div class="space-y-2">
                <label for="confirm_password" class="block text-sm font-semibold text-gray-900">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
            </div>
            
            <!-- Room Selection -->
            <div class="space-y-2">
                <label for="room" class="block text-sm font-semibold text-gray-900">Room</label>
                <select id="room" name="room" required class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                    <option value="">Select Room</option>
                    <option value="Application1" <?php echo ($user['room'] === 'Application1') ? 'selected' : ''; ?>>Application 1</option>
                    <option value="Application2" <?php echo ($user['room'] === 'Application2') ? 'selected' : ''; ?>>Application 2</option>
                    <option value="Cloud" <?php echo ($user['room'] === 'Cloud') ? 'selected' : ''; ?>>Cloud</option>
                </select>
            </div>
            
            <!-- Current Profile Image -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-900">Current Profile Image</label>
                <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-100">
                    <img src="uploads/<?php echo htmlspecialchars($user['profile_image'] ?? ''); ?>" alt="Profile" class="w-full h-full object-cover">
                </div>
            </div>
            
            <!-- Profile Image (optional) -->
            <div class="space-y-2">
                <label for="profile_image" class="block text-sm font-semibold text-gray-900">New Profile Image (leave blank to keep current)</label>
                <input type="file" id="profile_image" name="profile_image" accept="image/*" class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
            </div>
            
            <!-- Submit Button -->
            <div class="pt-6 flex justify-between">
                <a href="usersListing.php" class="px-6 py-3 rounded-lg bg-gray-200 text-gray-700 font-medium hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-all duration-200">Cancel</a>
                <button type="submit" class="px-6 py-3 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200">Update User</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>