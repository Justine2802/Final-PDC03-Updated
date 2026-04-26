<div>
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h2 class="text-xl font-semibold text-foreground font-serif tracking-tight">My Favourites</h2>
            <p class="text-xs text-dim mt-1">Properties you've saved for later.</p>
        </div>

        {{-- Favorites Grid --}}
        @if($favorites->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($favorites as $favorite)
                    @if(!$favorite->property)
                        @continue
                    @endif
                    <div class="border border-line bg-card rounded-sm overflow-hidden hover:border-foreground/30 transition-all duration-200" style="box-shadow: var(--shadow-xs);">
                        {{-- Image --}}
                        <div class="relative h-56 bg-subtle overflow-hidden group">
                            @if($favorite->property->images && $favorite->property->images->first())
                                <img src="{{ asset('storage/' . $favorite->property->images->first()->image_path) }}" 
                                     alt="{{ $favorite->property->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full bg-subtle flex items-center justify-center">
                                    <svg class="w-12 h-12 text-dim/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-3m0 0l7-4 7 4M5 7v10a1 1 0 001 1h12a1 1 0 001-1V7m-9 10l-2-1m0 0l-2 1m2-1v-5"></path>
                                    </svg>
                                </div>
                            @endif
                            {{-- Remove Favorite --}}
                            <button wire:click="removeFavorite({{ $favorite->property->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="removeFavorite({{ $favorite->property->id }})"
                                    title="Remove from favorites"
                                    class="absolute top-3 right-3 p-2 bg-red-500 hover:bg-red-600 rounded-full shadow-md transition-all duration-200 hover:scale-110 active:scale-95">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Content --}}
                        <div class="p-5 space-y-3">
                            <div>
                                <p class="text-[10px] font-semibold text-accent uppercase tracking-wider">{{ $favorite->property->propertyType->name ?? 'Property' }}</p>
                                <h3 class="font-semibold text-foreground font-serif tracking-tight mt-1">{{ $favorite->property->title }}</h3>
                            </div>

                            <p class="text-lg font-bold text-foreground font-serif">₱{{ number_format($favorite->property->price, 0) }}<span class="text-xs text-dim font-normal font-sans">/month</span></p>

                            <p class="text-xs text-dim line-clamp-2 leading-relaxed">{{ $favorite->property->description }}</p>

                            <div class="flex gap-4 py-3 border-t border-line text-xs text-dim">
                                <span><strong class="text-foreground">{{ $favorite->property->bedrooms }}</strong> beds</span>
                                <span><strong class="text-foreground">{{ $favorite->property->bathrooms }}</strong> baths</span>
                                @if($favorite->property->area)
                                    <span><strong class="text-foreground">{{ $favorite->property->area }}</strong> sqft</span>
                                @endif
                            </div>

                            <button wire:click="showPropertyDetail({{ $favorite->property->id }})" class="w-full py-2 px-4 bg-foreground text-on-primary rounded-sm hover:opacity-90 font-medium text-sm transition-all">
                                View Details
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $favorites->links() }}</div>
        @else
            <div class="text-center py-16 px-8 bg-card border border-line rounded-sm">
                <svg class="w-12 h-12 mx-auto text-dim/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <p class="text-base font-semibold text-foreground font-serif">No favourites yet</p>
                <p class="text-xs text-dim mt-2">Start adding properties to your favourites</p>
                <a href="{{ route('renter.explore') }}" class="inline-block mt-6 px-6 py-2 bg-foreground text-on-primary rounded-sm hover:opacity-90 font-medium text-sm transition-all">
                    Explore Properties
                </a>
            </div>
        @endif
    </div>

    {{-- Property Detail Modal --}}
    @if($selectedProperty)
        <div class="fixed inset-0 bg-foreground/40 backdrop-blur-sm z-50 flex items-center justify-center p-4" wire:click.self="closePropertyDetail">
            <div class="bg-card border border-line rounded-sm max-w-4xl w-full max-h-[90vh] overflow-y-auto relative" @click.stop style="box-shadow: var(--shadow-lg);">
                <button wire:click="closePropertyDetail" class="absolute top-4 right-4 z-10 w-8 h-8 flex items-center justify-center bg-card border border-line rounded-sm text-dim hover:text-foreground hover:bg-subtle transition-colors" style="box-shadow: var(--shadow-xs);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-0 lg:gap-8 p-6 lg:p-8">
                    {{-- Left: Image Gallery --}}
                    <div class="lg:col-span-2" x-data="{ activeImage: '{{ $selectedProperty->images->count() > 0 ? asset('storage/' . $selectedProperty->images[0]->image_path) : '' }}' }">
                        {{-- Main Image --}}
                        <div class="relative w-full h-80 lg:h-96 bg-subtle rounded-sm overflow-hidden mb-4 group">
                            @if($selectedProperty->images->count() > 0)
                                <img :src="activeImage"
                                     alt="{{ $selectedProperty->title }}"
                                     class="w-full h-full object-cover transition-opacity duration-200">
                            @else
                                <div class="w-full h-full bg-subtle flex items-center justify-center">
                                    <svg class="w-16 h-16 text-dim/20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-3m0 0l7-4 7 4M5 7v10a1 1 0 001 1h12a1 1 0 001-1V7m-9 10l-2-1m0 0l-2 1m2-1v-5"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Thumbnail Gallery --}}
                        @if($selectedProperty->images->count() > 1)
                            <div class="grid grid-cols-4 gap-2 mb-6">
                                @foreach($selectedProperty->images as $index => $image)
                                    @php $imgUrl = asset('storage/' . $image->image_path); @endphp
                                    <button @click="activeImage = '{{ $imgUrl }}'" type="button"
                                            class="relative h-16 lg:h-20 rounded-sm overflow-hidden border-2 transition-all cursor-pointer focus:outline-none"
                                            :class="activeImage === '{{ $imgUrl }}' ? 'border-foreground' : 'border-line hover:border-foreground/40'">
                                        <img src="{{ $imgUrl }}"
                                             alt="Property image"
                                             class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        {{-- Description Section --}}
                        <div class="pt-6 border-t border-line">
                            <h3 class="text-lg font-semibold text-foreground font-serif tracking-tight mb-3">About this property</h3>
                            <p class="text-sm text-dim leading-relaxed">{{ $selectedProperty->description }}</p>
                        </div>

                        {{-- Amenities --}}
                        @if($selectedProperty->amenities)
                            <div class="mt-6 pt-6 border-t border-line">
                                <h3 class="text-lg font-semibold text-foreground font-serif tracking-tight mb-4">Amenities</h3>
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $amenitiesList = is_array($selectedProperty->amenities) ? $selectedProperty->amenities : json_decode($selectedProperty->amenities, true) ?? [];
                                    @endphp
                                    @foreach($amenitiesList as $amenity)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-dim bg-subtle border border-line rounded-sm">
                                            <svg class="w-3 h-3 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                                            </svg>
                                            {{ $amenity }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Right: Property Info --}}
                    <div class="lg:col-span-1">
                        <div class="mb-6 pb-6 border-b border-line">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.12em] text-dim mb-1">Monthly Rate</p>
                            <p class="text-3xl font-bold text-foreground font-serif tracking-tight">₱{{ number_format($selectedProperty->price, 0) }}<span class="text-sm font-normal text-dim font-sans">/mo</span></p>
                        </div>

                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-foreground font-serif tracking-tight mb-1">{{ $selectedProperty->title }}</h2>
                            <p class="text-xs font-medium text-accent uppercase tracking-wider">{{ $selectedProperty->propertyType->name ?? 'Property' }}</p>
                        </div>

                        @if($selectedProperty->address)
                            <div class="mb-6 pb-6 border-b border-line">
                                <div class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-accent flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-foreground">
                                            {{ $selectedProperty->address->barangay?->name }}, {{ $selectedProperty->address->city?->name }}
                                        </p>
                                        <p class="text-xs text-dim mt-0.5">
                                            {{ $selectedProperty->address->city?->province?->name }} {{ $selectedProperty->address->city?->province?->region?->name }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-3 gap-3 mb-6 pb-6 border-b border-line">
                            @if($selectedProperty->bedrooms)
                                <div class="text-center py-3 bg-subtle rounded-sm">
                                    <p class="text-lg font-bold text-foreground font-serif">{{ $selectedProperty->bedrooms }}</p>
                                    <p class="text-[10px] text-dim font-semibold uppercase tracking-wider mt-0.5">Beds</p>
                                </div>
                            @endif
                            @if($selectedProperty->bathrooms)
                                <div class="text-center py-3 bg-subtle rounded-sm">
                                    <p class="text-lg font-bold text-foreground font-serif">{{ $selectedProperty->bathrooms }}</p>
                                    <p class="text-[10px] text-dim font-semibold uppercase tracking-wider mt-0.5">Baths</p>
                                </div>
                            @endif
                            @if($selectedProperty->area)
                                <div class="text-center py-3 bg-subtle rounded-sm">
                                    <p class="text-lg font-bold text-foreground font-serif">{{ $selectedProperty->area }}</p>
                                    <p class="text-[10px] text-dim font-semibold uppercase tracking-wider mt-0.5">Sqft</p>
                                </div>
                            @endif
                        </div>

                        @if(!$showReservationForm)
                            <div class="space-y-2.5">
                                <button wire:click="openReservationForm" class="w-full py-2.5 px-4 bg-foreground text-on-primary rounded-sm hover:opacity-90 font-medium text-sm transition-all" style="box-shadow: var(--shadow-xs);">
                                    Reserve Now
                                </button>
                                <button wire:click="sendInquiry({{ $selectedProperty->id }})" class="w-full py-2.5 px-4 border border-foreground text-foreground rounded-sm hover:bg-foreground hover:text-on-primary font-medium text-sm transition-all">
                                    Send Inquiry
                                </button>
                                <button wire:click="removeFavorite({{ $selectedProperty->id }})" class="w-full py-2.5 px-4 border border-line text-dim rounded-sm hover:border-foreground hover:text-foreground font-medium text-sm transition-all">
                                    Remove from Favourites
                                </button>
                                <button wire:click="closePropertyDetail" class="w-full py-2 px-4 text-dim rounded-sm hover:text-foreground font-medium text-xs transition-colors text-center">
                                    Close
                                </button>
                            </div>
                        @else
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 mb-2 pb-3 border-b border-line">
                                    <button wire:click="closeReservationForm" class="text-dim hover:text-foreground transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </button>
                                    <h3 class="text-sm font-semibold text-foreground font-serif">Reserve This Property</h3>
                                </div>

                                <form wire:submit="submitReservation" class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-medium text-dim mb-1.5 uppercase tracking-wider">
                                            Move-in Date <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" wire:model.live="moveInDate"
                                               min="{{ date('Y-m-d') }}"
                                               class="w-full px-3 py-2 rounded-sm border border-line bg-page text-foreground text-sm focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground">
                                        @error('moveInDate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-dim mb-1.5 uppercase tracking-wider">
                                            Move-out Date <span class="text-dim/50 normal-case tracking-normal">(optional)</span>
                                        </label>
                                        <input type="date" wire:model.live="moveOutDate"
                                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                               class="w-full px-3 py-2 rounded-sm border border-line bg-page text-foreground text-sm focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground">
                                        @error('moveOutDate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    @if($moveInDate)
                                        @php
                                            $months = 1;
                                            if ($moveOutDate) {
                                                $diff = \Carbon\Carbon::parse($moveInDate)->diffInMonths(\Carbon\Carbon::parse($moveOutDate));
                                                $months = max(1, (int) $diff);
                                            }
                                            $estimated = $selectedProperty->price * $months;
                                        @endphp
                                        <div class="bg-subtle border border-line rounded-sm p-4">
                                            <p class="text-[10px] font-semibold text-dim uppercase tracking-[0.12em] mb-1">Estimated Total</p>
                                            <p class="text-xl font-bold text-foreground font-serif">₱{{ number_format($estimated, 2) }}</p>
                                            <p class="text-xs text-dim mt-1">
                                                ₱{{ number_format($selectedProperty->price, 0) }}/mo × {{ $months }} {{ Str::plural('month', $months) }}
                                            </p>
                                        </div>
                                    @endif

                                    <div>
                                        <label class="block text-xs font-medium text-dim mb-1.5 uppercase tracking-wider">
                                            Notes <span class="text-dim/50 normal-case tracking-normal">(optional)</span>
                                        </label>
                                        <textarea wire:model="reservationNotes" placeholder="Any special requests..." rows="2"
                                                  class="w-full px-3 py-2 rounded-sm border border-line bg-page text-foreground placeholder-dim/50 text-sm focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground resize-none">
                                        </textarea>
                                        @error('reservationNotes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="flex gap-2.5 pt-1">
                                        <button type="submit"
                                                class="flex-1 py-2.5 px-4 bg-foreground text-on-primary rounded-sm hover:opacity-90 font-medium text-sm transition-all">
                                            Submit
                                        </button>
                                        <button type="button" wire:click="closeReservationForm"
                                                class="flex-1 py-2.5 px-4 border border-line text-dim rounded-sm hover:text-foreground hover:border-foreground font-medium text-sm transition-all">
                                            Back
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
