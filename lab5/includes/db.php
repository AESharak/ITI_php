<?php
require_once 'DBClass.php';
require_once 'config.php';

// Database instance
function getDbInstance() {
    static $db = null;
    if ($db === null) {
        $db = new Database();
        $db->connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }
    return $db;
}

// Function to execute a query (maintaining for backward compatibility)
function executeQuery($query, $params = []) {
    // This function can remain for any direct queries not covered by Database class
    $conn = dbConnect();
    $stmt = $conn->prepare($query);
    
    if ($params) {
        $stmt->execute($params);
    } else {
        $stmt->execute();
    }
    
    return $stmt;
}

// Legacy PDO connection (maintain for executeQuery and any other direct PDO usage)
function dbConnect() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
        $conn = new PDO($dsn, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        error_log("Database Connection Error: " . $e->getMessage());
        die("Sorry, we're experiencing technical difficulties. Please try again later.");
    }
}

// Function to insert a new user
function insertUser($name, $email, $password, $room, $profile_image) {
    $db = getDbInstance();
    $data = [
        'name' => $name,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'room' => $room,
        'profile_image' => $profile_image,
        'created_at' => date('Y-m-d H:i:s')
    ];
    return $db->insert('users', $data);
}

// Function to get all users
function getUsers() {
    $db = getDbInstance();
    return $db->select('users');
}

// Function to check if a user exists
function userExists($email) {
    $db = getDbInstance();
    $result = $db->select('users', 'COUNT(*) as count', "email = ?", [$email]);
    return $result[0]['count'] > 0;
}

// Get user by email
function getUserByEmail($email) {
    $db = getDbInstance();
    $result = $db->select('users', '*', "email = ?", [$email]);
    return $result ? $result[0] : false;
}

// Function to get user by ID
function getUserById($id) {
    $db = getDbInstance();
    $result = $db->select('users', '*', "id = ?", [$id]);
    return $result ? $result[0] : false;
}

// Function to check if a user exists by email (ignoring specific ID)
function userExistsByEmail($email, $ignoreId = null) {
    $db = getDbInstance();
    if ($ignoreId !== null) {
        $result = $db->select('users', 'COUNT(*) as count', "email = ? AND id != ?", [$email, $ignoreId]);
    } else {
        $result = $db->select('users', 'COUNT(*) as count', "email = ?", [$email]);
    }
    return $result[0]['count'] > 0;
}

// Function to update user information
function updateUser($userData) {
    $db = getDbInstance();
    
    try {
        $updateData = [
            'name' => $userData['name'],
            'email' => $userData['email'],
            'room' => $userData['room']
        ];
        
        // Add password if provided
        if(isset($userData['password'])) {
            $updateData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        }
        
        // Add profile image if provided
        if(isset($userData['profile_image'])) {
            $updateData['profile_image'] = $userData['profile_image'];
        }
        
        return $db->update('users', $userData['id'], $updateData);
    } catch (Exception $e) {
        error_log("Error updating user: " . $e->getMessage());
        return "Update failed. Please try again later.";
    }
}

// Function to delete a user
function deleteUser($id) {
    try {
        // Get user profile image first
        $db = getDbInstance();
        $result = $db->select('users', 'profile_image', "id = ?", [$id]);
        $profile_image = $result[0]['profile_image'] ?? null;
        
        // Delete the user
        $success = $db->delete('users', $id);
        
        // Delete profile image file if it exists
        if ($success && $profile_image) {
            $imagePath = UPLOADS_DIR . $profile_image;
            if(file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        return $success;
    } catch (Exception $e) {
        error_log("Error deleting user: " . $e->getMessage());
        return false;
    }
}

// Helper functions
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