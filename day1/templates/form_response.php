<?php
if ($formSubmitted): 
    if (!$captchaValid): ?>
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
    <?php elseif (!empty($validationErrors)): ?>
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <!-- Error icon -->
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">Please fix the following errors:</p>
                    <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                        <?php foreach ($validationErrors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php elseif ($saveSuccess): ?>
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <!-- Success icon -->
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">Form submitted successfully!</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-md border mb-6">
            <h2 class="text-lg font-medium mb-4">Submitted Information</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Name</p>
                    <p class="text-sm"><?php echo htmlspecialchars($formData['firstName'] . ' ' . $formData['lastName']); ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Email</p>
                    <p class="text-sm"><?php echo htmlspecialchars($formData['email']); ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Username</p>
                    <p class="text-sm"><?php echo htmlspecialchars($formData['username']); ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Address</p>
                    <p class="text-sm"><?php echo htmlspecialchars($formData['address']); ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Country</p>
                    <p class="text-sm"><?php echo htmlspecialchars($formData['country']); ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Gender</p>
                    <p class="text-sm"><?php echo htmlspecialchars($formData['gender']); ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Department</p>
                    <p class="text-sm"><?php echo htmlspecialchars($formData['department']); ?></p>
                </div>
                <div class="col-span-2">
                    <p class="text-sm font-medium text-gray-500">Skills</p>
                    <p class="text-sm">
                        <?php 
                        if (!empty($formData['skills'])) {
                            echo htmlspecialchars(implode(', ', $formData['skills']));
                        } else {
                            echo "No skills selected";
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>