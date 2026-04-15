<div>
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h2 class="text-2xl font-bold text-foreground">Explore Properties</h2>
        </div>

        {{-- Filters Bar --}}
        <div class="rounded-lg border border-line bg-card">
            <div class="p-8 space-y-6">
                {{-- First Row: Search & Basic Filters --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <input type="text" wire:model.live="search" placeholder="Search..." 
                        class="rounded-md border border-line bg-card px-4 py-2.5 text-sm text-foreground placeholder-dim focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    
                    <select wire:model.live="propertyType" class="rounded-md border border-line bg-card px-4 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                        <option value="">All Types</option>
                        @foreach($propertyTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>

                    <input type="number" wire:model.live="minPrice" placeholder="Min $" 
                        class="rounded-md border border-line bg-card px-4 py-2.5 text-sm text-foreground placeholder-dim focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    
                    <input type="number" wire:model.live="maxPrice" placeholder="Max $" 
                        class="rounded-md border border-line bg-card px-4 py-2.5 text-sm text-foreground placeholder-dim focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">

                    <button wire:click="resetFilters" class="rounded-md bg-primary text-on-primary px-4 py-2.5 text-sm font-semibold hover:bg-primary/90 transition-colors">
                        Reset
                    </button>
                </div>

                {{-- Second Row: Room Filters --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <input type="number" wire:model.live="minBedrooms" placeholder="Min Bed" min="0"
                        class="rounded-md border border-line bg-card px-4 py-2.5 text-sm text-foreground placeholder-dim focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    
                    <input type="number" wire:model.live="maxBedrooms" placeholder="Max Bed" min="0"
                        class="rounded-md border border-line bg-card px-4 py-2.5 text-sm text-foreground placeholder-dim focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    
                    <input type="number" wire:model.live="minBathrooms" placeholder="Min Bath" min="0"
                        class="rounded-md border border-line bg-card px-4 py-2.5 text-sm text-foreground placeholder-dim focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    
                    <input type="number" wire:model.live="maxBathrooms" placeholder="Max Bath" min="0"
                        class="rounded-md border border-line bg-card px-4 py-2.5 text-sm text-foreground placeholder-dim focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                </div>


        {{-- Properties Grid --}}
        @if($properties->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @foreach($properties as $property)
                    <div class="rounded-lg border border-line bg-card overflow-hidden hover:shadow-2xl hover:border-primary transition-all duration-200">
                        {{-- Image Container --}}
                        <div class="relative w-full h-80 bg-gray-200 overflow-hidden group">
                            @if($property->images->first())
                                <img src="{{ asset('storage/' . $property->images->first()->image_path) }}" 
                                     alt="{{ $property->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-primary/10 to-primary/5 flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="w-16 h-16 text-line mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-3m0 0l7-4 7 4M5 7v10a1 1 0 001 1h12a1 1 0 001-1V7m-9 10l-2-1m0 0l-2 1m2-1v-5"></path>
                                        </svg>
                                    </div>
                                </div>
                            @endif
                            
                            {{-- Heart Button --}}
                            <button wire:click="addToFavorites({{ $property->id }})" class="absolute top-3 right-3 p-2 bg-white rounded-full hover:bg-red-50 shadow-md transition-colors">
                                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path>
                                </svg>
                            </button>
                        </div>

                        {{-- Card Content --}}
                        <div class="p-6 space-y-5">
                            {{-- Title and Price --}}
                            <div>
                                <h3 class="font-bold text-foreground text-lg line-clamp-1">{{ $property->title }}</h3>
                                <p class="text-sm text-dim mt-1.5">{{ $property->propertyType->name ?? 'Property' }}</p>
                            </div>

                            {{-- Price --}}
                            <div class="text-2xl font-bold text-primary">
                                ${{ number_format($property->price, 0) }}<span class="text-xs text-dim font-normal">/month</span>
                            </div>

                            {{-- Description --}}
                            <p class="text-sm text-dim line-clamp-2">{{ $property->description }}</p>

                            {{-- Features --}}
                            <div class="flex gap-6 py-4 border-t border-b border-line">
                                @if($property->bedrooms)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"></path>
                                        </svg>
                                        <span class="text-sm font-bold text-foreground">{{ $property->bedrooms }}</span>
                                        <span class="text-sm text-dim">beds</span>
                                    </div>
                                @endif
                                @if($property->bathrooms)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"></path>
                                        </svg>
                                        <span class="text-sm font-bold text-foreground">{{ $property->bathrooms }}</span>
                                        <span class="text-sm text-dim">baths</span>
                                    </div>
                                @endif
                            </div>

                            {{-- CTA Button --}}
                            <button wire:click="showPropertyDetail({{ $property->id }})" class="w-full py-3 px-4 bg-primary text-on-primary rounded-md hover:bg-primary/90 font-bold text-base transition-colors">
                                View Details
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $properties->links() }}
            </div>
        @else
            <div class="text-center py-16 px-8 bg-card border border-line rounded-lg mb-12">
                <svg class="w-20 h-20 mx-auto text-dim mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="text-2xl font-bold text-foreground">No properties found</p>
                <p class="text-base text-dim mt-3">Try adjusting your filters to find available rentals</p>
            </div>
        @endif
    </div>

    {{-- Property Detail Modal --}}
    @if($selectedProperty)
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" wire:click.self="closePropertyDetail">
            {{-- Modal Container --}}
            <div class="bg-white dark:bg-foreground rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
                {{-- Close Button --}}
                <button wire:click="closePropertyDetail" class="absolute top-4 right-4 z-10 p-2 bg-white dark:bg-card rounded-full hover:bg-gray-100 dark:hover:bg-line transition-colors" style="background-color: white; border: 1px solid #e5e7eb;">
                    <svg class="w-6 h-6 text-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 p-8">
                    {{-- Left: Image Gallery --}}
                    <div class="lg:col-span-2">
                        {{-- Main Image --}}
                        <div class="relative w-full h-96 bg-gray-200 rounded-xl overflow-hidden mb-5 group">
                            @if($selectedProperty->images->count() > 0)
                                <img src="{{ asset('storage/' . $selectedProperty->images[0]->image_path) }}" 
                                     alt="{{ $selectedProperty->title }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-primary/10 to-primary/5 flex items-center justify-center">
                                    <svg class="w-24 h-24 text-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-3m0 0l7-4 7 4M5 7v10a1 1 0 001 1h12a1 1 0 001-1V7m-9 10l-2-1m0 0l-2 1m2-1v-5"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Thumbnail Gallery --}}
                        @if($selectedProperty->images->count() > 1)
                            <div class="grid grid-cols-4 gap-3 mb-8">
                                @foreach($selectedProperty->images as $index => $image)
                                    <div class="relative h-20 rounded-lg overflow-hidden border-2 border-line hover:border-primary/50 transition-all">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                                             alt="Property image"
                                             class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Description Section --}}
                        <div class="pt-8 border-t border-line">
                            <h3 class="text-2xl font-bold text-foreground mb-4">About this property</h3>
                            <p class="text-base text-foreground leading-relaxed">{{ $selectedProperty->description }}</p>
                        </div>

                        {{-- Amenities --}}
                        @if($selectedProperty->amenities)
                            <div class="mt-8 pt-8 border-t border-line">
                                <h3 class="text-2xl font-bold text-foreground mb-6">Amenities</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    @php
                                        $amenitiesList = is_array($selectedProperty->amenities) ? $selectedProperty->amenities : json_decode($selectedProperty->amenities, true) ?? [];
                                    @endphp
                                    @foreach($amenitiesList as $amenity)
                                        <div class="flex items-center gap-3 p-3 bg-card border border-line rounded-lg">
                                            <svg class="w-5 h-5 text-primary flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-foreground">{{ $amenity }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Right: Property Info --}}
                    <div class="lg:col-span-1">
                        {{-- Price Card --}}
                        <div class="bg-primary/5 border border-primary/20 rounded-xl p-6 mb-6">
                            <p class="text-sm font-bold text-dim uppercase tracking-wider mb-2">Monthly Rate</p>
                            <p class="text-4xl font-extrabold text-primary">${{ number_format($selectedProperty->price, 0) }}</p>
                            <p class="text-sm text-dim mt-2">/month</p>
                        </div>

                        {{-- Title and Type --}}
                        <div class="mb-8">
                            <h2 class="text-3xl font-bold text-foreground mb-2">{{ $selectedProperty->title }}</h2>
                            <p class="text-base font-semibold text-primary">{{ $selectedProperty->propertyType->name ?? 'Property' }}</p>
                        </div>

                        {{-- Location --}}
                        @if($selectedProperty->address)
                            <div class="mb-8 pb-8 border-b border-line">
                                <div class="flex gap-3">
                                    <svg class="w-6 h-6 text-primary flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-dim mb-1">Location</p>
                                        <p class="text-base font-semibold text-foreground">
                                            {{ $selectedProperty->address->barangay?->name }}, {{ $selectedProperty->address->city?->name }}
                                        </p>
                                        <p class="text-sm text-dim mt-1">
                                            {{ $selectedProperty->address->city?->province?->name }} {{ $selectedProperty->address->city?->province?->region?->name }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Details Grid --}}
                        <div class="grid grid-cols-3 gap-4 mb-8 pb-8 border-b border-line">
                            @if($selectedProperty->bedrooms)
                                <div class="text-center p-4 bg-card border border-line rounded-lg">
                                    <svg class="w-6 h-6 text-primary mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"></path>
                                    </svg>
                                    <p class="text-2xl font-bold text-foreground">{{ $selectedProperty->bedrooms }}</p>
                                    <p class="text-xs text-dim font-semibold uppercase">Beds</p>
                                </div>
                            @endif

                            @if($selectedProperty->bathrooms)
                                <div class="text-center p-4 bg-card border border-line rounded-lg">
                                    <svg class="w-6 h-6 text-primary mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"></path>
                                    </svg>
                                    <p class="text-2xl font-bold text-foreground">{{ $selectedProperty->bathrooms }}</p>
                                    <p class="text-xs text-dim font-semibold uppercase">Baths</p>
                                </div>
                            @endif

                            @if($selectedProperty->area)
                                <div class="text-center p-4 bg-card border border-line rounded-lg">
                                    <svg class="w-6 h-6 text-primary mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"></path>
                                    </svg>
                                    <p class="text-2xl font-bold text-foreground">{{ $selectedProperty->area }}</p>
                                    <p class="text-xs text-dim font-semibold uppercase">sqft</p>
                                </div>
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        <div class="space-y-3">
                            <button wire:click="sendInquiry({{ $selectedProperty->id }})" class="w-full py-3 px-4 bg-primary text-on-primary rounded-lg hover:bg-primary/90 font-bold text-base transition-colors">
                                Send Inquiry
                            </button>
                            <button wire:click="addToFavorites({{ $selectedProperty->id }})" class="w-full py-3 px-4 border-2 border-primary text-primary rounded-lg hover:bg-primary/5 font-bold text-base transition-colors">
                                Add to Favorites
                            </button>
                            <button wire:click="closePropertyDetail" class="w-full py-3 px-4 border border-line text-foreground rounded-lg hover:bg-line font-semibold text-base transition-colors">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
