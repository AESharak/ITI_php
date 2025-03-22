<div class="space-y-3">
    <label class="block text-sm font-semibold text-gray-900">Gender</label>
    <div class="grid grid-cols-2 gap-4">
        <div class="relative">
            <input type="radio" id="male" name="gender" value="male" required
                class="peer absolute opacity-0 w-full h-full cursor-pointer">
            <label for="male" 
                class="flex items-center justify-center p-4 rounded-lg border-2 border-gray-200 cursor-pointer transition-all duration-200
                peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:bg-gray-50">
                <svg class="w-6 h-6 mr-2 text-gray-500 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="font-medium text-gray-900">Male</span>
            </label>
        </div>
        <div class="relative">
            <input type="radio" id="female" name="gender" value="female" required
                class="peer absolute opacity-0 w-full h-full cursor-pointer">
            <label for="female"
                class="flex items-center justify-center p-4 rounded-lg border-2 border-gray-200 cursor-pointer transition-all duration-200
                peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:bg-gray-50">
                <svg class="w-6 h-6 mr-2 text-gray-500 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="font-medium text-gray-900">Female</span>
            </label>
        </div>
    </div>
</div>