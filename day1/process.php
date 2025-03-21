<?php
require_once 'functions.php';

// Initialize variables
$formSubmitted = false;
$formData = [];
$captchaValid = true;
$captchaCode = generateCaptchaCode();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = processForm($_POST);
    $formSubmitted = $result['formSubmitted'];
    $captchaValid = $result['captchaValid'];
    $formData = $result['formData'];
}