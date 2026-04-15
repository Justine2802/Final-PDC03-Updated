<div>
    {{-- Quick Action Button --}}
    <div class="flex justify-end mb-8">
        <a href="{{ route('renter.explore') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-md hover:bg-primary/90 font-medium text-sm transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            Browse Properties
        </a>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-7 mb-12">
        <div class="rounded-lg border border-line bg-card p-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-dim uppercase tracking-widest">Total Reservations</p>
                    <p class="mt-4 text-5xl font-extrabold text-foreground">{{ $reservationCount }}</p>
                </div>
                <div class="flex h-16 w-16 items-center justify-center rounded-lg bg-subtle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-lg border border-line bg-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-dim">Favorites</p>
                    <p class="mt-2 text-2xl font-semibold text-foreground">{{ $favoriteCount }}</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-subtle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-1.085-.979-2.25-2.25-2.25h-1.5a2.25 2.25 0 00-1.5.62l-.68-.68a1.5 1.5 0 00-1.06-.44h-.5a1.5 1.5 0 00-1.06.44l-.82.82a2.25 2.25 0 00-.5 1.06V15M12 14.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-lg border border-line bg-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-dim">Active Inquiries</p>
                    <p class="mt-2 text-2xl font-semibold text-foreground">{{ $inquiryCount }}</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-subtle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-lg border border-line bg-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-dim">Account Status</p>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 mt-2 text-xs font-medium bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400">
                        {{ ucfirst(auth()->user()->status) }}
                    </span>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-subtle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>


    {{-- Recent Reservations --}}
    <div class="bg-card border border-line rounded-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-foreground">Recent Reservations</h2>
                <a href="{{ route('renter.explore') }}" class="text-primary text-sm hover:underline font-medium">View All</a>
            </div>

            @if($recentReservations->count() > 0)
                <div class="space-y-5">
                    @foreach($recentReservations as $reservation)
                        <div class="flex items-start justify-between pb-4 border-b border-line last:border-0">
                            <div class="flex gap-4 flex-1">
                                @if($reservation->property->images->first())
                                    <img src="{{ asset('storage/' . $reservation->property->images->first()->image_path) }}" 
                                         alt="{{ $reservation->property->title }}"
                                         class="w-16 h-16 object-cover rounded-md">
                                @else
                                    <div class="w-16 h-16 bg-line rounded-md"></div>
                                @endif
                                <div class="flex-1">
                                    <h3 class="font-semibold text-foreground">{{ $reservation->property->title }}</h3>
                                    <p class="text-sm text-dim">{{ $reservation->property->propertyType->name ?? 'Property' }}</p>
                                    <p class="text-sm text-dim mt-1">
                                        {{ $reservation->move_in_date->format('M d, Y') }} - 
                                        @if($reservation->move_out_date)
                                            {{ $reservation->move_out_date->format('M d, Y') }}
                                        @else
                                            Ongoing
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-primary">${{ $reservation->total_price }}</p>
                                <span class="inline-block px-2 py-1 text-xs font-medium rounded mt-2
                                    @if($reservation->status === 'pending')
                                        bg-yellow-100 text-yellow-800
                                    @elseif($reservation->status === 'confirmed')
                                        bg-green-100 text-green-800
                                    @elseif($reservation->status === 'cancelled')
                                        bg-red-100 text-red-800
                                    @else
                                        bg-blue-100 text-blue-800
                                    @endif
                                ">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-dim py-12 text-base">No reservations yet. <a href="{{ route('renter.explore') }}" class="text-primary hover:underline font-semibold">Start exploring</a></p>
            @endif
        </div>

    {{-- Quick Links --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('renter.explore') }}" class="bg-card border border-line rounded-lg p-6 hover:border-primary hover:shadow-lg transition-all">
                <div class="flex items-center gap-4">
                    <svg class="w-8 h-8 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <div>
                        <p class="font-bold text-foreground">Explore Properties</p>
                        <p class="text-sm text-dim">Find your next home</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('renter.favorites') }}" class="bg-card border border-line rounded-lg p-6 hover:border-primary hover:shadow-lg transition-all">
                <div class="flex items-center gap-4">
                    <svg class="w-8 h-8 text-primary flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M11.645 20.761A.75.75 0 0012 20.25v-3.995a.75.75 0 00-1.5 0v3.745a.75.75 0 00.75.75z"></path>
                    </svg>
                    <div>
                        <p class="font-bold text-foreground">My Favorites</p>
                        <p class="text-sm text-dim">{{ $favoriteCount }} saved properties</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('renter.profile') }}" class="bg-card border border-line rounded-lg p-6 hover:border-primary hover:shadow-lg transition-all">
                <div class="flex items-center gap-4">
                    <svg class="w-8 h-8 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <div>
                        <p class="font-bold text-foreground">My Profile</p>
                        <p class="text-sm text-dim">Manage your account</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Inquiry Form Modal --}}
    @if($selectedProperty)
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" wire:click.self="clearProperty">
            {{-- Modal Container --}}
            <div class="bg-white dark:bg-foreground rounded-2xl shadow-2xl max-w-2xl w-full" @click.stop>
                {{-- Close Button --}}
                <button wire:click="clearProperty" class="absolute top-4 right-4 z-10 p-2 bg-white dark:bg-card rounded-full hover:bg-gray-100 dark:hover:bg-line transition-colors" style="background-color: white; border: 1px solid #e5e7eb;">
                    <svg class="w-6 h-6 text-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <div class="p-10">
                    {{-- Header --}}
                    <div class="mb-10">
                        <h2 class="text-2xl font-bold text-foreground mb-1">Send an Inquiry</h2>
                        <p class="text-sm text-dim">{{ $selectedProperty->title }}</p>
                    </div>

                    {{-- Property Preview --}}
                    <div class="flex gap-5 mb-8 p-5 bg-card border border-line rounded-lg">
                        @if($selectedProperty->images->first())
                            <img src="{{ asset('storage/' . $selectedProperty->images->first()->image_path) }}" 
                                 alt="{{ $selectedProperty->title }}"
                                 class="w-24 h-24 object-cover rounded-lg">
                        @else
                            <div class="w-24 h-24 bg-line rounded-lg"></div>
                        @endif
                        <div>
                            <h3 class="font-bold text-foreground text-lg">{{ $selectedProperty->title }}</h3>
                            <p class="text-sm text-dim mt-1">{{ $selectedProperty->propertyType->name ?? 'Property' }}</p>
                            <p class="text-xl font-bold text-primary mt-3">${{ number_format($selectedProperty->price, 0) }}/month</p>
                        </div>
                    </div>

                    {{-- Form --}}
                    <form wire:submit="submitInquiry" class="space-y-6">
                        <div>
                            <label for="inquiryMessage" class="block text-sm font-semibold text-foreground mb-2">Your Message</label>
                            <textarea wire:model="inquiryMessage" 
                                      id="inquiryMessage"
                                      placeholder="Tell the property owner why you're interested in this property..." 
                                      rows="5"
                                      class="w-full px-3 py-2 rounded-md border border-line bg-card text-foreground placeholder-dim focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary/50 text-sm">
                            </textarea>
                            @error('inquiryMessage')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-3 pt-4\">
                            <button type="submit" class="flex-1 py-2.5 px-4 bg-primary text-on-primary rounded-md hover:bg-primary/90 font-semibold text-sm transition-colors">
                                Send Inquiry
                            </button>
                            <button type="button" wire:click="clearProperty" class="flex-1 py-2.5 px-4 border border-line text-foreground rounded-md hover:bg-subtle font-medium text-sm transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
