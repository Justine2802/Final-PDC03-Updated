<div>
    <x-slot:header>
        <span class="font-medium text-foreground">Reviews</span>
    </x-slot:header>

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-foreground font-serif tracking-tight">Reviews</h1>
        <p class="text-xs text-dim mt-1">Manage property reviews and ratings.</p>
    </div>

    <div class="rounded-sm border border-line bg-card" x-data="{ showFilters: false }" style="box-shadow: var(--shadow-xs);">
        {{-- Toolbar --}}
        <div class="p-4 border-b border-line flex flex-col sm:flex-row sm:items-center gap-3">
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Search by property, renter or comment..."
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
                    <label class="block text-xs font-medium text-dim mb-1">Rating</label>
                    <select wire:model.live="filterRating"
                            class="w-full rounded-sm border border-line bg-card px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground">
                        <option value="">All Ratings</option>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-dim mb-1">Verification</label>
                    <select wire:model.live="filterVerified"
                            class="w-full rounded-sm border border-line bg-card px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground">
                        <option value="">All</option>
                        <option value="1">Verified</option>
                        <option value="0">Unverified</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-dim mb-1">Date From</label>
                    <input type="date" wire:model.live="dateFrom"
                           class="w-full rounded-sm border border-line bg-card px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-dim mb-1">Date To</label>
                    <input type="date" wire:model.live="dateTo"
                           class="w-full rounded-sm border border-line bg-card px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground" />
                </div>
            </div>
            @if($activeFilterCount > 0)
                <div class="mt-3">
                    <button wire:click="$set('filterRating', ''); $set('filterVerified', ''); $set('dateFrom', ''); $set('dateTo', '')"
                            class="text-sm text-dim hover:text-foreground underline underline-offset-2">
                        Clear all filters
                    </button>
                </div>
            @endif
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto transition-opacity duration-200" wire:loading.class="opacity-50 pointer-events-none">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-line bg-subtle/50">
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">#</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Property</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Renter</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Rating</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Comment</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Pros</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Cons</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Verified</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Date</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-subtle/50 transition-colors">
                            <td class="px-4 py-3 text-dim">{{ $review->id }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2.5 max-w-[200px]">
                                    <div class="w-9 h-9 rounded-sm overflow-hidden bg-subtle flex-shrink-0">
                                        @if($review->property?->images?->first())
                                            <img src="{{ asset('storage/' . $review->property->images->first()->image_path) }}"
                                                 class="w-full h-full object-cover" alt="">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-dim/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <span class="font-medium text-foreground text-sm truncate">{{ $review->property->title ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-foreground">{{ $review->renter->name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-3.5 w-3.5 {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200 dark:text-gray-700' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </td>
                            <td class="px-4 py-3 text-dim max-w-[180px] truncate">{{ $review->comment ?? '—' }}</td>
                            <td class="px-4 py-3 text-green-600 dark:text-green-400 max-w-[140px] truncate text-xs">{{ is_array($review->pros) ? implode(', ', $review->pros) : ($review->pros ?? '—') }}</td>
                            <td class="px-4 py-3 text-red-500 dark:text-red-400 max-w-[140px] truncate text-xs">{{ is_array($review->cons) ? implode(', ', $review->cons) : ($review->cons ?? '—') }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-sm px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider {{ $review->is_verified ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-subtle text-dim' }}">
                                    {{ $review->is_verified ? 'Verified' : 'Unverified' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-dim">{{ $review->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <button wire:click="viewReview({{ $review->id }})"
                                            class="text-xs text-dim hover:text-foreground font-medium transition-colors">View</button>
                                    <button wire:click="toggleVerified({{ $review->id }})"
                                            class="text-xs {{ $review->is_verified ? 'text-yellow-600 hover:text-yellow-800' : 'text-green-600 hover:text-green-800' }} font-medium transition-colors">
                                        {{ $review->is_verified ? 'Unverify' : 'Verify' }}
                                    </button>
                                    <button wire:click="deleteReview({{ $review->id }})"
                                            class="text-xs text-red-500 hover:text-red-700 font-medium transition-colors">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-8 text-center text-dim">No reviews found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-line">
            {{ $reviews->links() }}
        </div>
    </div>

    {{-- View Modal --}}
    @if($viewing)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-foreground/40 backdrop-blur-sm" wire:click="closeView"></div>
            <div class="relative w-full max-w-md rounded-sm border border-line bg-card overflow-hidden" style="box-shadow: var(--shadow-lg);">
                <div class="flex items-center justify-between p-4 border-b border-line">
                    <h3 class="text-base font-semibold text-foreground font-serif tracking-tight">Review #{{ $viewing->id }}</h3>
                    <button wire:click="closeView" class="text-dim hover:text-foreground transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="block text-[10px] text-dim uppercase tracking-wider mb-0.5">Property</span>
                            <span class="font-medium text-foreground">{{ $viewing->property->title ?? '—' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-dim uppercase tracking-wider mb-0.5">Renter</span>
                            <span class="font-medium text-foreground">{{ $viewing->renter->name ?? '—' }}</span>
                        </div>
                    </div>
                    <div>
                        <span class="block text-[10px] text-dim uppercase tracking-wider mb-1">Rating</span>
                        <div class="flex items-center gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="h-4 w-4 {{ $i <= $viewing->rating ? 'text-amber-400' : 'text-gray-200 dark:text-gray-700' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                            <span class="ml-1 text-sm text-foreground font-medium">{{ $viewing->rating }}/5</span>
                        </div>
                    </div>
                    <div>
                        <span class="block text-[10px] text-dim uppercase tracking-wider mb-1">Comment</span>
                        <p class="text-sm text-foreground bg-subtle/50 border border-line rounded-sm p-3">{{ $viewing->comment ?? 'No comment provided.' }}</p>
                    </div>
                    @if($viewing->pros || $viewing->cons)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <span class="block text-[10px] text-dim uppercase tracking-wider mb-1">Pros</span>
                                <div class="text-sm text-foreground bg-green-50 dark:bg-green-500/5 border border-green-200 dark:border-green-500/20 rounded-sm p-3">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-3.5 h-3.5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        <span class="text-green-700 dark:text-green-400">{{ is_array($viewing->pros) ? implode(', ', $viewing->pros) : ($viewing->pros ?? 'None listed') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <span class="block text-[10px] text-dim uppercase tracking-wider mb-1">Cons</span>
                                <div class="text-sm text-foreground bg-red-50 dark:bg-red-500/5 border border-red-200 dark:border-red-500/20 rounded-sm p-3">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-3.5 h-3.5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        <span class="text-red-700 dark:text-red-400">{{ is_array($viewing->cons) ? implode(', ', $viewing->cons) : ($viewing->cons ?? 'None listed') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="block text-[10px] text-dim uppercase tracking-wider mb-0.5">Status</span>
                            <span class="inline-flex items-center rounded-sm px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider {{ $viewing->is_verified ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-subtle text-dim' }}">
                                {{ $viewing->is_verified ? 'Verified' : 'Unverified' }}
                            </span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-dim uppercase tracking-wider mb-0.5">Date</span>
                            <span class="text-foreground">{{ $viewing->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between p-4 border-t border-line">
                    <div class="flex items-center gap-2">
                        <button wire:click="toggleVerified({{ $viewing->id }})"
                                class="rounded-sm bg-foreground px-3 py-1.5 text-xs font-medium text-on-primary hover:opacity-90 transition-all">
                            {{ $viewing->is_verified ? 'Unverify' : 'Verify' }}
                        </button>
                        <button wire:click="deleteReview({{ $viewing->id }})"
                                class="rounded-sm bg-red-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-700 transition-colors">Delete</button>
                    </div>
                    <button wire:click="closeView"
                            class="rounded-sm border border-line px-4 py-1.5 text-xs font-medium text-dim hover:text-foreground hover:bg-subtle transition-colors">Close</button>
                </div>
            </div>
        </div>
    @endif
</div>
