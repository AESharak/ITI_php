<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

requireAuth();

if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirectWithError('usersListing.php', 'Invalid user ID');
}

$userId = $_GET['id'];
$user = getUserById($userId);

if(!$user) {
    redirectWithError('usersListing.php', 'User not found');
}

if($_SESSION['user']['id'] == $userId) {
    redirectWithError('usersListing.php', 'You cannot delete your own account');
}

$result = deleteUser($userId);

if($result === true) {
    redirectWithSuccess('usersListing.php', 'User deleted successfully');
} else {
    redirectWithError('usersListing.php', 'Failed to delete user');
}
?>