<div class="space-y-3">
    <label for="captcha" class="block text-sm font-semibold text-gray-900">Security Verification</label>
    <div class="space-y-3">
        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 p-6 rounded-lg text-center captcha-box relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-slate-100 [mask-image:linear-gradient(0deg,#fff,rgba(255,255,255,0.6))]"></div>
            <span class="relative font-mono text-2xl tracking-[0.5em] text-gray-800 select-none"><?php echo $_SESSION['captcha_code']; ?></span>
        </div>
        <div class="relative">
            <input type="text" id="captcha" name="captcha" required
                class="block w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-white/50 backdrop-blur-sm"
                placeholder="Enter the code shown above">
            <p class="mt-2 text-xs text-gray-500">Please enter the characters exactly as shown in the image above</p>
        </div>
    </div>
</div>