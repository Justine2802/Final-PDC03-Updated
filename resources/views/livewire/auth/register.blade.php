<div>
    <div class="w-full max-w-sm">
        {{-- Header --}}
        <div class="mb-10">
            <h1 class="text-3xl font-semibold text-foreground font-serif tracking-tight">Create account</h1>
            <p class="mt-2 text-sm text-dim">Join {{ config('app.name') }} and find your perfect property.</p>
        </div>

        <form wire:submit="register" class="space-y-5">
            {{-- Name --}}
            <div>
                <label for="name" class="block text-xs font-medium text-dim mb-2 tracking-wide uppercase">Full Name</label>
                <input id="name" type="text" wire:model="name" placeholder="John Doe"
                    class="auth-input @error('name') input-error @enderror" />
                @error('name')
                    <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-xs font-medium text-dim mb-2 tracking-wide uppercase">Email</label>
                <input id="email" type="email" wire:model="email" placeholder="you@example.com"
                    class="auth-input @error('email') input-error @enderror" />
                @error('email')
                    <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Phone --}}
            <div>
                <label for="phone" class="block text-xs font-medium text-dim mb-2 tracking-wide uppercase">Phone <span class="text-dim/50 normal-case tracking-normal">(optional)</span></label>
                <input id="phone" type="tel" wire:model="phone" placeholder="+63 912 345 6789"
                    class="auth-input @error('phone') input-error @enderror" />
                @error('phone')
                    <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password row --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-medium text-dim mb-2 tracking-wide uppercase">Password</label>
                    <input id="password" type="password" wire:model="password" placeholder="••••••••"
                        class="auth-input @error('password') input-error @enderror" />
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-xs font-medium text-dim mb-2 tracking-wide uppercase">Confirm</label>
                    <input id="password_confirmation" type="password" wire:model="password_confirmation" placeholder="••••••••"
                        class="auth-input" />
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" wire:loading.attr="disabled"
                class="w-full rounded bg-foreground px-4 py-2.5 text-sm font-medium text-on-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-foreground focus:ring-offset-2 focus:ring-offset-page disabled:opacity-50 transition-all mt-2">
                <span wire:loading.remove>Create Account</span>
                <span wire:loading class="inline-flex items-center gap-2">
                    <svg class="h-4 w-4 animate-spin opacity-70" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Creating…
                </span>
            </button>
        </form>

        {{-- Footer link --}}
        <p class="mt-8 pt-6 border-t border-line text-sm text-dim text-center">
            Already have an account?
            <a href="{{ route('login') }}" wire:navigate class="text-foreground font-semibold hover:underline underline-offset-4">Sign in</a>
        </p>
    </div>
</div>
