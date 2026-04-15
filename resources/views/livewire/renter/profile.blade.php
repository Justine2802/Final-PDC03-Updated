<div>
    <div class="space-y-8">
        {{-- Header --}}
        <div>
            <h2 class="text-3xl font-bold text-foreground">My Profile</h2>
        </div>

        {{-- Profile Form --}}
        <form wire:submit="updateProfile" class="bg-card border border-line rounded-lg p-10 space-y-8">
            {{-- Avatar --}}
            <div class="flex items-center gap-8">
                <div class="w-28 h-28 bg-line rounded-full flex items-center justify-center overflow-hidden flex-shrink-0">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <svg class="w-14 h-14 text-dim" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                        </svg>
                    @endif
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-foreground">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-dim mt-1">{{ auth()->user()->email }}</p>
                    <label class="inline-block mt-4 px-4 py-2 bg-primary text-on-primary rounded-md hover:bg-primary/90 font-medium text-sm cursor-pointer transition-colors">
                        Change Avatar
                        <input type="file" wire:model="avatar" accept="image/*" class="hidden">
                    </label>
                    @error('avatar')
                        <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <hr class="border-line">

            {{-- Form Fields --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-foreground mb-3">Full Name</label>
                    <input id="name" type="text" wire:model="name"
                        class="w-full rounded-md border border-line bg-card px-4 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-foreground mb-3">Email</label>
                    <input id="email" type="email" wire:model="email"
                        class="w-full rounded-md border border-line bg-card px-4 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-sm font-semibold text-foreground mb-3">Phone Number</label>
                    <input id="phone" type="tel" wire:model="phone"
                        class="w-full rounded-md border border-line bg-card px-4 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Bio --}}
            <div>
                <label for="bio" class="block text-sm font-semibold text-foreground mb-3">Bio</label>
                <textarea id="bio" wire:model="bio" rows="5" placeholder="Tell us about yourself..."
                    class="w-full rounded-md border border-line bg-card px-4 py-2.5 text-sm text-foreground placeholder-dim focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary @error('bio') border-red-500 @enderror"></textarea>
                @error('bio')
                    <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <div class="flex gap-4 pt-4">
                <button type="submit" wire:loading.attr="disabled"
                    class="px-6 py-2.5 bg-primary text-on-primary rounded-md hover:bg-primary/90 font-semibold text-sm transition-colors disabled:opacity-50">
                    <span wire:loading.remove>Save Changes</span>
                    <span wire:loading class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...
                    </span>
                </button>
                <a href="{{ route('renter.home') }}" class="px-6 py-2.5 border border-line rounded-md text-foreground hover:bg-line font-semibold text-sm transition-colors">
                    Cancel
                </a>
            </div>
        </form>

        {{-- Account Status --}}
        <div class="bg-card border border-line rounded-lg p-6">
            <h3 class="text-lg font-semibold text-foreground mb-6">Account Status</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-dim font-medium">Account Status</span>
                    <span class="px-3 py-1.5 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                        {{ ucfirst(auth()->user()->status) }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-dim font-medium">Member Since</span>
                    <span class="text-foreground font-medium">{{ auth()->user()->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-dim font-medium">Last Updated</span>
                    <span class="text-foreground font-medium">{{ auth()->user()->updated_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
