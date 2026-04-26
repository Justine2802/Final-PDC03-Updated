<div>
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h2 class="text-xl font-semibold text-foreground font-serif tracking-tight">My Inquiries</h2>
            <p class="text-xs text-dim mt-1">Track responses to your property inquiries.</p>
        </div>

        {{-- Status Filter --}}
        <div class="flex gap-2 flex-wrap">
            <button wire:click="$set('statusFilter', '')" 
                    class="px-3.5 py-1.5 rounded-sm text-xs font-medium transition-all
                    @if(!$statusFilter) bg-foreground text-on-primary @else border border-line text-dim hover:text-foreground hover:border-foreground @endif">
                All
            </button>
            <button wire:click="$set('statusFilter', 'pending')" 
                    class="px-3.5 py-1.5 rounded-sm text-xs font-medium transition-all
                    @if($statusFilter === 'pending') bg-foreground text-on-primary @else border border-line text-dim hover:text-foreground hover:border-foreground @endif">
                Pending
            </button>
            <button wire:click="$set('statusFilter', 'responded')" 
                    class="px-3.5 py-1.5 rounded-sm text-xs font-medium transition-all
                    @if($statusFilter === 'responded') bg-foreground text-on-primary @else border border-line text-dim hover:text-foreground hover:border-foreground @endif">
                Responded
            </button>
            <button wire:click="$set('statusFilter', 'closed')" 
                    class="px-3.5 py-1.5 rounded-sm text-xs font-medium transition-all
                    @if($statusFilter === 'closed') bg-foreground text-on-primary @else border border-line text-dim hover:text-foreground hover:border-foreground @endif">
                Closed
            </button>
        </div>

        {{-- Inquiries List --}}
        @if($inquiries->count() > 0)
            <div class="space-y-4 mb-8">
                @foreach($inquiries as $inquiry)
                    <div class="bg-card border border-line rounded-sm overflow-hidden hover:border-foreground/30 transition-all" style="box-shadow: var(--shadow-xs);">
                        <div class="px-5 py-4 flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-foreground font-serif tracking-tight">{{ $inquiry->property->title ?? 'Property' }}</h3>
                                <p class="text-[10px] text-accent uppercase tracking-wider mt-0.5">{{ $inquiry->property->propertyType->name ?? 'Property' }}</p>
                                <p class="text-[10px] text-dim mt-2">
                                    Inquiry sent on {{ $inquiry->created_at->format('M d, Y \a\t h:i A') }}
                                </p>
                            </div>
                            <span class="ml-4 px-2 py-0.5 rounded-sm text-[10px] font-semibold uppercase tracking-wider flex-shrink-0
                                @if($inquiry->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-500/10 dark:text-yellow-400
                                @elseif($inquiry->status === 'responded') bg-green-100 text-green-800 dark:bg-green-500/10 dark:text-green-400
                                @else bg-red-100 text-red-800 dark:bg-red-500/10 dark:text-red-400
                                @endif">
                                {{ ucfirst($inquiry->status) }}
                            </span>
                        </div>

                        {{-- Messages --}}
                        <div class="px-5 py-4 space-y-4 border-t border-line">
                            <div>
                                <p class="text-[10px] font-semibold text-dim uppercase tracking-[0.12em] mb-2">Your Inquiry</p>
                                <p class="text-sm text-foreground bg-subtle rounded-sm p-4 leading-relaxed">{{ $inquiry->message }}</p>
                            </div>

                            @if($inquiry->response)
                                <div>
                                    <p class="text-[10px] font-semibold text-dim uppercase tracking-[0.12em] mb-2">Response from Owner</p>
                                    <p class="text-sm text-foreground bg-green-50 dark:bg-green-500/5 border border-green-200 dark:border-green-500/20 rounded-sm p-4 leading-relaxed">{{ $inquiry->response }}</p>
                                    @if($inquiry->responded_at)
                                        <p class="text-[10px] text-dim mt-2">Responded on {{ $inquiry->responded_at->format('M d, Y \a\t h:i A') }}</p>
                                    @endif
                                </div>
                            @elseif($inquiry->status === 'pending')
                                <div>
                                    <p class="text-sm text-dim bg-subtle border border-line rounded-sm p-4 italic">
                                        Waiting for response from the property owner...
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="px-5 py-3 border-t border-line">
                            <a href="{{ route('renter.explore') }}" class="text-xs text-foreground hover:underline underline-offset-4 font-medium transition-colors">
                                View Similar Properties →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $inquiries->links() }}</div>
        @else
            <div class="text-center py-16 px-8 bg-card border border-line rounded-sm">
                <svg class="w-12 h-12 mx-auto text-dim/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <p class="text-base font-semibold text-foreground font-serif">No inquiries yet</p>
                <p class="text-xs text-dim mt-2">Send an inquiry about a property to get started</p>
                <a href="{{ route('renter.explore') }}" class="inline-block mt-6 px-6 py-2 bg-foreground text-on-primary rounded-sm hover:opacity-90 font-medium text-sm transition-all">
                    Explore Properties
                </a>
            </div>
        @endif
    </div>
</div>
