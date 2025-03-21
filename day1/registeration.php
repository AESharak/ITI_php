<?php
require_once 'process.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form - Lab 02</title>
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
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Registration Form</h1>
                <p class="text-gray-500">
                    <?php if ($formSubmitted && $captchaValid && $saveSuccess): ?>
                        Submitted Information
                    <?php else: ?>
                        Please fill out all the required fields
                    <?php endif; ?>
                </p>
            </div>
            <a href="customers.php" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                View Customers
            </a>
        </div>
        
        <?php include 'templates/form_response.php'; ?>
        
        <?php if (!($formSubmitted && $captchaValid && $saveSuccess)): ?>
            <!-- Show the form fields -->
            <?php include 'templates/form_fields.php'; ?>
        <?php else: ?>
            <!-- Add buttons to add more customers or view all -->
            <div class="flex space-x-4 pt-4">
                <a href="registeration.php" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    New Registration
                </a>
                <a href="customers.php" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    View All Customers
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>