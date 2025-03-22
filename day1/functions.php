<?php
require_once 'config.php';

/**
 * Generates a random CAPTCHA code
 * @return string The generated CAPTCHA code
 */
function generateCaptchaCode() {
    return strtoupper(substr(md5(time()), 0, 6));
}

/**
 * Validates the CAPTCHA input
 * @param string $userInput User's CAPTCHA input
 * @return bool Whether the CAPTCHA is valid
 */
function validateCaptcha($userInput) {
    return isset($userInput) && !empty($userInput) && 
           isset($_SESSION['captcha_code']) && 
           strtoupper($userInput) === $_SESSION['captcha_code'];
}

/**
 * Validates form fields
 * @param array $data Form data
 * @return array Array of validation errors (empty if valid)
 */
function validateFormFields($data) {
    $errors = [];
    
    // Validate first name
    if (empty($data['firstName'])) {
        $errors['firstName'] = "First name is required";
    } elseif (!preg_match("/^[a-zA-Z ]{2,30}$/", $data['firstName'])) {
        $errors['firstName'] = "First name must contain only letters and be 2-30 characters";
    }
    
    // Validate last name
    if (empty($data['lastName'])) {
        $errors['lastName'] = "Last name is required";
    } elseif (!preg_match("/^[a-zA-Z ]{2,30}$/", $data['lastName'])) {
        $errors['lastName'] = "Last name must contain only letters and be 2-30 characters";
    }
    
    // Validate email
    if (empty($data['email'])) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }
    
    // Validate gender
    if (empty($data['gender'])) {
        $errors['gender'] = "Gender is required";
    } elseif (!in_array($data['gender'], ['male', 'female'])) {
        $errors['gender'] = "Gender must be either male or female";
    }
    
    return $errors;
}

/**
 * Process form submission
 * @param array $postData The $_POST data
 * @return array Contains form data, validation status, and errors
 */
function processForm($postData) {
    $result = [
        'formSubmitted' => true,
        'captchaValid' => true,
        'formData' => [],
        'validationErrors' => [],
        'saveSuccess' => false,
    ];
    
    // Validate CAPTCHA
    if (!validateCaptcha($postData['captcha'] ?? '')) {
        $result['captchaValid'] = false;
    }
    
    // Store form data
    $result['formData'] = [
        'firstName' => $postData['firstName'] ?? '',
        'lastName' => $postData['lastName'] ?? '',
        'email' => $postData['email'] ?? '',
        'address' => $postData['address'] ?? '',
        'country' => $postData['country'] ?? '',
        'gender' => $postData['gender'] ?? '',
        'skills' => $postData['skills'] ?? [],
        'username' => $postData['username'] ?? '',
        'department' => $postData['department'] ?? DEFAULT_DEPARTMENT
    ];
    
    // Validate form fields
    $result['validationErrors'] = validateFormFields($result['formData']);
    
    // If CAPTCHA is valid and no validation errors, save to file
    if ($result['captchaValid'] && empty($result['validationErrors'])) {
        $result['saveSuccess'] = saveCustomerToFile($result['formData']);
    }
    
    return $result;
}

/**
 * Saves customer data to file
 * @param array $data Customer data to save
 * @return bool Success status
 */
function saveCustomerToFile($data) {
    $file = __DIR__ . '/customers.json';
    
    // Debug - Check if file exists and permissions
    if (!file_exists($file)) {
        error_log("Creating new customer file at: $file");
    } else {
        error_log("Customer file exists at: $file");
        error_log("File is " . (is_writable($file) ? "writable" : "not writable"));
    }
    
    // Check if file exists and is writable
    if (file_exists($file) && !is_writable($file)) {
        error_log("Customer file exists but is not writable: $file");
        return false;
    }
    
    // Check if directory is writable if file doesn't exist
    if (!file_exists($file) && !is_writable(dirname($file))) {
        error_log("Directory is not writable for creating customer file: " . dirname($file));
        return false;
    }
    
    // Get existing customers
    $allCustomers = getCustomersFromFile();
    error_log("Current customer count: " . count($allCustomers));
    
    // Generate new ID
    $id = 1;
    if (!empty($allCustomers)) {
        // Get the highest ID and add 1
        $ids = array_column($allCustomers, 'id');
        $id = max($ids) + 1;
    }
    
    // Format the data to save
    $newCustomer = [
        'id' => $id,
        'firstName' => $data['firstName'],
        'lastName' => $data['lastName'],
        'email' => $data['email'],
        'gender' => $data['gender']
    ];
    
    // Add to array
    $allCustomers[] = $newCustomer;
    error_log("New customer added with ID: $id");
    
    // Convert to JSON with pretty print for readability
    $jsonData = json_encode($allCustomers, JSON_PRETTY_PRINT);
    if ($jsonData === false) {
        error_log("Failed to encode customer data to JSON: " . json_last_error_msg());
        return false;
    }
    
    // Try to save to file with explicit error handling
    try {
        $result = file_put_contents($file, $jsonData);
        if ($result === false) {
            error_log("Failed to write customer data to file: $file");
            return false;
        }
        error_log("Successfully wrote " . $result . " bytes to customer file");
        return true;
    } catch (Exception $e) {
        error_log("Exception when writing to file: " . $e->getMessage());
        return false;
    }
}

/**
 * Gets all customers from file
 * @return array Array of customers
 */
function getCustomersFromFile() {
    $file = __DIR__ . '/customers.json';
    
    // If file doesn't exist, create it with empty array
    if (!file_exists($file)) {
        $result = file_put_contents($file, json_encode([], JSON_PRETTY_PRINT));
        if ($result === false) {
            error_log("Failed to create new customers file: $file");
            return [];
        }
    }
    
    // Check if file is readable
    if (!is_readable($file)) {
        error_log("Customers file is not readable: $file");
        return [];
    }
    
    // Read file contents
    $contents = file_get_contents($file);
    if ($contents === false) {
        error_log("Failed to read customers file: $file");
        return [];
    }
    
    // If empty, return empty array
    if (empty($contents)) {
        return [];
    }
    
    // Decode and return
    $customers = json_decode($contents, true);
    if ($customers === null && json_last_error() !== JSON_ERROR_NONE) {
        error_log("Failed to decode customer data from JSON: " . json_last_error_msg());
        return [];
    }
    
    return $customers ?: [];
}

/**
 * Deletes customer by ID
 * @param int $id Customer ID to delete
 * @return bool Success status
 */
function deleteCustomer($id) {
    $file = __DIR__ . '/customers.json';
    $customers = getCustomersFromFile();
    
    // Find and remove the customer
    foreach ($customers as $key => $customer) {
        if ($customer['id'] == $id) {
            unset($customers[$key]);
            break;
        }
    }
    
    // Reindex array
    $customers = array_values($customers);
    
    // Save back to file
    return file_put_contents($file, json_encode($customers));
}