<div>
    {{-- Page Header --}}
    <div class="flex items-end justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-foreground font-serif tracking-tight">My Inquiries</h1>
            <p class="text-sm text-dim mt-1">Track conversations about properties you're interested in.</p>
        </div>
        @if($inquiries->total() > 0)
            <p class="text-xs text-dim shrink-0">{{ $inquiries->total() }} {{ Str::plural('inquiry', $inquiries->total()) }}</p>
        @endif
    </div>

    {{-- Status Filter Tabs --}}
    <div class="flex gap-1 mb-6 bg-subtle/50 rounded-sm p-1 w-fit">
        @foreach(['' => 'All', 'pending' => 'Pending', 'responded' => 'Responded', 'closed' => 'Closed'] as $val => $label)
            <button wire:click="$set('statusFilter', '{{ $val }}')"
                    class="px-4 py-1.5 rounded-[3px] text-xs font-semibold transition-all
                        {{ $statusFilter === $val ? 'bg-foreground text-on-primary shadow-sm' : 'text-dim hover:text-foreground' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Inquiry Cards --}}
    @if($inquiries->count() > 0)
        <div class="space-y-5 mb-8">
            @foreach($inquiries as $inquiry)
                @php
                    $prop   = $inquiry->property;
                    $cover  = $prop?->images?->first();
                    $brgy   = $prop?->address?->barangay;
                    $city   = $brgy?->city;
                    $prov   = $city?->province;
                    $isPending    = $inquiry->status === 'pending';
                    $isResponded  = $inquiry->status === 'responded';
                    $isClosed     = $inquiry->status === 'closed';
                @endphp
                <div class="bg-card border border-line rounded-sm overflow-hidden transition-all hover:border-foreground/20"
                     style="box-shadow: var(--shadow-xs);">

                    {{-- Property header strip --}}
                    <div class="flex items-stretch">
                        {{-- Cover thumbnail --}}
                        <a href="{{ route('renter.property', $prop->id) }}" wire:navigate
                           class="w-24 sm:w-32 shrink-0 bg-subtle relative overflow-hidden block group">
                            @if($cover)
                                <img src="{{ asset('storage/' . $cover->image_path) }}"
                                     alt="{{ $prop->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full min-h-[88px] flex items-center justify-center">
                                    <svg class="w-8 h-8 text-dim/20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </a>

                        {{-- Property info --}}
                        <div class="flex-1 px-4 py-3 flex items-start justify-between gap-3 min-w-0">
                            <div class="min-w-0">
                                @if($prop?->propertyType)
                                    <span class="text-[9px] font-semibold uppercase tracking-wider text-accent">{{ $prop->propertyType->name }}</span>
                                @endif
                                <a href="{{ route('renter.property', $prop->id) }}" wire:navigate
                                   class="block text-sm font-semibold text-foreground font-serif hover:underline underline-offset-2 truncate mt-0.5">
                                    {{ $prop?->title ?? 'Property' }}
                                </a>
                                @if($city)
                                    <p class="text-[11px] text-dim mt-0.5 truncate">
                                        {{ $brgy?->name ? $brgy->name . ', ' : '' }}{{ $city->name }}{{ $prov ? ', ' . $prov->name : '' }}
                                    </p>
                                @endif
                                <p class="text-[10px] text-dim/60 mt-1.5">
                                    Sent {{ $inquiry->created_at->format('M d, Y · g:i A') }}
                                </p>
                            </div>

                            {{-- Status badge --}}
                            <div class="shrink-0 flex flex-col items-end gap-1.5">
                                <span class="inline-flex items-center gap-1 rounded-sm px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider
                                    {{ $isPending ? 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400' : ($isResponded ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-subtle text-dim') }}">
                                    @if($isPending)
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse inline-block"></span>
                                    @elseif($isResponded)
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    @endif
                                    {{ ucfirst($inquiry->status) }}
                                </span>
                                @if($prop?->price)
                                    <span class="text-[10px] text-dim font-serif font-semibold">₱{{ number_format($prop->price, 0) }}<span class="font-sans font-normal">/mo</span></span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Conversation thread --}}
                    <div class="px-5 py-4 space-y-3 border-t border-line bg-page/30">

                        {{-- Renter message --}}
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-full bg-foreground flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-[10px] font-semibold text-on-primary font-serif">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-baseline gap-2 mb-1">
                                    <span class="text-xs font-semibold text-foreground">You</span>
                                    <span class="text-[10px] text-dim">{{ $inquiry->created_at->format('M d · g:i A') }}</span>
                                </div>
                                <div class="bg-subtle border border-line rounded-sm rounded-tl-none px-4 py-3 text-sm text-foreground leading-relaxed">
                                    {{ $inquiry->message }}
                                </div>
                            </div>
                        </div>

                        {{-- Owner response or waiting state --}}
                        @if($inquiry->response)
                            <div class="flex items-start gap-3 flex-row-reverse">
                                <div class="w-7 h-7 rounded-full bg-accent/15 border border-accent/25 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-baseline gap-2 mb-1 justify-end">
                                        @if($inquiry->responded_at)
                                            <span class="text-[10px] text-dim">{{ $inquiry->responded_at->format('M d · g:i A') }}</span>
                                        @endif
                                        <span class="text-xs font-semibold text-accent">Property Owner</span>
                                    </div>
                                    <div class="bg-accent/5 border border-accent/20 rounded-sm rounded-tr-none px-4 py-3 text-sm text-foreground leading-relaxed">
                                        {{ $inquiry->response }}
                                    </div>
                                </div>
                            </div>
                        @elseif($isPending)
                            <div class="flex items-center gap-2.5 py-2 px-1">
                                <div class="flex gap-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-dim/30 animate-bounce" style="animation-delay: 0ms"></span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-dim/30 animate-bounce" style="animation-delay: 150ms"></span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-dim/30 animate-bounce" style="animation-delay: 300ms"></span>
                                </div>
                                <span class="text-xs text-dim italic">Waiting for a response from the property owner…</span>
                            </div>
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="px-5 py-2.5 border-t border-line flex items-center justify-between">
                        <a href="{{ route('renter.property', $prop->id) }}" wire:navigate
                           class="inline-flex items-center gap-1 text-xs text-dim hover:text-foreground font-medium transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                            </svg>
                            View Property
                        </a>
                        @if($isClosed)
                            <span class="text-[10px] text-dim">Closed · {{ $inquiry->updated_at->format('M d, Y') }}</span>
                        @elseif($isResponded && $inquiry->responded_at)
                            <span class="text-[10px] text-dim">Responded {{ $inquiry->responded_at->diffForHumans() }}</span>
                        @else
                            <span class="text-[10px] text-dim">{{ $inquiry->created_at->diffForHumans() }}</span>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>

        {{ $inquiries->links() }}

    @else
        {{-- Empty state --}}
        <div class="flex flex-col items-center justify-center py-24 px-8">
            <div class="w-16 h-16 rounded-full bg-subtle flex items-center justify-center mb-5">
                <svg class="w-8 h-8 text-dim/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-foreground font-serif mb-2">
                {{ $statusFilter ? 'No ' . $statusFilter . ' inquiries' : 'No inquiries yet' }}
            </h3>
            <p class="text-sm text-dim text-center max-w-xs mb-6">
                {{ $statusFilter ? 'Try switching to a different filter above.' : 'When you send an inquiry about a property, it will appear here.' }}
            </p>
            @if(!$statusFilter)
                <a href="{{ route('renter.explore') }}" wire:navigate
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-foreground text-on-primary rounded-sm text-sm font-medium hover:opacity-90 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                    Explore Properties
                </a>
            @endif
        </div>
    @endif
</div>
