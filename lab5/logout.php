<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/db.php';

// Log out the user
logoutUser();

// Redirect to login page
redirectWithSuccess('login.php', 'You have been successfully logged out');
?>