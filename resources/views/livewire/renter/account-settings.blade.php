<div>
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h2 class="text-xl font-semibold text-foreground font-serif tracking-tight">Account Settings</h2>
            <p class="text-xs text-dim mt-1">Manage your password and security preferences.</p>
        </div>

        {{-- Change Password --}}
        <div class="bg-card border border-line rounded-sm p-6" style="box-shadow: var(--shadow-xs);">
            <h3 class="text-sm font-semibold text-foreground font-serif tracking-tight mb-5 pb-3 border-b border-line">Change Password</h3>
            
            <form wire:submit="changePassword" class="space-y-5">
                <div>
                    <label for="currentPassword" class="block text-xs font-medium text-dim mb-1.5 uppercase tracking-wider">Current Password</label>
                    <input id="currentPassword" type="password" wire:model="currentPassword"
                        class="w-full rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground @error('currentPassword') border-red-500 @enderror">
                    @error('currentPassword') <p class="text-red-600 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="newPassword" class="block text-xs font-medium text-dim mb-1.5 uppercase tracking-wider">New Password</label>
                        <input id="newPassword" type="password" wire:model="newPassword"
                            class="w-full rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground @error('newPassword') border-red-500 @enderror">
                        @error('newPassword') <p class="text-red-600 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="newPasswordConfirmation" class="block text-xs font-medium text-dim mb-1.5 uppercase tracking-wider">Confirm Password</label>
                        <input id="newPasswordConfirmation" type="password" wire:model="newPasswordConfirmation"
                            class="w-full rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground">
                    </div>
                </div>

                <button type="submit" wire:loading.attr="disabled"
                    class="px-5 py-2 bg-foreground text-on-primary rounded-sm hover:opacity-90 font-medium text-sm transition-all disabled:opacity-50">
                    <span wire:loading.remove>Update Password</span>
                    <span wire:loading>Updating...</span>
                </button>
            </form>
        </div>

        {{-- Delete Account --}}
        <div class="bg-card border border-red-200 dark:border-red-500/20 rounded-sm p-6" style="box-shadow: var(--shadow-xs);">
            <h3 class="text-sm font-semibold text-red-700 dark:text-red-400 font-serif tracking-tight mb-1">Delete Account</h3>
            <p class="text-xs text-red-600/70 dark:text-red-400/60 mb-5">
                Permanently delete your account and all associated data. This action cannot be undone.
            </p>

            <form wire:submit="deleteAccount" class="space-y-4">
                <div>
                    <label for="toDeletePassword" class="block text-xs font-medium text-dim mb-1.5 uppercase tracking-wider">Confirm Password</label>
                    <input id="toDeletePassword" type="password" wire:model="toDeletePassword"
                        class="w-full rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground @error('toDeletePassword') border-red-500 @enderror"
                        placeholder="Enter your password to confirm deletion">
                    @error('toDeletePassword') <p class="text-red-600 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" wire:loading.attr="disabled"
                    onclick="return confirm('Are you absolutely sure? This will permanently delete your account and all your data.')"
                    class="px-5 py-2 bg-red-500 text-white rounded-sm hover:bg-red-600 font-medium text-sm transition-colors disabled:opacity-50">
                    <span wire:loading.remove>Delete My Account</span>
                    <span wire:loading>Deleting...</span>
                </button>
            </form>
        </div>

        {{-- Security Tips --}}
        <div class="bg-card border border-line rounded-sm p-5" style="box-shadow: var(--shadow-xs);">
            <h3 class="text-sm font-semibold text-foreground font-serif tracking-tight mb-4">Security Tips</h3>
            <ul class="space-y-3">
                <li class="flex items-start gap-2.5 text-xs text-dim">
                    <svg class="w-3 h-3 text-accent flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                    </svg>
                    <span>Use a strong, unique password that includes uppercase letters, lowercase letters, numbers, and symbols.</span>
                </li>
                <li class="flex items-start gap-2.5 text-xs text-dim">
                    <svg class="w-3 h-3 text-accent flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                    </svg>
                    <span>Never share your password with anyone, even support staff.</span>
                </li>
                <li class="flex items-start gap-2.5 text-xs text-dim">
                    <svg class="w-3 h-3 text-accent flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                    </svg>
                    <span>Change your password regularly and avoid reusing old passwords.</span>
                </li>
                <li class="flex items-start gap-2.5 text-xs text-dim">
                    <svg class="w-3 h-3 text-accent flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                    </svg>
                    <span>Log out from all sessions and update your password if you suspect unauthorized access.</span>
                </li>
            </ul>
        </div>
    </div>
</div>
