<div>
    <x-slot:header>
        <span class="font-medium text-foreground">Reservations</span>
    </x-slot:header>

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-foreground font-serif tracking-tight">Reservations</h1>
        <p class="text-xs text-dim mt-1">Review and manage all property reservations.</p>
    </div>

    <div class="rounded-sm border border-line bg-card" style="box-shadow: var(--shadow-xs);">
        {{-- Toolbar --}}
        <div class="p-4 border-b border-line flex flex-col sm:flex-row sm:items-center gap-3">
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Search by property or renter..."
                   class="w-full sm:max-w-xs rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground placeholder-dim/50 focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground" />

            <div class="flex items-center gap-2 sm:ml-auto">
                <select wire:model.live="filterStatus"
                        class="rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="completed">Completed</option>
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
                            <td class="px-4 py-3 font-medium text-foreground max-w-[160px] truncate">
                                {{ $reservation->property->title ?? '—' }}
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
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-foreground/40 backdrop-blur-sm" wire:click="closeView"></div>
            <div class="relative w-full max-w-lg rounded-sm border border-line bg-card overflow-hidden" style="box-shadow: var(--shadow-lg);">
                <div class="flex items-center justify-between p-4 border-b border-line">
                    <h3 class="text-base font-semibold text-foreground font-serif tracking-tight">Reservation #{{ $viewing->id }}</h3>
                    <button wire:click="closeView" class="text-dim hover:text-foreground transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="block text-[10px] text-dim uppercase tracking-wider mb-0.5">Property</span>
                            <span class="font-medium text-foreground">{{ $viewing->property->title ?? '—' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-dim uppercase tracking-wider mb-0.5">Renter</span>
                            <span class="font-medium text-foreground">{{ $viewing->user->name ?? '—' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-dim uppercase tracking-wider mb-0.5">Move In</span>
                            <span class="text-foreground">{{ $viewing->move_in_date->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-dim uppercase tracking-wider mb-0.5">Move Out</span>
                            <span class="text-foreground">{{ $viewing->move_out_date?->format('M d, Y') ?? '—' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-dim uppercase tracking-wider mb-0.5">Total Price</span>
                            <span class="font-semibold text-foreground font-serif">&#8369;{{ number_format($viewing->total_price, 2) }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-dim uppercase tracking-wider mb-0.5">Status</span>
                            @php
                                $vBadge = match($viewing->status) {
                                    'pending'   => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400',
                                    'confirmed' => 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                                    'cancelled' => 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                                    'completed' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
                                    default     => 'bg-subtle text-dim',
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-sm px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider {{ $vBadge }}">
                                {{ ucfirst($viewing->status) }}
                            </span>
                        </div>
                    </div>
                    @if($viewing->notes)
                        <div class="pt-3 border-t border-line">
                            <span class="block text-[10px] text-dim uppercase tracking-wider mb-1">Notes</span>
                            <p class="text-sm text-foreground">{{ $viewing->notes }}</p>
                        </div>
                    @endif
                </div>
                <div class="flex justify-end gap-2 p-4 border-t border-line">
                    @if($viewing->status === 'pending')
                        <button wire:click="askConfirm({{ $viewing->id }}, 'confirm')"
                                class="rounded-sm bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition-colors">Confirm</button>
                        <button wire:click="askConfirm({{ $viewing->id }}, 'cancel')"
                                class="rounded-sm bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition-colors">Cancel</button>
                    @elseif($viewing->status === 'confirmed')
                        <button wire:click="askConfirm({{ $viewing->id }}, 'complete')"
                                class="rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition-colors">Complete</button>
                    @endif
                    <button wire:click="closeView"
                            class="rounded-sm border border-line px-4 py-2 text-sm font-medium text-dim hover:text-foreground hover:bg-subtle transition-colors">Close</button>
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
