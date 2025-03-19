<?php
// filepath: /var/www/html/php/day1/functions.php
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
 * Process form submission
 * @param array $postData The $_POST data
 * @return array Contains form data and validation status
 */
function processForm($postData) {
    $result = [
        'formSubmitted' => true,
        'captchaValid' => true,
        'formData' => []
    ];
    
    // Validate CAPTCHA
    if (!validateCaptcha($postData['captcha'] ?? '', $postData['captcha_verification'] ?? '')) {
        $result['captchaValid'] = false;
    }
    
    // Store form data
    $result['formData'] = [
        'firstName' => $postData['firstName'] ?? '',
        'lastName' => $postData['lastName'] ?? '',
        'address' => $postData['address'] ?? '',
        'country' => $postData['country'] ?? '',
        'gender' => $postData['gender'] ?? '',
        'skills' => $postData['skills'] ?? [],
        'username' => $postData['username'] ?? '',
        'department' => $postData['department'] ?? DEFAULT_DEPARTMENT
    ];
    
    return $result;
}