<div>
    @if($property)
        {{-- Modal Backdrop --}}
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" wire:click.self="close">
            {{-- Modal Container --}}
            <div class="bg-white dark:bg-foreground rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
                {{-- Close Button --}}
                <button wire:click="close" class="absolute top-4 right-4 z-10 p-2 bg-white dark:bg-card rounded-full hover:bg-gray-100 dark:hover:bg-line transition-colors" style="background-color: white; border: 1px solid #e5e7eb;">
                    <svg class="w-6 h-6 text-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 p-8">
                    {{-- Left: Image Gallery --}}
                    <div class="lg:col-span-2">
                        {{-- Main Image --}}
                        <div class="relative w-full h-96 bg-gray-200 rounded-xl overflow-hidden mb-5 group">
                            @if($property->images->count() > 0)
                                <img src="{{ asset('storage/' . $property->images[$currentImageIndex]->image_path) }}" 
                                     alt="{{ $property->title }}"
                                     class="w-full h-full object-cover">
                                
                                {{-- Image Navigation Arrows --}}
                                @if($property->images->count() > 1)
                                    <button wire:click="previousImage" class="absolute left-4 top-1/2 -translate-y-1/2 p-3 bg-white/90 hover:bg-white rounded-full shadow-lg transition-all">
                                        <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </button>
                                    <button wire:click="nextImage" class="absolute right-4 top-1/2 -translate-y-1/2 p-3 bg-white/90 hover:bg-white rounded-full shadow-lg transition-all">
                                        <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                    
                                    {{-- Image Counter --}}
                                    <div class="absolute bottom-4 right-4 bg-black/70 text-white px-4 py-2 rounded-full text-sm font-semibold">
                                        {{ $currentImageIndex + 1 }} / {{ $property->images->count() }}
                                    </div>
                                @endif
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-primary/10 to-primary/5 flex items-center justify-center">
                                    <svg class="w-24 h-24 text-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-3m0 0l7-4 7 4M5 7v10a1 1 0 001 1h12a1 1 0 001-1V7m-9 10l-2-1m0 0l-2 1m2-1v-5"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Thumbnail Gallery --}}
                        @if($property->images->count() > 1)
                            <div class="grid grid-cols-4 gap-3">
                                @foreach($property->images as $index => $image)
                                    <button wire:click="$set('currentImageIndex', {{ $index }})" 
                                            class="relative h-20 rounded-lg overflow-hidden border-2 transition-all  @if($currentImageIndex === $index) border-primary @else border-line hover:border-primary/50 @endif">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                                             alt="Property image"
                                             class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        {{-- Description Section --}}
                        <div class="mt-8 pt-8 border-t border-line">
                            <h3 class="text-2xl font-bold text-foreground mb-4">About this property</h3>
                            <p class="text-base text-foreground leading-relaxed">{{ $property->description }}</p>
                        </div>

                        {{-- Amenities --}}
                        @if($property->amenities)
                            <div class="mt-8 pt-8 border-t border-line">
                                <h3 class="text-2xl font-bold text-foreground mb-6">Amenities</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    @php
                                        $amenitiesList = is_array($property->amenities) ? $property->amenities : json_decode($property->amenities, true) ?? [];
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
                            <p class="text-4xl font-extrabold text-primary">${{ number_format($property->price, 0) }}</p>
                            <p class="text-sm text-dim mt-2">/month</p>
                        </div>

                        {{-- Title and Type --}}
                        <div class="mb-8">
                            <h2 class="text-3xl font-bold text-foreground mb-2">{{ $property->title }}</h2>
                            <p class="text-base font-semibold text-primary">{{ $property->propertyType->name ?? 'Property' }}</p>
                        </div>

                        {{-- Location --}}
                        @if($property->address)
                            <div class="mb-8 pb-8 border-b border-line">
                                <div class="flex gap-3">
                                    <svg class="w-6 h-6 text-primary flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-dim mb-1">Location</p>
                                        <p class="text-base font-semibold text-foreground">
                                            {{ $property->address->barangay?->name }}, {{ $property->address->city?->name }}
                                        </p>
                                        <p class="text-sm text-dim mt-1">
                                            {{ $property->address->city?->province?->name }} {{ $property->address->city?->province?->region?->name }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Details Grid --}}
                        <div class="grid grid-cols-3 gap-4 mb-8 pb-8 border-b border-line">
                            @if($property->bedrooms)
                                <div class="text-center p-4 bg-card border border-line rounded-lg">
                                    <svg class="w-6 h-6 text-primary mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"></path>
                                    </svg>
                                    <p class="text-2xl font-bold text-foreground">{{ $property->bedrooms }}</p>
                                    <p class="text-xs text-dim font-semibold uppercase">Beds</p>
                                </div>
                            @endif

                            @if($property->bathrooms)
                                <div class="text-center p-4 bg-card border border-line rounded-lg">
                                    <svg class="w-6 h-6 text-primary mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"></path>
                                    </svg>
                                    <p class="text-2xl font-bold text-foreground">{{ $property->bathrooms }}</p>
                                    <p class="text-xs text-dim font-semibold uppercase">Baths</p>
                                </div>
                            @endif

                            @if($property->area)
                                <div class="text-center p-4 bg-card border border-line rounded-lg">
                                    <svg class="w-6 h-6 text-primary mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"></path>
                                    </svg>
                                    <p class="text-2xl font-bold text-foreground">{{ $property->area }}</p>
                                    <p class="text-xs text-dim font-semibold uppercase">sqft</p>
                                </div>
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        <div class="space-y-3">
                            <button class="w-full py-3 px-4 bg-primary text-on-primary rounded-lg hover:bg-primary/90 font-bold text-base transition-colors">
                                Send Inquiry
                            </button>
                            <button class="w-full py-3 px-4 border-2 border-primary text-primary rounded-lg hover:bg-primary/5 font-bold text-base transition-colors">
                                Add to Favorites
                            </button>
                            <button wire:click="close" class="w-full py-3 px-4 border border-line text-foreground rounded-lg hover:bg-line font-semibold text-base transition-colors">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
