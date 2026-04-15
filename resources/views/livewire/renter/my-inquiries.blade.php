<div>
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h2 class="text-2xl font-bold text-foreground">My Inquiries</h2>
        </div>

        {{-- Status Filter --}}
        <div class="bg-card border border-line rounded-lg p-8 flex gap-5 flex-wrap">
            <button wire:click="$set('statusFilter', '')" 
                    class="px-5 py-3.5 rounded-md font-semibold text-base transition-colors
                    @if(!$statusFilter) bg-primary text-on-primary @else border border-line text-foreground hover:bg-line @endif">
                All
            </button>
            <button wire:click="$set('statusFilter', 'pending')" 
                    class="px-5 py-3.5 rounded-md font-semibold text-base transition-colors
                    @if($statusFilter === 'pending') bg-yellow-500 text-white @else border border-line text-foreground hover:bg-line @endif">
                Pending
            </button>
            <button wire:click="$set('statusFilter', 'responded')" 
                    class="px-5 py-3.5 rounded-md font-semibold text-base transition-colors
                    @if($statusFilter === 'responded') bg-green-500 text-white @else border border-line text-foreground hover:bg-line @endif">
                Responded
            </button>
            <button wire:click="$set('statusFilter', 'closed')" 
                    class="px-5 py-3.5 rounded-md font-semibold text-base transition-colors
                    @if($statusFilter === 'closed') bg-red-500 text-white @else border border-line text-foreground hover:bg-line @endif">
                Closed
            </button>
        </div>

        {{-- Inquiries List --}}
        @if($inquiries->count() > 0)
            <div class="space-y-6 mb-8">
                @foreach($inquiries as $inquiry)
                    <div class="bg-card border border-line rounded-lg overflow-hidden hover:border-primary/50 transition-colors hover:shadow-lg">
                        <div class="px-8 py-6 flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-foreground">{{ $inquiry->property->title ?? 'Property' }}</h3>
                                <p class="text-sm text-dim mt-1.5">{{ $inquiry->property->propertyType->name ?? 'Property' }}</p>
                                <p class="text-xs text-dim mt-3">
                                    Inquiry sent on {{ $inquiry->created_at->format('M d, Y \a\t h:i A') }}
                                </p>
                            </div>
                            <span class="ml-6 px-4 py-2 rounded-full text-sm font-bold flex-shrink-0
                                @if($inquiry->status === 'pending')
                                    bg-yellow-100 text-yellow-800
                                @elseif($inquiry->status === 'responded')
                                    bg-green-100 text-green-800
                                @else
                                    bg-red-100 text-red-800
                                @endif
                            ">
                                {{ ucfirst($inquiry->status) }}
                            </span>
                        </div>

                        {{-- Messages Section --}}
                        <div class="px-8 py-6 space-y-6 border-t border-line">
                            {{-- Your Message --}}
                            <div>
                                <p class="text-xs font-bold text-dim uppercase tracking-wider mb-3">Your Inquiry</p>
                                <p class="text-base text-foreground bg-line rounded-md p-5">{{ $inquiry->message }}</p>
                            </div>

                            {{-- Response --}}
                            @if($inquiry->response)
                                <div>
                                    <p class="text-xs font-bold text-dim uppercase tracking-wider mb-3">Response from Owner</p>
                                    <p class="text-base text-foreground bg-green-50 border border-green-200 rounded-md p-5">{{ $inquiry->response }}</p>
                                    @if($inquiry->responded_at)
                                        <p class="text-xs text-dim mt-3">Responded on {{ $inquiry->responded_at->format('M d, Y \a\t h:i A') }}</p>
                                    @endif
                                </div>
                            @elseif($inquiry->status === 'pending')
                                <div class="py-2">
                                    <p class="text-base text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-md p-5">
                                        Waiting for response from the property owner...
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- Action --}}
                        <div class="px-8 py-5 border-t border-line">
                            <a href="{{ route('renter.explore') }}" class="text-primary hover:underline text-base font-semibold transition-colors">
                                View Similar Properties
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $inquiries->links() }}
            </div>
        @else
            <div class="text-center py-16 px-8 bg-card border border-line rounded-lg mb-12">
                <svg class="w-20 h-20 mx-auto text-dim mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <p class="text-foreground font-bold text-2xl">No inquiries yet</p>
                <p class="text-dim text-base mt-3">Send an inquiry about a property to get started</p>
                <a href="{{ route('renter.explore') }}" class="inline-block mt-8 px-8 py-3 bg-primary text-on-primary rounded-md hover:bg-primary/90 font-bold text-base transition-colors">
                    Explore Properties
                </a>
            </div>
        @endif
    </div>
</div>
