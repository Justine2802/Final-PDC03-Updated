<div>
    <div class="space-y-8">
        {{-- Header --}}
        <div>
            <h2 class="text-3xl font-bold text-foreground">Account Settings</h2>
        </div>

        {{-- Change Password Section --}}
        <div class="bg-card border border-line rounded-lg p-10">
            <h3 class="text-2xl font-bold text-foreground mb-8">Change Password</h3>
            
            <form wire:submit="changePassword" class="space-y-8">
                {{-- Current Password --}}
                <div>
                    <label for="currentPassword" class="block text-base font-bold text-foreground mb-3">Current Password</label>
                    <input id="currentPassword" type="password" wire:model="currentPassword"
                        class="w-full rounded-md border border-line bg-card px-5 py-3 text-base text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary @error('currentPassword') border-red-500 @enderror">
                    @error('currentPassword')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- New Password --}}
                <div>
                    <label for="newPassword" class="block text-base font-bold text-foreground mb-3">New Password</label>
                    <input id="newPassword" type="password" wire:model="newPassword"
                        class="w-full rounded-md border border-line bg-card px-5 py-3 text-base text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary @error('newPassword') border-red-500 @enderror">
                    @error('newPassword')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm New Password --}}
                <div>
                    <label for="newPasswordConfirmation" class="block text-base font-bold text-foreground mb-3">Confirm New Password</label>
                    <input id="newPasswordConfirmation" type="password" wire:model="newPasswordConfirmation"
                        class="w-full rounded-md border border-line bg-card px-5 py-3 text-base text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                </div>

                {{-- Submit Button --}}
                <button type="submit" wire:loading.attr="disabled"
                    class="px-8 py-3 bg-primary text-on-primary rounded-md hover:bg-primary/90 font-bold text-base transition-colors disabled:opacity-50">
                    <span wire:loading.remove>Update Password</span>
                    <span wire:loading>Updating...</span>
                </button>
            </form>
        </div>

        {{-- Delete Account Section --}}
        <div class="bg-red-50 border border-red-200 rounded-lg p-8">
            <h3 class="text-lg font-semibold text-red-900 mb-3">Delete Account</h3>
            <p class="text-sm text-red-700 mb-6">
                Permanently delete your account and all associated data. This action cannot be undone.
            </p>

            <form wire:submit="deleteAccount" class="space-y-6">
                {{-- Password Confirmation --}}
                <div>
                    <label for="toDeletePassword" class="block text-sm font-semibold text-foreground mb-3">Confirm Password</label>
                    <input id="toDeletePassword" type="password" wire:model="toDeletePassword"
                        class="w-full rounded-md border border-line bg-card px-4 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary @error('toDeletePassword') border-red-500 @enderror"
                        placeholder="Enter your password to confirm deletion">
                    @error('toDeletePassword')
                        <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Warning --}}
                <div class="bg-red-100 border border-red-300 rounded-md p-4">
                    <p class="text-sm text-red-900 font-semibold">⚠️ Warning: This action is permanent and cannot be reversed.</p>
                </div>

                {{-- Delete Button --}}
                <button type="submit" wire:loading.attr="disabled"
                    onclick="return confirm('Are you absolutely sure? This will permanently delete your account and all your data.')"
                    class="px-6 py-2.5 bg-red-600 text-white rounded-md hover:bg-red-700 font-semibold text-sm transition-colors disabled:opacity-50">
                    <span wire:loading.remove>Delete My Account</span>
                    <span wire:loading>Deleting...</span>
                </button>
            </form>
        </div>

        {{-- Security Tips --}}
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-8">
            <h3 class="text-lg font-semibold text-blue-900 mb-6">Security Tips</h3>
            <ul class="space-y-4 text-sm text-blue-800">
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path>
                    </svg>
                    <span>Use a strong, unique password that includes uppercase letters, lowercase letters, numbers, and symbols.</span>
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path>
                    </svg>
                    <span>Never share your password with anyone, even support staff.</span>
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path>
                    </svg>
                    <span>Change your password regularly and avoid reusing old passwords.</span>
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path>
                    </svg>
                    <span>Log out from all sessions and update your password if you suspect unauthorized access.</span>
                </li>
            </ul>
        </div>
    </div>
</div>
