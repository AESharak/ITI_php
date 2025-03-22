<form action="./registeration.php" method="POST" class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- First Name -->
        <div class="space-y-2">
            <label for="firstName" class="block text-sm font-semibold text-gray-900">First Name</label>
            <input type="text" id="firstName" name="firstName" required 
                class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-white/50 backdrop-blur-sm"
                placeholder="Enter your first name">
        </div>
        
        <!-- Last Name -->
        <div class="space-y-2">
            <label for="lastName" class="block text-sm font-semibold text-gray-900">Last Name</label>
            <input type="text" id="lastName" name="lastName" required 
                class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-white/50 backdrop-blur-sm"
                placeholder="Enter your last name">
        </div>
    </div>
    
    <!-- Email -->
    <div class="space-y-2">
        <label for="email" class="block text-sm font-semibold text-gray-900">Email Address</label>
        <input type="email" id="email" name="email" required 
            class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-white/50 backdrop-blur-sm"
            placeholder="you@example.com">
    </div>
    
    <!-- Address -->
    <div class="space-y-2">
        <label for="address" class="block text-sm font-semibold text-gray-900">Address</label>
        <textarea id="address" name="address" rows="3" required 
            class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-white/50 backdrop-blur-sm resize-none"
            placeholder="Enter your full address"></textarea>
    </div>
    
    <!-- Country -->
    <?php include 'form_parts/country_select.php'; ?>
    
    <!-- Gender -->
    <?php include 'form_parts/gender_options.php'; ?>
    
    <!-- Skills -->
    <?php include 'form_parts/skills_checkboxes.php'; ?>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Username -->
        <div class="space-y-2">
            <label for="username" class="block text-sm font-semibold text-gray-900">Username</label>
            <input type="text" id="username" name="username" required 
                class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-white/50 backdrop-blur-sm"
                placeholder="Choose a username">
        </div>
        
        <!-- Password -->
        <div class="space-y-2">
            <label for="password" class="block text-sm font-semibold text-gray-900">Password</label>
            <input type="password" id="password" name="password" required 
                class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-white/50 backdrop-blur-sm"
                placeholder="••••••••">
        </div>
    </div>
    
    <!-- Department -->
    <div class="space-y-2">
        <label for="department" class="block text-sm font-semibold text-gray-900">Department</label>
        <input type="text" id="department" name="department" value="<?php echo DEFAULT_DEPARTMENT; ?>" readonly
            class="block w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 cursor-not-allowed">
    </div>
    
    <!-- CAPTCHA -->
    <?php include 'form_parts/captcha.php'; ?>
    
    <!-- Buttons -->
    <div class="flex gap-4 pt-6">
        <button type="submit" 
            class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200">
            Submit Registration
        </button>
        <button type="reset" 
            class="flex-1 bg-white text-gray-700 px-6 py-3 rounded-lg font-medium border border-gray-200 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-2 transition-all duration-200">
            Reset Form
        </button>
    </div>
</form>