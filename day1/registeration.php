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
        .form-container {
            background-image: linear-gradient(135deg, rgba(249, 250, 251, 0.8) 0%, rgba(255, 255, 255, 0.9) 100%);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-lg w-full">
        <div class="text-center mb-8">
            <svg class="w-12 h-12 mx-auto text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h1 class="mt-4 text-3xl font-bold text-gray-900">Registration Form</h1>
            <p class="mt-2 text-gray-600">
                <?php if ($formSubmitted && $captchaValid && $saveSuccess): ?>
                    Thank you for your registration
                <?php else: ?>
                    Please complete all required fields below
                <?php endif; ?>
            </p>
        </div>

        <div class="form-container bg-white/80 rounded-2xl shadow-xl border border-gray-100 p-8 space-y-6">
            <div class="flex justify-between items-center border-b border-gray-100 pb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">
                        <?php if ($formSubmitted && $captchaValid && $saveSuccess): ?>
                            Submitted Information
                        <?php else: ?>
                            Personal Details
                        <?php endif; ?>
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        <?php if ($formSubmitted && $captchaValid && $saveSuccess): ?>
                            Registration completed successfully
                        <?php else: ?>
                            Fields marked with * are required
                        <?php endif; ?>
                    </p>
                </div>
                <?php if (!($formSubmitted && $captchaValid && $saveSuccess)): ?>
                    <a href="customers.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-indigo-600 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4c-4.42 0-8 3.58-8 8s3.58 8 8 8 8-3.58 8-8-3.58-8-8-8zm1 13h-2v-6h2v6zm0-8h-2V7h2v2z" />
                        </svg>
                        View Records
                    </a>
                <?php endif; ?>
            </div>
            
            <?php include 'templates/form_response.php'; ?>
            
            <?php if (!($formSubmitted && $captchaValid && $saveSuccess)): ?>
                <?php include 'templates/form_fields.php'; ?>
            <?php else: ?>
                <div class="flex space-x-4 pt-6">
                    <a href="registeration.php" 
                       class="flex-1 inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m6 0H6" />
                        </svg>
                        New Registration
                    </a>
                    <a href="customers.php" 
                       class="flex-1 inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        View All Records
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>