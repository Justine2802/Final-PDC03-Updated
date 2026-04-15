<div>
    <div class="w-full max-w-sm">
        {{-- Logo --}}
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-md bg-primary">
                <span class="text-lg font-bold text-on-primary">L</span>
            </div>
            <h1 class="text-xl font-semibold text-foreground">Sign in to your account</h1>
            <p class="mt-1 text-sm text-dim">Find and manage your perfect rental properties</p>
        </div>

        <form wire:submit="login" class="space-y-4">
            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-foreground mb-1.5">Email</label>
                <input id="email" type="email" wire:model="email" placeholder="your@example.com"
                    class="w-full rounded-md border border-line bg-card px-3 py-2 text-sm text-foreground placeholder-dim focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary @error('email') border-red-500 @enderror" />
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-foreground mb-1.5">Password</label>
                <input id="password" type="password" wire:model="password" placeholder="Enter your password"
                    class="w-full rounded-md border border-line bg-card px-3 py-2 text-sm text-foreground placeholder-dim focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary @error('password') border-red-500 @enderror" />
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center gap-2">
                <input id="remember" type="checkbox" wire:model="remember"
                    class="h-4 w-4 rounded border-line text-primary focus:ring-primary" />
                <label for="remember" class="text-sm text-dim">Remember me</label>
            </div>

            {{-- Submit --}}
            <button type="submit" wire:loading.attr="disabled"
                class="w-full rounded-md bg-primary px-4 py-2.5 text-sm font-medium text-on-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-50 transition-colors">
                <span wire:loading.remove>Sign In</span>
                <span wire:loading class="inline-flex items-center gap-2">
                    <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Signing in...
                </span>
            </button>

            {{-- Sign Up Link --}}
            <p class="text-center text-sm text-dim">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-primary hover:underline font-medium">Sign Up</a>
            </p>
        </form>
    </div>
</div>

