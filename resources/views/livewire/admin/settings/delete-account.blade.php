<x-layouts.settings>
    <div class="space-y-6">
        <div>
            <h2 class="text-lg font-semibold text-foreground">Delete Account</h2>
            <p class="mt-1 text-sm text-dim">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
        </div>

        @if(!$showConfirmation)
            <button wire:click="confirmDelete"
                class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-on-primary hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                Delete Account
            </button>
        @else
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 space-y-4 dark:border-red-500/20 dark:bg-red-500/10">
                <p class="text-sm text-red-800 dark:text-red-400">Are you sure you want to delete your account? This action cannot be undone.</p>

                <div>
                    <label for="password" class="block text-sm font-medium text-foreground">Password</label>
                    <input type="password" id="password" wire:model="password" placeholder="Enter your password to confirm"
                        class="mt-1 block w-full rounded-md border border-line bg-card px-3 py-2 text-sm text-foreground placeholder-dim focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" />
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button wire:click="delete"
                        class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-on-primary hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Delete Account
                    </button>
                    <button wire:click="cancelDelete"
                        class="inline-flex items-center rounded-md border border-line bg-card px-4 py-2 text-sm font-medium text-foreground hover:bg-subtle transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        @endif
    </div>
</x-layouts.settings>
