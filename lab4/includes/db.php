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

// Function to get user by ID
function getUserById($id) {
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to check if a user exists by email (ignoring specific ID)
function userExistsByEmail($email, $ignoreId = null) {
    $conn = dbConnect();
    
    if($ignoreId !== null) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $ignoreId]);
    } else {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
    }
    
    return $stmt->fetchColumn() > 0;
}

// Function to update user information
function updateUser($userData) {
    $conn = dbConnect();
    
    try {
        // Start transaction
        $conn->beginTransaction();
        
        // Update basic user info
        $query = "UPDATE users SET name = ?, email = ?, room = ?";
        $params = [$userData['name'], $userData['email'], $userData['room']];
        
        // Add password if provided
        if(isset($userData['password'])) {
            $query .= ", password = ?";
            $params[] = password_hash($userData['password'], PASSWORD_DEFAULT);
        }
        
        // Add profile image if provided
        if(isset($userData['profile_image'])) {
            $query .= ", profile_image = ?";
            $params[] = $userData['profile_image'];
        }
        
        // Add where clause
        $query .= " WHERE id = ?";
        $params[] = $userData['id'];
        
        // Execute update
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        
        // Commit transaction
        $conn->commit();
        return true;
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollBack();
        error_log("Error updating user: " . $e->getMessage());
        return "Update failed. Please try again later.";
    }
}

// Function to delete a user
function deleteUser($id) {
    $conn = dbConnect();
    
    try {
        // Get user profile image
        $stmt = $conn->prepare("SELECT profile_image FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Delete the user
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        
        // Delete profile image file if it exists
        if($user && isset($user['profile_image'])) {
            $imagePath = UPLOADS_DIR . $user['profile_image'];
            if(file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Error deleting user: " . $e->getMessage());
        return false;
    }
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