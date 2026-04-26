<div>
    <div class="w-full max-w-sm">
        {{-- Header --}}
        <div class="mb-10">
            <h1 class="text-3xl font-semibold text-foreground font-serif tracking-tight">Welcome back</h1>
            <p class="mt-2 text-sm text-dim">Enter your credentials to access your account.</p>
        </div>

        <form wire:submit="login" class="space-y-5">
            {{-- Email --}}
            <div>
                <label for="email" class="block text-xs font-medium text-dim mb-2 tracking-wide uppercase">Email</label>
                <input id="email" type="email" wire:model="email" placeholder="you@example.com"
                    class="auth-input @error('email') input-error @enderror" />
                @error('email')
                    <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-xs font-medium text-dim mb-2 tracking-wide uppercase">Password</label>
                <input id="password" type="password" wire:model="password" placeholder="••••••••"
                    class="auth-input @error('password') input-error @enderror" />
                @error('password')
                    <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center gap-2.5">
                <input id="remember" type="checkbox" wire:model="remember"
                    class="h-3.5 w-3.5 rounded-sm border-line text-foreground focus:ring-foreground" />
                <label for="remember" class="text-sm text-dim cursor-pointer select-none">Remember me</label>
            </div>

            {{-- Submit --}}
            <button type="submit" wire:loading.attr="disabled"
                class="w-full rounded bg-foreground px-4 py-2.5 text-sm font-medium text-on-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-foreground focus:ring-offset-2 focus:ring-offset-page disabled:opacity-50 transition-all mt-2">
                <span wire:loading.remove>Sign In</span>
                <span wire:loading class="inline-flex items-center gap-2">
                    <svg class="h-4 w-4 animate-spin opacity-70" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Signing in…
                </span>
            </button>
        </form>

        {{-- Footer link --}}
        <p class="mt-8 pt-6 border-t border-line text-sm text-dim text-center">
            Don't have an account?
            <a href="{{ route('register') }}" wire:navigate class="text-foreground font-semibold hover:underline underline-offset-4">Create one</a>
        </p>
    </div>
</div>
