<form action="./registeration.php" method="POST" class="space-y-4">
    <!-- First Name -->
    <div>
        <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
        <input type="text" id="firstName" name="firstName" required 
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
    </div>
    
    <!-- Last Name -->
    <div>
        <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name</label>
        <input type="text" id="lastName" name="lastName" required 
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
    </div>
    
    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" id="email" name="email" required 
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
    </div>
    
    <!-- Address -->
    <div>
        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
        <textarea id="address" name="address" rows="2" required 
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
    </div>
    
    <!-- Country -->
    <?php include 'form_parts/country_select.php'; ?>
    
    <!-- Gender -->
    <?php include 'form_parts/gender_options.php'; ?>
    
    <!-- Skills -->
    <?php include 'form_parts/skills_checkboxes.php'; ?>
    
    <!-- Username -->
    <div>
        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
        <input type="text" id="username" name="username" required 
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
    </div>
    
    <!-- Password -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" id="password" name="password" required 
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
    </div>
    
    <!-- Department -->
    <div>
        <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
        <input type="text" id="department" name="department" value="<?php echo DEFAULT_DEPARTMENT; ?>" readonly
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
    </div>
    
    <!-- CAPTCHA -->
    <?php include 'form_parts/captcha.php'; ?>
    
    <!-- Buttons -->
    <div class="flex space-x-4 pt-4">
        <button type="submit" 
            class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Submit
        </button>
        <button type="reset" 
            class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Reset
        </button>
    </div>
</form>