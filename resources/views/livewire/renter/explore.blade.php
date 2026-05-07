<div>
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h2 class="text-xl font-semibold text-foreground font-serif tracking-tight">Explore Properties</h2>
            <p class="text-xs text-dim mt-1">Browse available rentals and find your perfect space.</p>
        </div>

        {{-- Slider CSS --}}
        <style>
            .range-slider {
                -webkit-appearance: none; appearance: none;
                height: 4px; border-radius: 9999px;
                outline: none; cursor: pointer;
            }
            /* Min slider — fills left side (at-least) */
            .range-slider--min {
                background: linear-gradient(to right, var(--c-text, #18181b) var(--pct, 0%), var(--c-border, #e4e4e7) var(--pct, 0%));
            }
            /* Max slider — fills right side (at-most) */
            .range-slider--max {
                background: linear-gradient(to left, var(--c-text, #18181b) var(--pct2, 0%), var(--c-border, #e4e4e7) var(--pct2, 0%));
            }
            .range-slider::-webkit-slider-thumb {
                -webkit-appearance: none; appearance: none;
                width: 17px; height: 17px; border-radius: 50%;
                background: var(--c-text, #18181b);
                border: 2px solid var(--c-page, #fff);
                box-shadow: 0 1px 4px rgba(0,0,0,.22);
                cursor: grab; transition: transform .1s;
            }
            .range-slider::-webkit-slider-thumb:active { transform: scale(1.15); cursor: grabbing; }
            .range-slider::-moz-range-thumb {
                width: 17px; height: 17px; border-radius: 50%;
                background: var(--c-text, #18181b);
                border: 2px solid var(--c-page, #fff);
                box-shadow: 0 1px 4px rgba(0,0,0,.22);
                cursor: grab;
            }
        </style>

        {{-- Filters Bar — single Alpine scope owns ALL local filter state --}}
        <div
            x-data="{
                showFilters:  false,
                search:       '{{ addslashes($search) }}',
                propType:     '{{ $propertyType }}',
                dbMax:        {{ $dbMaxPrice }},
                maxPrice:     {{ $maxPrice !== '' && is_numeric($maxPrice) ? (int)$maxPrice : $dbMaxPrice }},
                minBedrooms:  {{ (int)$minBedrooms }},
                minBathrooms: {{ (int)$minBathrooms }},
                get hasFilters() {
                    return this.search !== '' || this.propType !== '' ||
                           this.maxPrice < this.dbMax ||
                           this.minBedrooms !== 0 || this.minBathrooms !== 0;
                },
                get advFilterCount() {
                    let n = 0;
                    if (this.maxPrice < this.dbMax) n++;
                    if (this.minBedrooms !== 0)     n++;
                    if (this.minBathrooms !== 0)    n++;
                    return n;
                },
                apply() {
                    $wire.applyFilters(
                        this.search,
                        this.propType,
                        String(this.maxPrice),
                        String(this.minBedrooms),
                        String(this.minBathrooms)
                    );
                },
                reset() {
                    this.search = ''; this.propType = '';
                    this.maxPrice = this.dbMax;
                    this.minBedrooms = 0; this.minBathrooms = 0;
                    $wire.resetFilters();
                }
            }"
            class="border border-line bg-card rounded-sm" style="box-shadow: var(--shadow-xs);">

            {{-- Always-visible row --}}
            <div class="p-4 flex flex-col sm:flex-row gap-3">
                {{-- Search input + button --}}
                <div class="relative flex-1 flex gap-2">
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-dim pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" x-model="search"
                            @keydown.enter="apply()"
                            placeholder="Search properties…"
                            class="w-full pl-9 pr-3 py-2 rounded-sm border border-line bg-page text-sm text-foreground placeholder-dim/50 focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground">
                    </div>
                    <button @click="apply()"
                        class="inline-flex items-center gap-1.5 rounded-sm bg-foreground text-on-primary px-4 py-2 text-sm font-medium hover:opacity-90 transition-all shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search
                    </button>
                </div>

                {{-- Property type (local Alpine, applied on Search) --}}
                <select x-model="propType"
                    class="sm:w-44 rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground">
                    <option value="">All Types</option>
                    @foreach($propertyTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>

                {{-- Advanced filter toggle — only enabled after typing a search term --}}
                <button
                    @click="search.trim() !== '' && (showFilters = !showFilters)"
                    x-init="$watch('search', v => { if (!v.trim()) showFilters = false })"
                    :title="search.trim() === '' ? 'Type a search term to enable filters' : ''"
                    :class="search.trim() !== ''
                        ? 'text-dim hover:bg-subtle hover:text-foreground cursor-pointer'
                        : 'opacity-40 cursor-not-allowed'"
                    class="inline-flex items-center gap-1.5 rounded-sm border border-line px-3 py-2 text-sm font-medium transition-colors">
                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="showFilters ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                    Filters
                    <span x-show="advFilterCount > 0"
                        x-text="advFilterCount"
                        class="inline-flex items-center justify-center h-4 min-w-[1rem] rounded-full bg-foreground text-on-primary text-[10px] font-semibold px-1">
                    </span>
                </button>

                {{-- Reset — visible when anything is active --}}
                <button x-show="hasFilters" @click="reset()"
                    class="inline-flex items-center gap-1.5 rounded-sm border border-line px-3 py-2 text-sm font-medium text-dim hover:text-foreground hover:bg-subtle transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reset
                </button>
            </div>

            {{-- Collapsible slider panel --}}
            <div x-show="showFilters" x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 class="border-t border-line p-5 bg-subtle/20">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

                    {{-- ── Max Price ──────────────────────────────────── --}}
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[10px] font-semibold text-dim uppercase tracking-wider">Max Price / Month</span>
                            <span class="text-xs font-semibold text-foreground tabular-nums">
                                Up to ₱<span x-text="maxPrice.toLocaleString()"></span>
                            </span>
                        </div>
                        <input type="range" min="0" :max="dbMax" step="500" :value="maxPrice"
                            @input="maxPrice = parseInt($event.target.value)"
                            class="range-slider range-slider--min w-full min-w-0"
                            :style="`--pct: ${(maxPrice / dbMax) * 100}%`">
                        <div class="flex justify-between mt-2 text-[10px] text-dim/60">
                            <span>₱0</span>
                            <span>₱<span x-text="dbMax.toLocaleString()"></span></span>
                        </div>
                    </div>

                    {{-- ── Min Bedrooms ────────────────────────────────── --}}
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[10px] font-semibold text-dim uppercase tracking-wider">Min Bedrooms</span>
                            <span class="text-xs font-semibold text-foreground">
                                <span x-text="minBedrooms === 0 ? 'Any' : minBedrooms + '+ beds'"></span>
                            </span>
                        </div>
                        <input type="range" min="0" max="10" step="1" :value="minBedrooms"
                            @input="minBedrooms = parseInt($event.target.value)"
                            class="range-slider range-slider--min w-full min-w-0"
                            :style="`--pct: ${(minBedrooms / 10) * 100}%`">
                        <div class="flex justify-between mt-2 text-[10px] text-dim/60">
                            <span>Any</span><span>10 beds</span>
                        </div>
                    </div>

                    {{-- ── Min Bathrooms ───────────────────────────────── --}}
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[10px] font-semibold text-dim uppercase tracking-wider">Min Bathrooms</span>
                            <span class="text-xs font-semibold text-foreground">
                                <span x-text="minBathrooms === 0 ? 'Any' : minBathrooms + '+ baths'"></span>
                            </span>
                        </div>
                        <input type="range" min="0" max="10" step="1" :value="minBathrooms"
                            @input="minBathrooms = parseInt($event.target.value)"
                            class="range-slider range-slider--min w-full min-w-0"
                            :style="`--pct: ${(minBathrooms / 10) * 100}%`">
                        <div class="flex justify-between mt-2 text-[10px] text-dim/60">
                            <span>Any</span><span>10 baths</span>
                        </div>
                    </div>

                </div>

                {{-- Apply button inside the panel --}}
                <div class="flex justify-end mt-5 pt-4 border-t border-line">
                    <button @click="apply()"
                        class="inline-flex items-center gap-1.5 rounded-sm bg-foreground text-on-primary px-5 py-2 text-sm font-medium hover:opacity-90 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>

        {{-- Properties Grid --}}
        @if($properties->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($properties as $property)
                    <div class="border border-line bg-card rounded-sm overflow-hidden hover:border-foreground/30 transition-all duration-200" style="box-shadow: var(--shadow-xs);">
                        {{-- Image --}}
                        <div class="relative w-full h-56 bg-subtle overflow-hidden group">
                            @if($property->images->first())
                                <img src="{{ asset('storage/' . $property->images->first()->image_path) }}" 
                                     alt="{{ $property->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full bg-subtle flex items-center justify-center">
                                    <svg class="w-12 h-12 text-dim/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-3m0 0l7-4 7 4M5 7v10a1 1 0 001 1h12a1 1 0 001-1V7m-9 10l-2-1m0 0l-2 1m2-1v-5"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            {{-- Heart --}}
                            @php $isFaved = in_array($property->id, $favoriteIds); @endphp
                            <button wire:click="addToFavorites({{ $property->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="addToFavorites({{ $property->id }})"
                                    title="{{ $isFaved ? 'Remove from favorites' : 'Add to favorites' }}"
                                    class="absolute top-3 right-3 p-2 rounded-full shadow-md transition-all duration-200 hover:scale-110 active:scale-95
                                        {{ $isFaved ? 'bg-red-500 hover:bg-red-600' : 'bg-white hover:bg-red-50' }}">
                                @if($isFaved)
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                @endif
                            </button>
                        </div>

                        {{-- Content --}}
                        <div class="p-5 space-y-3">
                            <div>
                                <p class="text-[10px] font-semibold text-accent uppercase tracking-wider">{{ $property->propertyType->name ?? 'Property' }}</p>
                                <h3 class="font-semibold text-foreground font-serif tracking-tight mt-1 line-clamp-1">{{ $property->title }}</h3>
                            </div>

                            <p class="text-lg font-bold text-foreground font-serif">₱{{ number_format($property->price, 0) }}<span class="text-xs text-dim font-normal font-sans">/month</span></p>

                            <p class="text-xs text-dim line-clamp-2 leading-relaxed">{{ $property->description }}</p>

                            {{-- Star rating summary --}}
                            @php
                                $verifiedReviews = $property->reviews->where('is_verified', true);
                                $reviewCount = $verifiedReviews->count();
                                $avgRating = $reviewCount ? round($verifiedReviews->avg('rating'), 1) : null;
                            @endphp
                            @if($reviewCount)
                                <div class="flex items-center gap-1.5">
                                    <div class="flex items-center gap-0.5">
                                        @for($s = 1; $s <= 5; $s++)
                                            <svg class="w-3.5 h-3.5 {{ $s <= round($avgRating) ? 'text-yellow-400' : 'text-dim/20' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-xs font-semibold text-foreground">{{ $avgRating }}</span>
                                    <span class="text-xs text-dim">({{ $reviewCount }})</span>
                                </div>
                            @else
                                <p class="text-xs text-dim/50 italic">No reviews yet</p>
                            @endif

                            <div class="flex gap-4 py-3 border-t border-line text-xs text-dim">
                                @if($property->bedrooms)
                                    <span><strong class="text-foreground">{{ $property->bedrooms }}</strong> beds</span>
                                @endif
                                @if($property->bathrooms)
                                    <span><strong class="text-foreground">{{ $property->bathrooms }}</strong> baths</span>
                                @endif
                            </div>

                            <a href="{{ route('renter.property', $property->id) }}" wire:navigate
                                class="block w-full py-2 px-4 bg-foreground text-on-primary rounded-sm hover:opacity-90 font-medium text-sm transition-all text-center">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $properties->links() }}</div>
        @else
            <div class="text-center py-16 px-8 bg-card border border-line rounded-sm">
                <svg class="w-12 h-12 mx-auto text-dim/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="text-base font-semibold text-foreground font-serif">No properties found</p>
                <p class="text-xs text-dim mt-2">Try adjusting your filters to find available rentals</p>
            </div>
        @endif
    </div>

    {{-- Property detail is now a dedicated page (renter.property) --}}
    @if(false)
        <div>
            {{-- Modal Container --}}
            <div class="bg-card border border-line rounded-sm max-w-4xl w-full max-h-[90vh] overflow-y-auto relative" @click.stop style="box-shadow: var(--shadow-lg);">
                {{-- Close Button --}}
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
                                <div class="w-full h-full bg-gradient-to-br from-primary/10 to-primary/5 flex items-center justify-center">
                                    <svg class="w-24 h-24 text-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-3m0 0l7-4 7 4M5 7v10a1 1 0 001 1h12a1 1 0 001-1V7m-9 10l-2-1m0 0l-2 1m2-1v-5"></path>
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
                        {{-- Price --}}
                        <div class="mb-6 pb-6 border-b border-line">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.12em] text-dim mb-1">Monthly Rate</p>
                            <p class="text-3xl font-bold text-foreground font-serif tracking-tight">₱{{ number_format($selectedProperty->price, 0) }}<span class="text-sm font-normal text-dim font-sans">/mo</span></p>
                        </div>

                        {{-- Title and Type --}}
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-foreground font-serif tracking-tight mb-1">{{ $selectedProperty->title }}</h2>
                            <p class="text-xs font-medium text-accent uppercase tracking-wider">{{ $selectedProperty->propertyType->name ?? 'Property' }}</p>
                        </div>

                        {{-- Location --}}
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

                        {{-- Details Grid --}}
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

                        {{-- Action Buttons --}}
                        @if(!$showReservationForm)
                            <div class="space-y-2.5">
                                <button wire:click="openReservationForm" class="w-full py-2.5 px-4 bg-foreground text-on-primary rounded-sm hover:opacity-90 font-medium text-sm transition-all" style="box-shadow: var(--shadow-xs);">
                                    Reserve Now
                                </button>
                                <button wire:click="sendInquiry({{ $selectedProperty->id }})" class="w-full py-2.5 px-4 border border-foreground text-foreground rounded-sm hover:bg-foreground hover:text-on-primary font-medium text-sm transition-all">
                                    Send Inquiry
                                </button>
                                <button wire:click="addToFavorites({{ $selectedProperty->id }})" class="w-full py-2.5 px-4 border border-line text-dim rounded-sm hover:border-foreground hover:text-foreground font-medium text-sm transition-all">
                                    Add to Favourites
                                </button>
                                <button wire:click="closePropertyDetail" class="w-full py-2 px-4 text-dim rounded-sm hover:text-foreground font-medium text-xs transition-colors text-center">
                                    Close
                                </button>
                            </div>
                        @else
                            {{-- Reservation Form --}}
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

                                    {{-- Live Price Estimate --}}
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
                                        <textarea wire:model="reservationNotes"
                                                  placeholder="Any special requests..."
                                                  rows="2"
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

