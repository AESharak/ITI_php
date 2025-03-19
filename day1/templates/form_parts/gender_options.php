<?php
// filepath: /var/www/html/php/day1/templates/form_parts/gender_options.php
?>
<div>
    <label class="block text-sm font-medium text-gray-700">Gender</label>
    <div class="mt-1 flex space-x-6">
        <div class="flex items-center">
            <input type="radio" id="male" name="gender" value="male" required
                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
            <label for="male" class="ml-2 block text-sm text-gray-700">Male</label>
        </div>
        <div class="flex items-center">
            <input type="radio" id="female" name="gender" value="female" required
                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
            <label for="female" class="ml-2 block text-sm text-gray-700">Female</label>
        </div>
    </div>
</div>