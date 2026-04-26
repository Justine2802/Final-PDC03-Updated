<div>
    {{-- Hero Banner (full-bleed) --}}
    <div class="relative overflow-hidden -mx-5 -mt-5 mb-8 lg:-mx-8 lg:-mt-8" style="box-shadow: 0 4px 24px rgba(0,0,0,.12);">
        <div class="relative h-64 md:h-72 lg:h-80">
            {{-- Background Image --}}
            <img src="{{ asset('images/hero-banner.png') }}" alt="Find your perfect home" class="absolute inset-0 w-full h-full object-cover">
            
            {{-- Gradient Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent"></div>
            
            {{-- Content --}}
            <div class="relative z-10 flex flex-col justify-center h-full px-8 lg:px-12 max-w-2xl">
                <p class="text-[10px] font-semibold text-white/60 uppercase tracking-[0.2em] mb-2">Welcome back</p>
                <h1 class="text-3xl lg:text-4xl font-semibold text-white font-serif tracking-tight leading-tight">
                    Find your perfect<br>place to call home
                </h1>
                <p class="text-sm text-white/70 mt-3 leading-relaxed max-w-md">
                    Discover curated rental properties that match your lifestyle. Browse, save, and reserve — all in one place.
                </p>
                <div class="flex items-center gap-3 mt-6">
                    <a href="{{ route('renter.explore') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-foreground rounded-sm hover:bg-white/90 font-medium text-sm transition-all" style="box-shadow: var(--shadow-xs);">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Browse Properties
                    </a>
                    <a href="{{ route('renter.favorites') }}" class="inline-flex items-center gap-2 px-5 py-2.5 border border-white/30 text-white rounded-sm hover:bg-white/10 font-medium text-sm transition-all">
                        My Favourites
                    </a>
                </div>
            </div>

            {{-- Greeting Badge --}}
            <div class="absolute bottom-6 right-8 bg-white/10 backdrop-blur-md border border-white/20 rounded-sm px-4 py-3 hidden lg:block">
                <p class="text-xs text-white/80 font-medium">Signed in as</p>
                <p class="text-sm text-white font-semibold font-serif tracking-tight mt-0.5">{{ auth()->user()->name }}</p>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
        {{-- Reservations --}}
        <a href="{{ route('renter.reservations') }}" class="group border border-line bg-card rounded-sm p-5 hover:border-foreground/30 transition-all" style="box-shadow: var(--shadow-xs);">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-semibold text-dim uppercase tracking-[0.12em]">Reservations</p>
                    <p class="mt-2 text-3xl font-bold text-foreground font-serif tracking-tight">{{ $reservationCount }}</p>
                </div>
                <div class="w-9 h-9 flex items-center justify-center rounded-sm bg-subtle group-hover:bg-foreground/5 transition-colors">
                    <svg class="h-4 w-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5" />
                    </svg>
                </div>
            </div>
        </a>

        {{-- Favourites --}}
        <a href="{{ route('renter.favorites') }}" class="group border border-line bg-card rounded-sm p-5 hover:border-foreground/30 transition-all" style="box-shadow: var(--shadow-xs);">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-semibold text-dim uppercase tracking-[0.12em]">Favourites</p>
                    <p class="mt-2 text-3xl font-bold text-foreground font-serif tracking-tight">{{ $favoriteCount }}</p>
                </div>
                <div class="w-9 h-9 flex items-center justify-center rounded-sm bg-subtle group-hover:bg-foreground/5 transition-colors">
                    <svg class="h-4 w-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
            </div>
        </a>

        {{-- Inquiries --}}
        <a href="{{ route('renter.inquiries') }}" class="group border border-line bg-card rounded-sm p-5 hover:border-foreground/30 transition-all" style="box-shadow: var(--shadow-xs);">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-semibold text-dim uppercase tracking-[0.12em]">Active Inquiries</p>
                    <p class="mt-2 text-3xl font-bold text-foreground font-serif tracking-tight">{{ $inquiryCount }}</p>
                </div>
                <div class="w-9 h-9 flex items-center justify-center rounded-sm bg-subtle group-hover:bg-foreground/5 transition-colors">
                    <svg class="h-4 w-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
            </div>
        </a>

        {{-- Account Status --}}
        <div class="border border-line bg-card rounded-sm p-5" style="box-shadow: var(--shadow-xs);">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-semibold text-dim uppercase tracking-[0.12em]">Account Status</p>
                    <span class="inline-flex items-center rounded-sm px-2 py-0.5 mt-2.5 text-[10px] font-semibold uppercase tracking-wider bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400">
                        {{ ucfirst(auth()->user()->status) }}
                    </span>
                </div>
                <div class="w-9 h-9 flex items-center justify-center rounded-sm bg-subtle">
                    <svg class="h-4 w-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Reservations --}}
    <div class="bg-card border border-line rounded-sm p-5 mb-6" style="box-shadow: var(--shadow-xs);">
        <div class="flex items-center justify-between mb-5 pb-3 border-b border-line">
            <h2 class="text-sm font-semibold text-foreground font-serif tracking-tight">Recent Reservations</h2>
            <a href="{{ route('renter.reservations') }}" class="text-xs text-foreground hover:underline underline-offset-4 font-medium">View All →</a>
        </div>

        @if($recentReservations->count() > 0)
            <div class="space-y-0 divide-y divide-line">
                @foreach($recentReservations as $reservation)
                    <div class="flex items-center justify-between py-3.5 first:pt-0 last:pb-0">
                        <div class="flex gap-3 flex-1 items-center">
                            @if($reservation->property && $reservation->property->images && $reservation->property->images->first())
                                <img src="{{ asset('storage/' . $reservation->property->images->first()->image_path) }}"
                                     alt="{{ $reservation->property->title }}"
                                     class="w-12 h-12 object-cover rounded-sm flex-shrink-0">
                            @else
                                <div class="w-12 h-12 bg-subtle rounded-sm flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-dim/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-3m0 0l7-4 7 4M5 7v10a1 1 0 001 1h12a1 1 0 001-1V7"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <h3 class="font-semibold text-foreground text-sm font-serif tracking-tight truncate">{{ $reservation->property->title ?? 'Property' }}</h3>
                                <p class="text-[10px] text-accent uppercase tracking-wider">{{ $reservation->property->propertyType->name ?? 'Property' }}</p>
                                <p class="text-[10px] text-dim mt-0.5">
                                    {{ $reservation->move_in_date->format('M d, Y') }} —
                                    {{ $reservation->move_out_date ? $reservation->move_out_date->format('M d, Y') : 'Ongoing' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 ml-4">
                            <p class="text-base font-bold text-foreground font-serif">₱{{ number_format($reservation->total_price, 2) }}</p>
                            <span class="inline-block px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider rounded-sm mt-1
                                @if($reservation->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-500/10 dark:text-yellow-400
                                @elseif($reservation->status === 'confirmed') bg-green-100 text-green-800 dark:bg-green-500/10 dark:text-green-400
                                @elseif($reservation->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-500/10 dark:text-red-400
                                @else bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-400
                                @endif">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-10">
                <svg class="w-10 h-10 mx-auto text-dim/20 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5"/>
                </svg>
                <p class="text-sm text-dim">No reservations yet. <a href="{{ route('renter.explore') }}" class="text-foreground hover:underline underline-offset-4 font-medium">Start exploring</a></p>
            </div>
        @endif
    </div>

    {{-- Quick Links --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('renter.explore') }}" class="group border border-line bg-card rounded-sm p-4 hover:border-foreground/30 transition-all" style="box-shadow: var(--shadow-xs);">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 flex items-center justify-center rounded-sm bg-subtle group-hover:bg-foreground/5 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-foreground text-sm font-serif tracking-tight">Explore Properties</p>
                    <p class="text-[10px] text-dim mt-0.5">Find your next home</p>
                </div>
            </div>
        </a>
        <a href="{{ route('renter.favorites') }}" class="group border border-line bg-card rounded-sm p-4 hover:border-foreground/30 transition-all" style="box-shadow: var(--shadow-xs);">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 flex items-center justify-center rounded-sm bg-subtle group-hover:bg-foreground/5 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-foreground text-sm font-serif tracking-tight">My Favourites</p>
                    <p class="text-[10px] text-dim mt-0.5">{{ $favoriteCount }} saved properties</p>
                </div>
            </div>
        </a>
        <a href="{{ route('renter.profile') }}" class="group border border-line bg-card rounded-sm p-4 hover:border-foreground/30 transition-all" style="box-shadow: var(--shadow-xs);">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 flex items-center justify-center rounded-sm bg-subtle group-hover:bg-foreground/5 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-foreground text-sm font-serif tracking-tight">My Profile</p>
                    <p class="text-[10px] text-dim mt-0.5">Manage your account</p>
                </div>
            </div>
        </a>
    </div>

    {{-- Inquiry Form Modal --}}
    @if($selectedProperty)
        <div class="fixed inset-0 bg-foreground/40 backdrop-blur-sm z-50 flex items-center justify-center p-4" wire:click.self="clearProperty">
            <div class="bg-card border border-line rounded-sm max-w-lg w-full relative" @click.stop style="box-shadow: var(--shadow-lg);">
                <button wire:click="clearProperty" class="absolute top-4 right-4 z-10 w-8 h-8 flex items-center justify-center bg-card border border-line rounded-sm text-dim hover:text-foreground hover:bg-subtle transition-colors" style="box-shadow: var(--shadow-xs);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <div class="p-6">
                    <div class="mb-5 pb-4 border-b border-line">
                        <h2 class="text-lg font-semibold text-foreground font-serif tracking-tight">Send an Inquiry</h2>
                        <p class="text-xs text-dim mt-0.5">{{ $selectedProperty->title }}</p>
                    </div>

                    {{-- Property Preview --}}
                    <div class="flex gap-3 mb-5 p-3 bg-subtle border border-line rounded-sm">
                        @if($selectedProperty->images && $selectedProperty->images->first())
                            <img src="{{ asset('storage/' . $selectedProperty->images->first()->image_path) }}"
                                 alt="{{ $selectedProperty->title }}"
                                 class="w-16 h-16 object-cover rounded-sm flex-shrink-0">
                        @else
                            <div class="w-16 h-16 bg-subtle rounded-sm flex items-center justify-center flex-shrink-0 border border-line">
                                <svg class="w-6 h-6 text-dim/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-3m0 0l7-4 7 4M5 7v10a1 1 0 001 1h12a1 1 0 001-1V7"/>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-semibold text-foreground text-sm font-serif tracking-tight">{{ $selectedProperty->title }}</h3>
                            <p class="text-[10px] text-accent uppercase tracking-wider mt-0.5">{{ $selectedProperty->propertyType->name ?? 'Property' }}</p>
                            <p class="text-sm font-bold text-foreground mt-1.5 font-serif">₱{{ number_format($selectedProperty->price, 0) }}<span class="text-xs font-normal text-dim font-sans">/month</span></p>
                        </div>
                    </div>

                    <form wire:submit="submitInquiry" class="space-y-4">
                        <div>
                            <label for="inquiryMessage" class="block text-xs font-medium text-dim mb-1.5 uppercase tracking-wider">Your Message</label>
                            <textarea wire:model="inquiryMessage"
                                      id="inquiryMessage"
                                      placeholder="Tell the property owner why you're interested..."
                                      rows="4"
                                      class="w-full px-3 py-2 rounded-sm border border-line bg-page text-foreground placeholder-dim/50 text-sm focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground resize-none">
                            </textarea>
                            @error('inquiryMessage')
                                <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-2.5 pt-1">
                            <button type="submit" class="flex-1 py-2.5 px-4 bg-foreground text-on-primary rounded-sm hover:opacity-90 font-medium text-sm transition-all">
                                Send Inquiry
                            </button>
                            <button type="button" wire:click="clearProperty" class="flex-1 py-2.5 px-4 border border-line text-dim rounded-sm hover:text-foreground hover:border-foreground font-medium text-sm transition-all">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
