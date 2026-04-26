<div>
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h2 class="text-xl font-semibold text-foreground font-serif tracking-tight">My Profile</h2>
            <p class="text-xs text-dim mt-1">Manage your personal information and preferences.</p>
        </div>

        {{-- Profile Form --}}
        <form wire:submit="updateProfile" class="bg-card border border-line rounded-sm p-6 space-y-6" style="box-shadow: var(--shadow-xs);">
            {{-- Avatar --}}
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 bg-subtle rounded-sm flex items-center justify-center overflow-hidden flex-shrink-0 border border-line">
                    @if($avatar)
                        <img src="{{ $avatar->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover">
                    @elseif(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <svg class="w-10 h-10 text-dim/30" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                        </svg>
                    @endif
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-foreground font-serif">{{ auth()->user()->name }}</h3>
                    <p class="text-xs text-dim mt-0.5">{{ auth()->user()->email }}</p>

                    <div class="flex items-center gap-2 mt-3">
                        <label class="inline-block px-3 py-1.5 bg-foreground text-on-primary rounded-sm hover:opacity-90 font-medium text-xs cursor-pointer transition-all">
                            {{ $avatar ? 'Change' : 'Upload Avatar' }}
                            <input type="file" wire:model="avatar" accept="image/*" class="hidden">
                        </label>

                        @if(auth()->user()->avatar && !$avatar)
                            <button type="button" wire:click="removeAvatar"
                                    class="text-[10px] text-red-500 hover:text-red-700 border border-red-200 hover:border-red-400 px-2.5 py-1.5 rounded-sm transition-colors font-medium">
                                Remove
                            </button>
                        @endif

                        @if($avatar)
                            <button type="button" wire:click="$set('avatar', null)"
                                    class="text-[10px] text-dim hover:text-foreground border border-line px-2.5 py-1.5 rounded-sm transition-colors font-medium">
                                Cancel
                            </button>
                        @endif
                    </div>

                    <div class="mt-1.5">
                        <p class="text-[10px] text-dim">JPG, PNG or GIF · Max 2 MB</p>
                        @error('avatar') <p class="text-red-600 text-[10px] mt-0.5">{{ $message }}</p> @enderror
                        <div wire:loading wire:target="avatar" class="text-[10px] text-accent mt-0.5 flex items-center gap-1">
                            <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Uploading...
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-line"></div>

            {{-- Form Fields --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="block text-xs font-medium text-dim mb-1.5 uppercase tracking-wider">Full Name</label>
                    <input id="name" type="text" wire:model="name"
                        class="w-full rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-red-600 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-medium text-dim mb-1.5 uppercase tracking-wider">Email</label>
                    <input id="email" type="email" wire:model="email"
                        class="w-full rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground @error('email') border-red-500 @enderror">
                    @error('email') <p class="text-red-600 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="phone" class="block text-xs font-medium text-dim mb-1.5 uppercase tracking-wider">Phone Number</label>
                    <input id="phone" type="tel" wire:model="phone"
                        class="w-full rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground @error('phone') border-red-500 @enderror">
                    @error('phone') <p class="text-red-600 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Bio --}}
            <div>
                <label for="bio" class="block text-xs font-medium text-dim mb-1.5 uppercase tracking-wider">Bio</label>
                <textarea id="bio" wire:model="bio" rows="4" placeholder="Tell us about yourself..."
                    class="w-full rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground placeholder-dim/50 focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground resize-none @error('bio') border-red-500 @enderror"></textarea>
                @error('bio') <p class="text-red-600 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Submit --}}
            <div class="flex gap-2.5 pt-2">
                <button type="submit" wire:loading.attr="disabled"
                    class="px-5 py-2 bg-foreground text-on-primary rounded-sm hover:opacity-90 font-medium text-sm transition-all disabled:opacity-50">
                    <span wire:loading.remove>Save Changes</span>
                    <span wire:loading class="inline-flex items-center gap-1.5">
                        <svg class="h-3 w-3 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...
                    </span>
                </button>
                <a href="{{ route('renter.home') }}" class="px-5 py-2 border border-line rounded-sm text-dim hover:text-foreground hover:border-foreground font-medium text-sm transition-all">
                    Cancel
                </a>
            </div>
        </form>

        {{-- Account Status --}}
        <div class="bg-card border border-line rounded-sm p-5" style="box-shadow: var(--shadow-xs);">
            <h3 class="text-sm font-semibold text-foreground font-serif tracking-tight mb-4">Account Status</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-dim uppercase tracking-wider">Status</span>
                    <span class="px-2 py-0.5 bg-green-100 text-green-800 dark:bg-green-500/10 dark:text-green-400 rounded-sm text-[10px] font-semibold uppercase tracking-wider">
                        {{ ucfirst(auth()->user()->status) }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-dim uppercase tracking-wider">Member Since</span>
                    <span class="text-xs text-foreground font-medium">{{ auth()->user()->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-dim uppercase tracking-wider">Last Updated</span>
                    <span class="text-xs text-foreground font-medium">{{ auth()->user()->updated_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
