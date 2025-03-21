<?php
require_once 'functions.php';

// Initialize variables
$formSubmitted = false;
$formData = [];
$captchaValid = true;
$validationErrors = [];
$saveSuccess = false;
$saveError = '';

// Generate CAPTCHA code if not already in session
if (!isset($_SESSION['captcha_code'])) {
    $_SESSION['captcha_code'] = generateCaptchaCode();
}
$captchaCode = $_SESSION['captcha_code'];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("Form submitted via POST");
    $result = processForm($_POST);
    $formSubmitted = $result['formSubmitted'];
    $captchaValid = $result['captchaValid'];
    $formData = $result['formData'];
    $validationErrors = $result['validationErrors'];
    $saveSuccess = $result['saveSuccess'];
    
    if ($formSubmitted && $captchaValid && empty($validationErrors) && !$saveSuccess) {
        $saveError = "Failed to save customer data. Please check server logs.";
        error_log("Form validation passed but save failed");
    }
    
    // Generate new CAPTCHA code for next attempt
    $_SESSION['captcha_code'] = generateCaptchaCode();
    $captchaCode = $_SESSION['captcha_code'];
}

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $deleteSuccess = deleteCustomer($_GET['id']);
    // Redirect to avoid refresh issues
    header("Location: customers.php?deleted=" . ($deleteSuccess ? "1" : "0"));
    exit;
}

// Get all customers for display
$customers = getCustomersFromFile();