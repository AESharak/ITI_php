<?php
require_once 'process.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form - Lab 01</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .captcha-box {
            letter-spacing: 0.25em;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-6 space-y-6">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-800">Registration Form</h1>
            <p class="text-gray-500">
                <?php if ($formSubmitted && $captchaValid): ?>
                    Submitted Information
                <?php else: ?>
                    Please fill out all the required fields
                <?php endif; ?>
            </p>
        </div>
        
        <?php if ($formSubmitted && $captchaValid): ?>
            <!-- Show only the form response when successfully submitted -->
            <?php include 'templates/form_response.php'; ?>
            
            <!-- Add a button to start over -->
            <div class="text-center mt-6">
                <a href="registeration.php" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    New Registration
                </a>
            </div>
        <?php else: ?>
            <!-- Show error message if CAPTCHA is invalid -->
            <?php if ($formSubmitted && !$captchaValid): ?>
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <!-- Error icon -->
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">CAPTCHA verification failed. Please try again.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Show the form fields -->
            <?php include 'templates/form_fields.php'; ?>
        <?php endif; ?>
    </div>
</body>
</html>