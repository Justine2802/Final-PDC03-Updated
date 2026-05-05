<div>
    <div class="w-full max-w-sm">

        {{-- Icon --}}
        <div class="mb-8 flex items-center justify-center">
            <div class="w-16 h-16 rounded-full bg-foreground/10 border border-line flex items-center justify-center">
                <svg class="w-7 h-7 text-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
            </div>
        </div>

        {{-- Header --}}
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-semibold text-foreground font-serif tracking-tight">Check your inbox</h1>
            <p class="mt-3 text-sm text-dim leading-relaxed">
                We sent a verification link to<br>
                <span class="text-foreground font-medium">{{ auth()->user()->email }}</span>
            </p>
        </div>

        {{-- Steps --}}
        <div class="rounded border border-line bg-surface/50 divide-y divide-line mb-6">
            <div class="flex items-start gap-3 px-4 py-3">
                <span class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full bg-foreground text-on-primary text-xs font-bold flex items-center justify-center">1</span>
                <p class="text-sm text-dim">Open the email from <span class="text-foreground font-medium">Rentdevous</span></p>
            </div>
            <div class="flex items-start gap-3 px-4 py-3">
                <span class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full bg-foreground text-on-primary text-xs font-bold flex items-center justify-center">2</span>
                <p class="text-sm text-dim">Click the <span class="text-foreground font-medium">"Verify Email Address"</span> button</p>
            </div>
            <div class="flex items-start gap-3 px-4 py-3">
                <span class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full bg-foreground text-on-primary text-xs font-bold flex items-center justify-center">3</span>
                <p class="text-sm text-dim">You'll be redirected to your dashboard automatically</p>
            </div>
        </div>

        {{-- Success flash --}}
        @if ($resent)
            <div class="mb-5 flex items-center gap-2.5 rounded border border-emerald-500/30 bg-emerald-500/10 px-4 py-3">
                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                <p class="text-sm text-emerald-400 font-medium">Verification link resent successfully.</p>
            </div>
        @endif

        {{-- Resend button --}}
        <button wire:click="resend" wire:loading.attr="disabled"
            class="w-full rounded bg-foreground px-4 py-2.5 text-sm font-medium text-on-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-foreground focus:ring-offset-2 focus:ring-offset-page disabled:opacity-50 transition-all">
            <span wire:loading.remove>Resend Verification Email</span>
            <span wire:loading class="inline-flex items-center gap-2">
                <svg class="h-4 w-4 animate-spin opacity-70" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Sending…
            </span>
        </button>

        {{-- Footer --}}
        <p class="mt-8 pt-6 border-t border-line text-sm text-dim text-center">
            Wrong account?
            <button wire:click="logout" class="text-foreground font-semibold hover:underline underline-offset-4">Sign out</button>
        </p>

    </div>
</div>
