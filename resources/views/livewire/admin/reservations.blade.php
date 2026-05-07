<div>
    <x-slot:header>
        <span class="font-medium text-foreground">Reservations</span>
    </x-slot:header>

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-foreground font-serif tracking-tight">Reservations</h1>
        <p class="text-xs text-dim mt-1">Review and manage all property reservations.</p>
    </div>

    <div class="rounded-sm border border-line bg-card" x-data="{ showFilters: false }" style="box-shadow: var(--shadow-xs);">
        {{-- Toolbar --}}
        <div class="p-4 border-b border-line flex flex-col sm:flex-row sm:items-center gap-3">
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Search by property or renter..."
                   class="w-full sm:max-w-xs rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground placeholder-dim/50 focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground" />

            <div class="flex items-center gap-2 sm:ml-auto">
                {{-- Filters toggle --}}
                <button @click="showFilters = !showFilters"
                    class="inline-flex items-center gap-1.5 rounded-sm border border-line px-3 py-2 text-sm font-medium text-dim hover:bg-subtle hover:text-foreground transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filters
                    @if($activeFilterCount > 0)
                        <span class="inline-flex items-center justify-center h-4 min-w-[1rem] rounded-sm bg-foreground text-on-primary text-[10px] font-semibold px-1">{{ $activeFilterCount }}</span>
                    @endif
                </button>

                {{-- Per page --}}
                <select wire:model.live="perPage"
                        class="rounded-sm border border-line bg-page text-foreground text-sm py-2 px-2 focus:outline-none focus:ring-1 focus:ring-foreground">
                    <option value="10">10 / page</option>
                    <option value="25">25 / page</option>
                    <option value="50">50 / page</option>
                </select>

                {{-- Export --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="inline-flex items-center gap-1.5 rounded-sm bg-foreground px-3 py-2 text-sm font-medium text-on-primary hover:opacity-90 transition-all">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition
                         class="absolute right-0 mt-1 w-40 rounded-sm border border-line bg-card z-50" style="box-shadow: var(--shadow-lg);">
                        <button wire:click="export" @click="open = false"
                            class="flex w-full items-center gap-2 px-3 py-2 text-sm text-foreground hover:bg-subtle transition-colors rounded-t-sm">
                            <svg class="h-4 w-4 text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Export as CSV
                        </button>
                        <button wire:click="exportExcel" @click="open = false"
                            class="flex w-full items-center gap-2 px-3 py-2 text-sm text-foreground hover:bg-subtle transition-colors rounded-b-sm border-t border-line">
                            <svg class="h-4 w-4 text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Export as Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters panel --}}
        <div x-show="showFilters" x-cloak class="p-4 border-b border-line bg-subtle/50">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-medium text-dim mb-1">Status</label>
                    <select wire:model.live="filterStatus"
                            class="w-full rounded-sm border border-line bg-card px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-dim mb-1">Move-in From</label>
                    <input type="date" wire:model.live="dateFrom"
                           class="w-full rounded-sm border border-line bg-card px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-dim mb-1">Move-in To</label>
                    <input type="date" wire:model.live="dateTo"
                           class="w-full rounded-sm border border-line bg-card px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground" />
                </div>
                <div class="flex items-end">
                    @if($activeFilterCount > 0)
                        <button wire:click="$set('filterStatus', ''); $set('dateFrom', ''); $set('dateTo', '')"
                                class="text-sm text-dim hover:text-foreground underline underline-offset-2">
                            Clear filters
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto transition-opacity duration-200" wire:loading.class="opacity-50 pointer-events-none">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-line bg-subtle/50">
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">#</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Property</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Renter</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Move In</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Move Out</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Total</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Status</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse($reservations as $reservation)
                        <tr class="hover:bg-subtle/50 transition-colors">
                            <td class="px-4 py-3 text-dim">{{ $reservation->id }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2.5 max-w-[200px]">
                                    <div class="w-9 h-9 rounded-sm overflow-hidden bg-subtle flex-shrink-0">
                                        @if($reservation->property?->images?->first())
                                            <img src="{{ asset('storage/' . $reservation->property->images->first()->image_path) }}"
                                                 class="w-full h-full object-cover" alt="">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-dim/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <span class="font-medium text-foreground text-sm truncate">{{ $reservation->property->title ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-foreground">
                                {{ $reservation->user->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-dim">{{ $reservation->move_in_date->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-dim">
                                {{ $reservation->move_out_date?->format('M d, Y') ?? '—' }}
                            </td>
                            <td class="px-4 py-3 font-semibold text-foreground font-serif">
                                &#8369;{{ number_format($reservation->total_price, 2) }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $badge = match($reservation->status) {
                                        'pending'   => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400',
                                        'confirmed' => 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                                        'cancelled' => 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                                        'completed' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
                                        default     => 'bg-subtle text-dim',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-sm px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider {{ $badge }}">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <button wire:click="viewReservation({{ $reservation->id }})"
                                            class="text-xs text-dim hover:text-foreground font-medium transition-colors">View</button>
                                    @if($reservation->status === 'pending')
                                        <button wire:click="askConfirm({{ $reservation->id }}, 'confirm')"
                                                class="text-xs text-green-600 hover:text-green-800 dark:text-green-400 font-medium transition-colors">Confirm</button>
                                        <button wire:click="askConfirm({{ $reservation->id }}, 'cancel')"
                                                class="text-xs text-red-500 hover:text-red-700 dark:text-red-400 font-medium transition-colors">Cancel</button>
                                    @elseif($reservation->status === 'confirmed')
                                        <button wire:click="askConfirm({{ $reservation->id }}, 'complete')"
                                                class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 font-medium transition-colors">Complete</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-dim">No reservations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-line">
            {{ $reservations->links() }}
        </div>
    </div>

    {{-- View Modal --}}
    @if($viewing)
        @php
            $vProp     = $viewing->property;
            $vCover    = $vProp?->images?->first();
            $vBrgy     = $vProp?->address?->barangay;
            $vCity     = $vBrgy?->city;
            $vProv     = $vCity?->province;
            $vBadge    = match($viewing->status) {
                'pending'   => ['bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400', 'Pending'],
                'confirmed' => ['bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400',  'Confirmed'],
                'cancelled' => ['bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',          'Cancelled'],
                'completed' => ['bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',      'Completed'],
                default     => ['bg-subtle text-dim', ucfirst($viewing->status)],
            };
            $months = $viewing->move_in_date && $viewing->move_out_date
                ? max(1, (int) $viewing->move_in_date->diffInMonths($viewing->move_out_date))
                : null;
        @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-foreground/40 backdrop-blur-sm" wire:click="closeView"></div>
            <div class="relative w-full max-w-2xl max-h-[90vh] flex flex-col rounded-sm border border-line bg-card overflow-hidden" style="box-shadow: var(--shadow-lg);">

                {{-- Header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-line shrink-0">
                    <div class="flex items-center gap-3">
                        <h3 class="text-base font-semibold text-foreground font-serif tracking-tight">Reservation #{{ $viewing->id }}</h3>
                        <span class="inline-flex items-center rounded-sm px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider {{ $vBadge[0] }}">
                            {{ $vBadge[1] }}
                        </span>
                    </div>
                    <button wire:click="closeView" class="text-dim hover:text-foreground transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="overflow-y-auto flex-1">

                    {{-- Property cover photo --}}
                    <div class="h-44 bg-subtle relative overflow-hidden shrink-0">
                        @if($vCover)
                            <img src="{{ asset('storage/' . $vCover->image_path) }}"
                                 alt="{{ $vProp->title }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-dim/20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        {{-- Property type pill over the image --}}
                        @if($vProp?->propertyType)
                            <span class="absolute top-3 left-3 bg-black/60 text-white text-[10px] font-semibold uppercase tracking-wider px-2 py-1 rounded-sm backdrop-blur-sm">
                                {{ $vProp->propertyType->name }}
                            </span>
                        @endif
                        {{-- Photo count if multiple --}}
                        @if(($vProp?->images?->count() ?? 0) > 1)
                            <span class="absolute bottom-3 right-3 bg-black/60 text-white text-[10px] px-2 py-1 rounded-sm backdrop-blur-sm">
                                +{{ $vProp->images->count() - 1 }} more photo{{ $vProp->images->count() > 2 ? 's' : '' }}
                            </span>
                        @endif
                    </div>

                    <div class="p-5 space-y-5">

                        {{-- Property info --}}
                        <div class="pb-5 border-b border-line">
                            <a href="{{ route('admin.property', $vProp->id) }}" wire:navigate
                               class="text-base font-semibold text-foreground font-serif hover:underline underline-offset-2 leading-snug">
                                {{ $vProp?->title ?? '—' }}
                            </a>
                            @if($vCity)
                                <p class="text-xs text-dim mt-0.5">
                                    {{ $vBrgy?->name ? $vBrgy->name . ', ' : '' }}{{ $vCity->name }}{{ $vProv ? ', ' . $vProv->name : '' }}
                                </p>
                            @endif

                            {{-- Key stats --}}
                            <div class="flex flex-wrap items-center gap-4 mt-3">
                                @if($vProp?->bedrooms !== null)
                                    <div class="flex items-center gap-1.5 text-xs text-dim">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9.5V19m0-9.5h18m-18 0V7a2 2 0 012-2h4a2 2 0 012 2v2.5m6-2.5V7a2 2 0 00-2-2h-4a2 2 0 00-2 2v2.5M3 19h18M3 19v-3a1 1 0 011-1h16a1 1 0 011 1v3"/></svg>
                                        <span><strong class="text-foreground">{{ $vProp->bedrooms }}</strong> {{ Str::plural('bedroom', $vProp->bedrooms) }}</span>
                                    </div>
                                @endif
                                @if($vProp?->bathrooms !== null)
                                    <div class="flex items-center gap-1.5 text-xs text-dim">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13h18M3 13v5a2 2 0 002 2h14a2 2 0 002-2v-5M3 13H2m1 0V9a5 5 0 015-5h1"/></svg>
                                        <span><strong class="text-foreground">{{ $vProp->bathrooms }}</strong> {{ Str::plural('bathroom', $vProp->bathrooms) }}</span>
                                    </div>
                                @endif
                                @if($vProp?->area)
                                    <div class="flex items-center gap-1.5 text-xs text-dim">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5a2 2 0 012-2h14a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2v-9z"/></svg>
                                        <span><strong class="text-foreground">{{ $vProp->area }}</strong> sqm</span>
                                    </div>
                                @endif
                                <div class="flex items-center gap-1.5 text-xs text-dim ml-auto">
                                    <span class="text-[10px] uppercase tracking-wider">Monthly rate</span>
                                    <span class="text-sm font-bold text-foreground font-serif">₱{{ number_format($vProp->price, 0) }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Two-column: Renter + Reservation dates --}}
                        <div class="grid grid-cols-2 gap-5 pb-5 border-b border-line">

                            {{-- Renter --}}
                            <div>
                                <p class="text-[10px] text-dim uppercase tracking-wider font-semibold mb-2">Renter</p>
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-foreground flex items-center justify-center flex-shrink-0">
                                        <span class="text-xs font-semibold text-on-primary font-serif">
                                            {{ strtoupper(substr($viewing->user?->name ?? '?', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-foreground">{{ $viewing->user?->name ?? '—' }}</p>
                                        <p class="text-[11px] text-dim">{{ $viewing->user?->email ?? '' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Submitted --}}
                            <div>
                                <p class="text-[10px] text-dim uppercase tracking-wider font-semibold mb-2">Submitted</p>
                                <p class="text-sm font-medium text-foreground">{{ $viewing->created_at->format('M d, Y') }}</p>
                                <p class="text-[11px] text-dim">{{ $viewing->created_at->format('g:i A') }}</p>
                            </div>

                            {{-- Move In --}}
                            <div>
                                <p class="text-[10px] text-dim uppercase tracking-wider font-semibold mb-2">Move-in Date</p>
                                <p class="text-sm font-medium text-foreground">{{ $viewing->move_in_date->format('F d, Y') }}</p>
                            </div>

                            {{-- Move Out --}}
                            <div>
                                <p class="text-[10px] text-dim uppercase tracking-wider font-semibold mb-2">Move-out Date</p>
                                @if($viewing->move_out_date)
                                    <p class="text-sm font-medium text-foreground">{{ $viewing->move_out_date->format('F d, Y') }}</p>
                                    @if($months)
                                        <p class="text-[11px] text-dim">{{ $months }} {{ Str::plural('month', $months) }}</p>
                                    @endif
                                @else
                                    <p class="text-sm text-dim">Not specified</p>
                                @endif
                            </div>
                        </div>

                        {{-- Total price --}}
                        <div class="flex items-center justify-between rounded-sm bg-subtle/50 border border-line px-4 py-3">
                            <div>
                                <p class="text-[10px] text-dim uppercase tracking-wider font-semibold">Total Price</p>
                                @if($months && $vProp?->price)
                                    <p class="text-[11px] text-dim mt-0.5">₱{{ number_format($vProp->price, 0) }}/mo × {{ $months }} {{ Str::plural('month', $months) }}</p>
                                @endif
                            </div>
                            <p class="text-2xl font-bold text-foreground font-serif">₱{{ number_format($viewing->total_price, 2) }}</p>
                        </div>

                        {{-- Notes --}}
                        @if($viewing->notes)
                            <div>
                                <p class="text-[10px] text-dim uppercase tracking-wider font-semibold mb-2">Renter Notes</p>
                                <div class="rounded-sm border border-line bg-subtle/30 px-4 py-3 text-sm text-foreground leading-relaxed">
                                    {{ $viewing->notes }}
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Footer actions --}}
                <div class="flex items-center justify-between px-5 py-3.5 border-t border-line bg-subtle/30 shrink-0">
                    <div class="text-[10px] text-dim">
                        @if($viewing->confirmed_at)
                            Confirmed {{ $viewing->confirmed_at->format('M d, Y') }}
                        @elseif($viewing->cancelled_at)
                            Cancelled {{ $viewing->cancelled_at->format('M d, Y') }}
                        @else
                            Awaiting admin action
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        @if($viewing->status === 'pending')
                            <button wire:click="askConfirm({{ $viewing->id }}, 'confirm')"
                                    class="inline-flex items-center gap-1.5 rounded-sm bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Confirm
                            </button>
                            <button wire:click="askConfirm({{ $viewing->id }}, 'cancel')"
                                    class="inline-flex items-center gap-1.5 rounded-sm bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Cancel
                            </button>
                        @elseif($viewing->status === 'confirmed')
                            <button wire:click="askConfirm({{ $viewing->id }}, 'complete')"
                                    class="inline-flex items-center gap-1.5 rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Mark Complete
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

    {{-- Confirm Action Modal --}}
    @if($confirmingId)
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-foreground/40 backdrop-blur-sm" wire:click="dismissConfirm"></div>
            <div class="relative w-full max-w-sm rounded-sm border border-line bg-card" style="box-shadow: var(--shadow-lg);">
                <div class="p-6 text-center">
                    <svg class="h-10 w-10 mx-auto mb-3 {{ $confirmingAction === 'cancel' ? 'text-red-500' : 'text-green-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <h3 class="text-base font-semibold text-foreground font-serif mb-1">{{ ucfirst($confirmingAction) }} Reservation</h3>
                    <p class="text-xs text-dim mb-5">Are you sure you want to {{ $confirmingAction }} this reservation?</p>
                    <div class="flex justify-center gap-3">
                        <button wire:click="dismissConfirm"
                                class="rounded-sm border border-line px-4 py-2 text-sm font-medium text-dim hover:text-foreground hover:bg-subtle transition-colors">
                            Cancel
                        </button>
                        <button wire:click="executeAction"
                                class="rounded-sm px-4 py-2 text-sm font-medium text-white transition-colors {{ $confirmingAction === 'cancel' ? 'bg-red-600 hover:bg-red-700' : 'bg-foreground hover:opacity-90' }}">
                            {{ ucfirst($confirmingAction) }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
