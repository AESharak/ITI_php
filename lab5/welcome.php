<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

requireAuth();

include 'includes/header.php';

$userEmail = $_SESSION['user']['email'];
$user = getUserByEmail($userEmail);

$createdDate = isset($user['created_at']) ? 
    date('F j, Y', strtotime($user['created_at'])) : 
    'Unknown';

$lastLogin = date('F j, Y, g:i a', strtotime('-2 days'));
$activityStats = [
    'logins' => rand(3, 15),
    'days_active' => rand(5, 30),
    'status' => 'Active'
];
?>



<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="w-full px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">Welcome, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>!</h1>
            <p class="mt-1 text-sm text-gray-500">You have successfully logged in</p>
        </div>
        <div class="space-x-2">
            <a href="usersListing.php" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                Manage Users
            </a>
            <a href="logout.php" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200">
                Logout
            </a>
        </div>
    </div>
    
    <div class="p-6">
        <!-- User profile section -->
        <div class="flex flex-col lg:flex-row items-center lg:items-start space-y-4 lg:space-y-0 lg:space-x-8">
            <!-- Profile image with enhanced styling -->
            <div class="w-36 h-36 rounded-full overflow-hidden border-4 border-indigo-100 shadow-lg">
                <img src="uploads/<?php echo htmlspecialchars($_SESSION['user']['profile_image'] ?? ''); ?>" alt="Profile" class="w-full h-full object-cover">
            </div>
            
            <!-- User details with icons -->
            <div class="flex-1">
                <h2 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">User Information</h2>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Full Name
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 font-medium"><?php echo htmlspecialchars($_SESSION['user']['name']); ?></dd>
                    </div>
                    
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Email
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($_SESSION['user']['email']); ?></dd>
                    </div>
                    
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Room
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($_SESSION['user']['room']); ?></dd>
                    </div>
                    
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Member Since
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900"><?php echo $createdDate; ?></dd>
                    </div>
                    
                    <?php if (isset($_SESSION['user']['ext'])): ?>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            Extension
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($_SESSION['user']['ext']); ?></dd>
                    </div>
                    <?php endif; ?>
                </dl>
            </div>
        </div>
        
        <!-- Activity statistics section -->
        <div class="mt-8 pt-8 border-t border-gray-200">
              <h2 class="text-lg font-medium text-gray-900 mb-4">Account Activity</h2>
                
              <div class=" flex justify-between p-3">

                  <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-lg p-6 shadow-sm border border-indigo-100">
                      <div class="flex items-center">
                          <div class="bg-indigo-500 rounded-md p-2 mr-4">
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Last Login</p>
                                <p class="text-xl font-semibold text-gray-900"><?php echo $lastLogin; ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-lg p-6 shadow-sm border border-indigo-100">
                        <div class="flex items-center">
                            <div class="bg-indigo-500 rounded-md p-2 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <div class="flex items-center">
                                    <span class="px-2 py-1 mt-1 bg-green-100 text-green-800 rounded-full text-xs font-medium"><?php echo $activityStats['status']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

            </div>
        </div>
        
        <!-- Current session information -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6 border border-gray-200">
            <h3 class="text-md font-medium text-gray-700 mb-2">Current Session</h3>
            <div class="flex items-center space-x-2 text-sm">
                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Active</span>
                <span class="text-gray-500">Started at <?php echo date('Y-m-d H:i:s'); ?></span>
                <span class="text-gray-400">•</span>
                <span class="text-gray-500">IP: <?php echo $_SERVER['REMOTE_ADDR']; ?></span>
            </div>
        </div>
    </div>
</div>


</body>
</html>