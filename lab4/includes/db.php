<?php
// filepath: /php-mysql-auth/includes/db.php

$host = 'localhost'; // Database host
$dbname = 'php_lab4'; // Database name
$username = 'aesharak'; // Database username
$password = 'aeey'; // Database password

// Create a connection to the database
function connect() {
    global $host, $dbname, $username, $password;
    $conn = new mysqli($host, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to execute a query
function executeQuery($query, $params = []) {
    $conn = connect();
    $stmt = $conn->prepare($query);
    
    if ($params) {
        $stmt->bind_param(...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    
    return $result;
}

// Function to insert a new user
function insertUser($name, $email, $password, $room, $profile_image) {
    $query = "INSERT INTO users (name, email, password, room, profile_image, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
    $params = ['sssss', $name, $email, password_hash($password, PASSWORD_DEFAULT), $room, $profile_image];
    return executeQuery($query, $params);
}

// Function to get all users
function getUsers() {
    $query = "SELECT * FROM users";
    return executeQuery($query)->fetch_all(MYSQLI_ASSOC);
}

// Function to check if a user exists
function userExists($email) {
    $query = "SELECT * FROM users WHERE email = ?";
    $params = ['s', $email];
    $result = executeQuery($query, $params);
    return $result->num_rows > 0;
}
?>