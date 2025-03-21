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
 * @param string $verification The correct CAPTCHA code
 * @return bool Whether the CAPTCHA is valid
 */
function validateCaptcha($userInput, $verification) {
    return isset($userInput) && isset($verification) && $userInput === $verification;
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
    if (!validateCaptcha($postData['captcha'] ?? '', $postData['captcha_verification'] ?? '')) {
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
    $file = 'customer.txt';
    $allCustomers = getCustomersFromFile();
    
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
    
    // Add to array and save to file
    $allCustomers[] = $newCustomer;
    
    // Convert to JSON and save
    return file_put_contents($file, json_encode($allCustomers));
}

/**
 * Gets all customers from file
 * @return array Array of customers
 */
function getCustomersFromFile() {
    $file = 'customer.txt';
    
    // If file doesn't exist, create it with empty array
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([]));
    }
    
    // Read file contents
    $contents = file_get_contents($file);
    
    // If empty, return empty array
    if (empty($contents)) {
        return [];
    }
    
    // Decode and return
    return json_decode($contents, true) ?: [];
}

/**
 * Deletes customer by ID
 * @param int $id Customer ID to delete
 * @return bool Success status
 */
function deleteCustomer($id) {
    $file = 'customer.txt';
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