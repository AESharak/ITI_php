<?php
// filepath: /var/www/html/php/day1/templates/form_parts/captcha.php
?>
<div>
    <label for="captcha" class="block text-sm font-medium text-gray-700">CAPTCHA</label>
    <div class="flex flex-col space-y-2">
        <div class="bg-gray-200 p-3 rounded-md text-center captcha-box">
            <!-- Using PHP-generated CAPTCHA code -->
            <span class="font-bold tracking-widest text-gray-700"><?php echo $captchaCode; ?></span>
            <!-- Store the CAPTCHA in a hidden field to verify on submission -->
            <input type="hidden" name="captcha_verification" value="<?php echo $captchaCode; ?>">
        </div>
        <input type="text" id="captcha" name="captcha" required
            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="Please insert the code above">
        <p class="text-xs text-gray-500">Please insert the code below the box.</p>
    </div>
</div>