<div wire:poll.5000ms="checkStatus">
    <div class="w-full max-w-sm">

        {{-- Submission success flash --}}
        @if(session('just_submitted'))
            <div class="mb-6 flex items-start gap-3 rounded border border-emerald-500/30 bg-emerald-500/10 px-4 py-3.5">
                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-emerald-400">Information submitted successfully!</p>
                    <p class="text-xs text-emerald-400/70 mt-0.5">Please wait while an admin reviews and approves your profile. You'll receive an email once verified.</p>
                </div>
            </div>
        @endif

        {{-- Icon --}}
        <div class="mb-8 flex items-center justify-center">
            <div class="w-16 h-16 rounded-full bg-amber-500/10 border border-amber-500/30 flex items-center justify-center">
                <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
        </div>

        {{-- Header --}}
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-semibold text-foreground font-serif tracking-tight">Verification Pending</h1>
            <p class="mt-3 text-sm text-dim leading-relaxed">
                Your profile has been submitted and is currently under review.
                You'll gain full access once an admin verifies your information.
            </p>
        </div>

        {{-- Status steps --}}
        <div class="rounded border border-line bg-surface/50 divide-y divide-line mb-6">
            <div class="flex items-start gap-3 px-4 py-3">
                <span class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full bg-green-600 text-white text-xs font-bold flex items-center justify-center">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </span>
                <p class="text-sm text-dim">Email verified</p>
            </div>
            <div class="flex items-start gap-3 px-4 py-3">
                <span class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full bg-green-600 text-white text-xs font-bold flex items-center justify-center">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </span>
                <p class="text-sm text-dim">Personal information submitted</p>
            </div>
            <div class="flex items-start gap-3 px-4 py-3">
                <span class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-500 text-xs font-bold flex items-center justify-center">
                    <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </span>
                <p class="text-sm text-dim">Admin review — <span class="text-amber-500 font-medium">in progress</span></p>
            </div>
            <div class="flex items-start gap-3 px-4 py-3 opacity-40">
                <span class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full border border-line text-xs font-bold flex items-center justify-center text-dim">4</span>
                <p class="text-sm text-dim">Account fully unlocked</p>
            </div>
        </div>

        {{-- Info note --}}
        <p class="text-xs text-dim text-center leading-relaxed mb-6">
            This usually takes a short while. You'll be able to access all renter services once approved.
        </p>

        {{-- Sign out --}}
        <p class="pt-5 border-t border-line text-sm text-dim text-center">
            Need to switch accounts?
            <button wire:click="logout" class="text-foreground font-semibold hover:underline underline-offset-4">Sign out</button>
        </p>

    </div>
</div>
