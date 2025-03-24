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

// Prevent users from deleting their own account
if($_SESSION['user']['id'] == $userId) {
    redirectWithError('usersListing.php', 'You cannot delete your own account');
}

// Delete user
$result = deleteUser($userId);

if($result === true) {
    redirectWithSuccess('usersListing.php', 'User deleted successfully');
} else {
    redirectWithError('usersListing.php', 'Failed to delete user');
}
?>