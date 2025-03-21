<?php
require_once 'functions.php';

// Initialize variables
$formSubmitted = false;
$formData = [];
$captchaValid = true;
$captchaCode = generateCaptchaCode();
$validationErrors = [];
$saveSuccess = false;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = processForm($_POST);
    $formSubmitted = $result['formSubmitted'];
    $captchaValid = $result['captchaValid'];
    $formData = $result['formData'];
    $validationErrors = $result['validationErrors'];
    $saveSuccess = $result['saveSuccess'];
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