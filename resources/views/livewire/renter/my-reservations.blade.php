<div>
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-foreground font-serif tracking-tight">My Reservations</h2>
                <p class="text-xs text-dim mt-1">Track and manage your property reservations.</p>
            </div>

            <select wire:model.live="statusFilter"
                    class="rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="cancelled">Cancelled</option>
                <option value="completed">Completed</option>
            </select>
        </div>

        {{-- Reservations Grid --}}
        @if($reservations->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8">
                @foreach($reservations as $reservation)
                    @if(!$reservation->property) @continue @endif
                    <div class="bg-card border border-line rounded-sm overflow-hidden hover:border-foreground/30 transition-all" style="box-shadow: var(--shadow-xs);">
                        <div class="flex gap-0">
                            {{-- Property Image --}}
                            <div class="w-32 flex-shrink-0 bg-subtle relative overflow-hidden">
                                @if($reservation->property->images && $reservation->property->images->first())
                                    <img src="{{ asset('storage/' . $reservation->property->images->first()->image_path) }}"
                                         alt="{{ $reservation->property->title }}"
                                         class="w-full h-full object-cover absolute inset-0">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-subtle absolute inset-0">
                                        <svg class="w-8 h-8 text-dim/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-3m0 0l7-4 7 4M5 7v10a1 1 0 001 1h12a1 1 0 001-1V7m-9 10l-2-1m0 0l-2 1m2-1v-5"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Details --}}
                            <div class="flex-1 p-4">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <div>
                                        <h3 class="font-semibold text-foreground text-sm font-serif tracking-tight leading-tight">{{ $reservation->property->title }}</h3>
                                        <p class="text-[10px] text-accent uppercase tracking-wider mt-0.5">{{ $reservation->property->propertyType->name ?? 'Property' }}</p>
                                    </div>
                                    <span class="flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded-sm text-[10px] font-semibold uppercase tracking-wider
                                        @if($reservation->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-500/10 dark:text-yellow-400
                                        @elseif($reservation->status === 'confirmed') bg-green-100 text-green-800 dark:bg-green-500/10 dark:text-green-400
                                        @elseif($reservation->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-500/10 dark:text-red-400
                                        @else bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-400
                                        @endif">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-1.5 text-xs text-dim mb-2">
                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5"></path>
                                    </svg>
                                    <span>{{ $reservation->move_in_date->format('M d, Y') }}</span>
                                    <span>→</span>
                                    <span>{{ $reservation->move_out_date ? $reservation->move_out_date->format('M d, Y') : 'Ongoing' }}</span>
                                </div>

                                <div class="flex items-center justify-between mt-2 pt-2 border-t border-line">
                                    <p class="text-base font-bold text-foreground font-serif">₱{{ number_format($reservation->total_price, 2) }}</p>

                                    @if(in_array($reservation->status, ['pending', 'confirmed']))
                                        <button wire:click="confirmCancel({{ $reservation->id }})"
                                                class="text-[10px] font-semibold text-red-500 hover:text-red-700 border border-red-200 hover:border-red-400 px-2.5 py-1 rounded-sm transition-colors uppercase tracking-wider">
                                            Cancel
                                        </button>
                                    @endif
                                </div>

                                @if($reservation->notes)
                                    <p class="text-[10px] text-dim mt-2 italic line-clamp-1">Note: {{ $reservation->notes }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div>{{ $reservations->links() }}</div>
        @else
            <div class="text-center py-16 px-8 bg-card border border-line rounded-sm">
                <svg class="w-12 h-12 mx-auto text-dim/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5"></path>
                </svg>
                <p class="text-base font-semibold text-foreground font-serif">No reservations yet</p>
                <p class="text-xs text-dim mt-2">Browse available properties and make your first reservation</p>
                <a href="{{ route('renter.explore') }}"
                   class="inline-block mt-6 px-6 py-2 bg-foreground text-on-primary rounded-sm hover:opacity-90 font-medium text-sm transition-all">
                    Explore Properties
                </a>
            </div>
        @endif
    </div>

    {{-- Cancel Confirmation Modal --}}
    @if($cancellingId)
        <div class="fixed inset-0 bg-foreground/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-card border border-line rounded-sm max-w-md w-full p-6" style="box-shadow: var(--shadow-lg);">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center w-12 h-12 rounded-sm bg-red-50 dark:bg-red-500/10 mb-4">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-foreground font-serif mb-1">Cancel Reservation?</h3>
                    <p class="text-xs text-dim">This action cannot be undone. Are you sure you want to cancel this reservation?</p>
                </div>
                <div class="flex gap-2.5 mt-6">
                    <button wire:click="cancelReservation"
                            class="flex-1 py-2.5 px-4 bg-red-500 text-white rounded-sm hover:bg-red-600 font-medium text-sm transition-colors">
                        Yes, Cancel It
                    </button>
                    <button wire:click="dismissCancel"
                            class="flex-1 py-2.5 px-4 border border-line text-dim rounded-sm hover:text-foreground hover:border-foreground font-medium text-sm transition-all">
                        Keep Reservation
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
