<div>
    <x-slot:header>
        <span class="font-medium text-foreground">Inquiries</span>
    </x-slot:header>

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-foreground font-serif tracking-tight">Inquiries</h1>
        <p class="text-xs text-dim mt-1">View and respond to renter inquiries.</p>
    </div>

    <div class="rounded-sm border border-line bg-card" style="box-shadow: var(--shadow-xs);">
        {{-- Toolbar --}}
        <div class="p-4 border-b border-line flex flex-col sm:flex-row sm:items-center gap-3">
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Search by message, property or renter..."
                   class="w-full sm:max-w-xs rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground placeholder-dim/50 focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground" />

            <div class="flex items-center gap-2 sm:ml-auto">
                <select wire:model.live="filterStatus"
                        class="rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="responded">Responded</option>
                    <option value="closed">Closed</option>
                </select>

                <select wire:model.live="perPage"
                        class="rounded-sm border border-line bg-page text-foreground text-sm py-2 px-2 focus:outline-none focus:ring-1 focus:ring-foreground">
                    <option value="10">10 / page</option>
                    <option value="25">25 / page</option>
                    <option value="50">50 / page</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto transition-opacity duration-200" wire:loading.class="opacity-50 pointer-events-none">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-line bg-subtle/50">
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">#</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Renter</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Property</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Message</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Status</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Date</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse($inquiries as $inquiry)
                        <tr class="hover:bg-subtle/50 transition-colors">
                            <td class="px-4 py-3 text-dim">{{ $inquiry->id }}</td>
                            <td class="px-4 py-3 font-medium text-foreground">{{ $inquiry->user->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-foreground">{{ $inquiry->property->title ?? '—' }}</td>
                            <td class="px-4 py-3 text-dim max-w-[200px] truncate">{{ $inquiry->message }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $badge = match($inquiry->status) {
                                        'pending'   => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400',
                                        'responded' => 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                                        'closed'    => 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                                        default     => 'bg-subtle text-dim',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-sm px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider {{ $badge }}">
                                    {{ ucfirst($inquiry->status ?? 'pending') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-dim">{{ $inquiry->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <button wire:click="viewInquiry({{ $inquiry->id }})"
                                            class="text-xs text-dim hover:text-foreground font-medium transition-colors">View / Respond</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-dim">No inquiries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-line">
            {{ $inquiries->links() }}
        </div>
    </div>

    {{-- View / Respond Modal --}}
    @if($viewing)
        @php
            $iProp  = $viewing->property;
            $iCover = $iProp?->images?->first();
            $iBrgy  = $iProp?->address?->barangay;
            $iCity  = $iBrgy?->city;
            $iProv  = $iCity?->province;
            $iBadge = match($viewing->status) {
                'pending'   => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400',
                'responded' => 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                'closed'    => 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                default     => 'bg-subtle text-dim',
            };
        @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-foreground/40 backdrop-blur-sm" wire:click="closeView"></div>
            <div class="relative w-full max-w-2xl max-h-[90vh] flex flex-col rounded-sm border border-line bg-card overflow-hidden" style="box-shadow: var(--shadow-lg);">

                {{-- Header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-line shrink-0">
                    <div class="flex items-center gap-3">
                        <h3 class="text-base font-semibold text-foreground font-serif tracking-tight">Inquiry #{{ $viewing->id }}</h3>
                        <span class="inline-flex items-center rounded-sm px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider {{ $iBadge }}">
                            {{ ucfirst($viewing->status) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] text-dim">{{ $viewing->created_at->format('M d, Y · g:i A') }}</span>
                        <button wire:click="closeView" class="text-dim hover:text-foreground transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Scrollable body --}}
                <div class="flex-1 overflow-y-auto">

                    {{-- Property context banner --}}
                    <div class="flex items-stretch gap-0 border-b border-line">
                        {{-- Thumbnail --}}
                        <div class="w-28 shrink-0 bg-subtle relative overflow-hidden">
                            @if($iCover)
                                <img src="{{ asset('storage/' . $iCover->image_path) }}"
                                     alt="{{ $iProp->title }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full min-h-[90px] flex items-center justify-center">
                                    <svg class="w-8 h-8 text-dim/20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        {{-- Property meta --}}
                        <div class="flex-1 px-4 py-3 bg-subtle/20">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    @if($iProp?->propertyType)
                                        <span class="text-[9px] font-semibold uppercase tracking-wider text-accent">{{ $iProp->propertyType->name }}</span>
                                    @endif
                                    <a href="{{ route('admin.property', $iProp->id) }}" wire:navigate
                                       class="block text-sm font-semibold text-foreground font-serif hover:underline underline-offset-2 leading-snug mt-0.5">
                                        {{ $iProp?->title ?? '—' }}
                                    </a>
                                    @if($iCity)
                                        <p class="text-[11px] text-dim mt-0.5">
                                            {{ $iBrgy?->name ? $iBrgy->name . ', ' : '' }}{{ $iCity->name }}{{ $iProv ? ', ' . $iProv->name : '' }}
                                        </p>
                                    @endif
                                </div>
                                @if($iProp?->price)
                                    <div class="shrink-0 text-right">
                                        <p class="text-[9px] text-dim uppercase tracking-wider">Monthly</p>
                                        <p class="text-sm font-bold text-foreground font-serif">₱{{ number_format($iProp->price, 0) }}</p>
                                    </div>
                                @endif
                            </div>
                            {{-- Stats --}}
                            @if($iProp)
                                <div class="flex items-center gap-3 mt-2">
                                    @if($iProp->bedrooms !== null)
                                        <span class="text-[10px] text-dim"><strong class="text-foreground">{{ $iProp->bedrooms }}</strong> bed</span>
                                    @endif
                                    @if($iProp->bathrooms !== null)
                                        <span class="text-[10px] text-dim"><strong class="text-foreground">{{ $iProp->bathrooms }}</strong> bath</span>
                                    @endif
                                    @if($iProp->area)
                                        <span class="text-[10px] text-dim"><strong class="text-foreground">{{ $iProp->area }}</strong> sqm</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="p-5 space-y-5">

                        {{-- Renter info --}}
                        <div class="flex items-center gap-3 pb-5 border-b border-line">
                            <div class="w-9 h-9 rounded-full bg-foreground flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-semibold text-on-primary font-serif">
                                    {{ strtoupper(substr($viewing->user?->name ?? '?', 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-foreground">{{ $viewing->user?->name ?? '—' }}</p>
                                <p class="text-[11px] text-dim">{{ $viewing->user?->email ?? '' }}</p>
                            </div>
                            <span class="ml-auto text-[10px] text-dim">Sent {{ $viewing->created_at->diffForHumans() }}</span>
                        </div>

                        {{-- Message thread --}}
                        <div class="space-y-4">

                            {{-- Renter message bubble --}}
                            <div class="flex items-start gap-3">
                                <div class="w-7 h-7 rounded-full bg-foreground flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-[10px] font-semibold text-on-primary font-serif">
                                        {{ strtoupper(substr($viewing->user?->name ?? '?', 0, 1)) }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-baseline gap-2 mb-1">
                                        <span class="text-xs font-semibold text-foreground">{{ $viewing->user?->name ?? 'Renter' }}</span>
                                        <span class="text-[10px] text-dim">{{ $viewing->created_at->format('M d, Y · g:i A') }}</span>
                                    </div>
                                    <div class="rounded-sm rounded-tl-none border border-line bg-subtle/40 px-4 py-3 text-sm text-foreground leading-relaxed">
                                        {{ $viewing->message }}
                                    </div>
                                </div>
                            </div>

                            {{-- Admin response bubble (if already responded) --}}
                            @if($viewing->response)
                                <div class="flex items-start gap-3 flex-row-reverse">
                                    <div class="w-7 h-7 rounded-full bg-accent/20 border border-accent/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg class="w-3.5 h-3.5 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-baseline gap-2 mb-1 justify-end">
                                            <span class="text-[10px] text-dim">
                                                @if($viewing->responded_at) {{ $viewing->responded_at->format('M d, Y · g:i A') }} @endif
                                            </span>
                                            <span class="text-xs font-semibold text-accent">Admin (You)</span>
                                        </div>
                                        <div class="rounded-sm rounded-tr-none border border-accent/20 bg-accent/5 px-4 py-3 text-sm text-foreground leading-relaxed">
                                            {{ $viewing->response }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Response composer --}}
                        @if($viewing->status !== 'closed')
                            <div class="border border-line rounded-sm overflow-hidden">
                                <div class="px-3 py-2 bg-subtle/50 border-b border-line flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-full bg-accent/20 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                    </div>
                                    <span class="text-[10px] font-semibold text-dim uppercase tracking-wider">
                                        {{ $viewing->response ? 'Update your response' : 'Write a response' }}
                                    </span>
                                </div>
                                <textarea wire:model="responseText" rows="3"
                                    class="w-full px-4 py-3 text-sm text-foreground bg-page placeholder-dim/50 focus:outline-none resize-none"
                                    placeholder="Type your response to the renter..."></textarea>
                                @error('responseText')
                                    <p class="px-4 pb-2 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <div class="rounded-sm border border-line bg-subtle/30 px-4 py-3 text-center text-xs text-dim">
                                This inquiry has been closed and can no longer receive responses.
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between px-5 py-3.5 border-t border-line bg-subtle/30 shrink-0">
                    <div>
                        @if($viewing->status !== 'closed')
                            <button wire:click="markClosed({{ $viewing->id }})" wire:confirm="Close this inquiry? This cannot be undone."
                                    class="text-xs text-dim hover:text-red-500 font-medium transition-colors flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Mark as Closed
                            </button>
                        @else
                            <span class="text-xs text-dim">Closed inquiry</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        @if($viewing->status !== 'closed')
                            <button wire:click="submitResponse"
                                    class="inline-flex items-center gap-1.5 rounded-sm bg-foreground px-4 py-2 text-sm font-medium text-on-primary hover:opacity-90 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                                {{ $viewing->response ? 'Update Response' : 'Send Response' }}
                            </button>
                        @endif
                        <button wire:click="closeView"
                                class="rounded-sm border border-line px-4 py-2 text-sm font-medium text-dim hover:text-foreground hover:bg-subtle transition-colors">
                            Close
                        </button>
                    </div>
                </div>

            </div>
        </div>
    @endif
</div>
