<?php
// filepath: /php-mysql-auth/includes/db.php

require_once 'config.php';

function dbConnect() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
        $conn = new PDO($dsn, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        // Log error and show user-friendly message
        error_log("Database Connection Error: " . $e->getMessage());
        die("Sorry, we're experiencing technical difficulties. Please try again later.");
    }
}

// Function to execute a query
function executeQuery($query, $params = []) {
    $conn = dbConnect();
    $stmt = $conn->prepare($query);
    
    if ($params) {
        $stmt->execute($params);
    } else {
        $stmt->execute();
    }
    
    return $stmt;
}

// Function to insert a new user
function insertUser($name, $email, $password, $room, $profile_image) {
    $query = "INSERT INTO users (name, email, password, room, profile_image, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
    $params = [$name, $email, password_hash($password, PASSWORD_DEFAULT), $room, $profile_image];
    return executeQuery($query, $params);
}

// Function to get all users
function getUsers() {
    $query = "SELECT * FROM users";
    return executeQuery($query)->fetchAll(PDO::FETCH_ASSOC);
}

// Function to check if a user exists
function userExists($email) {
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetchColumn() > 0;
}

// Get user by email
function getUserByEmail($email) {
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Add missing helper functions from the JSON version
function redirectWithError($url, $errorMessage) {
    $_SESSION['error'] = $errorMessage;
    header("Location: $url");
    exit;
}

function redirectWithSuccess($url, $successMessage) {
    $_SESSION['success'] = $successMessage;
    header("Location: $url");
    exit;
}
?>