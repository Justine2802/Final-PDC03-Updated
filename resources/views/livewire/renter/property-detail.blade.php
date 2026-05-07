<div>
    {{-- Back nav --}}
    <div class="mb-5 flex items-center gap-3">
        <a href="{{ route('renter.explore') }}" wire:navigate
            class="inline-flex items-center gap-1.5 text-sm text-dim hover:text-foreground transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Explore
        </a>
    </div>

    {{-- Title row --}}
    <div class="mb-4">
        <h1 class="text-2xl font-semibold text-foreground font-serif tracking-tight">{{ $property->title }}</h1>
        <div class="flex flex-wrap items-center gap-2 mt-1 text-sm text-dim">
            <span class="font-medium text-accent uppercase tracking-wider text-[10px]">{{ $property->propertyType->name ?? 'Property' }}</span>
            @if($property->address?->barangay?->city)
                <span class="text-dim/40">·</span>
                <span>{{ $property->address->barangay->city->name }}, {{ $property->address->barangay->city->province?->name }}</span>
            @endif
            @php
                $summaryReviews = $property->reviews->where('is_verified', true);
                $summaryCount   = $summaryReviews->count();
                $summaryAvg     = $summaryCount ? round($summaryReviews->avg('rating'), 1) : null;
            @endphp
            @if($summaryCount)
                <span class="text-dim/40">·</span>
                <div class="flex items-center gap-1">
                    <div class="flex items-center gap-0.5">
                        @for($s = 1; $s <= 5; $s++)
                            <svg class="w-3.5 h-3.5 {{ $s <= round($summaryAvg) ? 'text-yellow-400' : 'text-dim/20' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-xs font-semibold text-foreground">{{ $summaryAvg }}</span>
                    <span class="text-xs text-dim">({{ $summaryCount }} {{ Str::plural('review', $summaryCount) }})</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Image gallery --}}
    @php
        $images = $property->images;
        $imageUrls = $images->map(fn($img) => asset('storage/' . $img->image_path))->values()->toArray();
        $imageCount = count($imageUrls);
    @endphp
    <div class="mb-8 rounded-sm overflow-hidden"
         x-data="{
             images: {{ json_encode($imageUrls) }},
             lightboxOpen: false,
             lightboxIndex: 0,
             open(index) { this.lightboxIndex = index; this.lightboxOpen = true; },
             close() { this.lightboxOpen = false; },
             prev() { this.lightboxIndex = (this.lightboxIndex - 1 + this.images.length) % this.images.length; },
             next() { this.lightboxIndex = (this.lightboxIndex + 1) % this.images.length; }
         }"
         @keydown.escape.window="close()"
         @keydown.arrow-left.window="lightboxOpen && prev()"
         @keydown.arrow-right.window="lightboxOpen && next()">

        @if($imageCount > 0)
            {{-- 1 image: full width --}}
            @if($imageCount === 1)
                <div class="h-80 lg:h-[440px]">
                    <button @click="open(0)" class="w-full h-full relative group overflow-hidden bg-subtle block">
                        <img src="{{ $imageUrls[0] }}" alt="{{ $property->title }}"
                             class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300">
                        <div class="absolute bottom-3 right-3 bg-black/50 text-white text-xs px-2 py-1 rounded">1 photo</div>
                    </button>
                </div>

            {{-- 2 images: side by side --}}
            @elseif($imageCount === 2)
                <div class="grid grid-cols-2 gap-1.5 h-80 lg:h-[440px]">
                    @foreach($imageUrls as $idx => $url)
                        <button @click="open({{ $idx }})" class="relative group overflow-hidden bg-subtle">
                            <img src="{{ $url }}" alt="Property photo"
                                 class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300">
                        </button>
                    @endforeach
                </div>

            {{-- 3 images: main left (2/3) + 2 stacked right --}}
            @elseif($imageCount === 3)
                <div class="grid grid-cols-3 gap-1.5 h-80 lg:h-[440px]">
                    <button @click="open(0)" class="col-span-2 relative group overflow-hidden bg-subtle">
                        <img src="{{ $imageUrls[0] }}" alt="{{ $property->title }}"
                             class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300">
                    </button>
                    <div class="grid grid-rows-2 gap-1.5">
                        @foreach(array_slice($imageUrls, 1, 2) as $idx => $url)
                            <button @click="open({{ $idx + 1 }})" class="relative group overflow-hidden bg-subtle">
                                <img src="{{ $url }}" alt="Property photo"
                                     class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300">
                            </button>
                        @endforeach
                    </div>
                </div>

            {{-- 4 images: main left + 3 stacked right --}}
            @elseif($imageCount === 4)
                <div class="grid grid-cols-3 gap-1.5 h-80 lg:h-[440px]">
                    <button @click="open(0)" class="col-span-2 relative group overflow-hidden bg-subtle">
                        <img src="{{ $imageUrls[0] }}" alt="{{ $property->title }}"
                             class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300">
                    </button>
                    <div class="grid grid-rows-3 gap-1.5">
                        @foreach(array_slice($imageUrls, 1, 3) as $idx => $url)
                            <button @click="open({{ $idx + 1 }})" class="relative group overflow-hidden bg-subtle">
                                <img src="{{ $url }}" alt="Property photo"
                                     class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300">
                            </button>
                        @endforeach
                    </div>
                </div>

            {{-- 5+ images: main left (2/4) + 2x2 grid right --}}
            @else
                <div class="grid grid-cols-4 grid-rows-2 gap-1.5 h-80 lg:h-[440px]">
                    <button @click="open(0)" class="col-span-2 row-span-2 relative group overflow-hidden bg-subtle">
                        <img src="{{ $imageUrls[0] }}" alt="{{ $property->title }}"
                             class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300">
                    </button>
                    @foreach(array_slice($imageUrls, 1, 4) as $idx => $url)
                        <button @click="open({{ $idx + 1 }})" class="relative group overflow-hidden bg-subtle">
                            @if($idx === 3 && $imageCount > 5)
                                <img src="{{ $url }}" alt="Property photo"
                                     class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300 brightness-50">
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <span class="text-white text-sm font-semibold">+{{ $imageCount - 4 }} more</span>
                                </div>
                            @else
                                <img src="{{ $url }}" alt="Property photo"
                                     class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300">
                            @endif
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- Lightbox --}}
            <div x-show="lightboxOpen" x-cloak
                class="fixed inset-0 z-[100] bg-black/90 flex items-center justify-center"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">

                {{-- Close --}}
                <button @click="close()" class="absolute top-4 right-4 z-10 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                {{-- Counter --}}
                <div class="absolute top-4 left-1/2 -translate-x-1/2 text-white/70 text-sm select-none">
                    <span x-text="lightboxIndex + 1"></span> / <span x-text="images.length"></span>
                </div>

                {{-- Prev --}}
                <button @click="prev()" x-show="images.length > 1"
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 flex items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/25 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                {{-- Image --}}
                <div class="w-full h-full flex items-center justify-center p-16">
                    <img :src="images[lightboxIndex]" class="max-h-full max-w-full rounded object-contain select-none">
                </div>

                {{-- Next --}}
                <button @click="next()" x-show="images.length > 1"
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 flex items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/25 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                {{-- Thumbnail strip --}}
                @if($imageCount > 1)
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 max-w-[90vw] overflow-x-auto pb-1">
                        @foreach($imageUrls as $idx => $url)
                            <button @click="lightboxIndex = {{ $idx }}"
                                class="w-14 h-10 flex-shrink-0 rounded overflow-hidden border-2 transition-colors"
                                :class="lightboxIndex === {{ $idx }} ? 'border-white' : 'border-transparent opacity-60 hover:opacity-90'">
                                <img src="{{ $url }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <div class="h-64 lg:h-80 bg-subtle rounded-sm flex items-center justify-center">
                <svg class="w-16 h-16 text-dim/20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-3m0 0l7-4 7 4M5 7v10a1 1 0 001 1h12a1 1 0 001-1V7"/>
                </svg>
            </div>
        @endif
    </div>

    {{-- Main content: info left + booking right --}}
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-10">

        {{-- Left: property info --}}
        <div class="space-y-8">

            {{-- Key stats --}}
            <div class="flex flex-wrap items-center gap-5 pb-6 border-b border-line">
                @if($property->bedrooms)
                    <div class="flex items-center gap-2 text-sm text-foreground">
                        {{-- Bed icon --}}
                        <svg class="w-4 h-4 text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.5V19m0-9.5h18m-18 0V7a2 2 0 012-2h4a2 2 0 012 2v2.5m6-2.5V7a2 2 0 00-2-2h-4a2 2 0 00-2 2v2.5M3 19h18M3 19v-3a1 1 0 011-1h16a1 1 0 011 1v3"/>
                        </svg>
                        <span><strong class="font-semibold">{{ $property->bedrooms }}</strong> {{ Str::plural('bedroom', $property->bedrooms) }}</span>
                    </div>
                @endif
                @if($property->bathrooms)
                    <div class="flex items-center gap-2 text-sm text-foreground">
                        {{-- Shower / bath icon --}}
                        <svg class="w-4 h-4 text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h18M3 13v5a2 2 0 002 2h14a2 2 0 002-2v-5M3 13H2m1 0V9a5 5 0 015-5h1m-1 5H4m15 4h1m-1 0V9a1 1 0 00-1-1h-1"/>
                        </svg>
                        <span><strong class="font-semibold">{{ $property->bathrooms }}</strong> {{ Str::plural('bathroom', $property->bathrooms) }}</span>
                    </div>
                @endif
                @if($property->area)
                    <div class="flex items-center gap-2 text-sm text-foreground">
                        {{-- Ruler / area icon --}}
                        <svg class="w-4 h-4 text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5a2 2 0 012-2h14a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2v-9zm3 5h2m2 0h2m2 0h2M6 9.5v1m4-1v1m4-1v1"/>
                        </svg>
                        <span><strong class="font-semibold">{{ $property->area }}</strong> sqm</span>
                    </div>
                @endif
            </div>

            {{-- Host info --}}
            @if($property->user)
                <div class="flex items-center gap-3 pb-6 border-b border-line">
                    <div class="w-10 h-10 rounded-full bg-foreground flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-semibold text-on-primary font-serif">{{ strtoupper(substr($property->user->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-foreground">Hosted by {{ $property->user->name }}</p>
                        <p class="text-xs text-dim">Property Owner</p>
                    </div>
                </div>
            @endif

            {{-- Description --}}
            @if($property->description)
                <div class="pb-6 border-b border-line">
                    <h2 class="text-base font-semibold text-foreground font-serif mb-3">About this property</h2>
                    <p class="text-sm text-dim leading-relaxed">{{ $property->description }}</p>
                </div>
            @endif

            {{-- Amenities --}}
            @if($property->amenities)
                @php
                    $amenities = is_array($property->amenities)
                        ? $property->amenities
                        : (is_string($property->amenities) && str_starts_with(trim($property->amenities), '[')
                            ? json_decode($property->amenities, true)
                            : array_filter(array_map('trim', explode(',', $property->amenities)))
                        );
                @endphp
                @if(count($amenities ?? []))
                    <div class="pb-6 border-b border-line">
                        <h2 class="text-base font-semibold text-foreground font-serif mb-4">What this place offers</h2>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($amenities as $amenity)
                                <div class="flex items-center gap-2.5 text-sm text-foreground">
                                    <svg class="w-4 h-4 text-accent flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                    </svg>
                                    {{ $amenity }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

            {{-- Location --}}
            @if($property->address)
                <div>
                    <h2 class="text-base font-semibold text-foreground font-serif mb-4">Location</h2>
                    @php
                            $addr     = $property->address;
                            $barangay = $addr?->barangay;
                            $city     = $barangay?->city;
                            $province = $city?->province;
                        @endphp
                        <div class="rounded-sm border border-line bg-subtle/30 p-4 text-sm space-y-2">
                        @if($addr?->street)
                            <div class="flex items-start gap-2">
                                <span class="text-dim text-[10px] uppercase tracking-wider w-20 shrink-0 mt-0.5">Street</span>
                                <span class="text-foreground">{{ $addr->street }}</span>
                            </div>
                        @endif
                        @if($barangay)
                            <div class="flex items-start gap-2">
                                <span class="text-dim text-[10px] uppercase tracking-wider w-20 shrink-0 mt-0.5">Barangay</span>
                                <span class="text-foreground">{{ $barangay->name }}</span>
                            </div>
                        @endif
                        @if($city)
                            <div class="flex items-start gap-2">
                                <span class="text-dim text-[10px] uppercase tracking-wider w-20 shrink-0 mt-0.5">City</span>
                                <span class="text-foreground">{{ $city->name }}</span>
                            </div>
                        @endif
                        @if($province)
                            <div class="flex items-start gap-2">
                                <span class="text-dim text-[10px] uppercase tracking-wider w-20 shrink-0 mt-0.5">Province</span>
                                <span class="text-foreground">{{ $province->name }}</span>
                            </div>
                        @endif
                        @if($addr?->zip_code)
                            <div class="flex items-start gap-2">
                                <span class="text-dim text-[10px] uppercase tracking-wider w-20 shrink-0 mt-0.5">ZIP</span>
                                <span class="text-foreground">{{ $addr->zip_code }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>

        {{-- Right: sticky booking card --}}
        <div class="lg:sticky lg:top-20 self-start">
            <div class="border border-line bg-card rounded-sm p-6 space-y-5" style="box-shadow: var(--shadow-lg);">

                {{-- Price --}}
                <div class="flex items-end justify-between pb-5 border-b border-line">
                    <div>
                        <p class="text-[10px] text-dim uppercase tracking-wider mb-1">Monthly Rate</p>
                        <p class="text-3xl font-bold text-foreground font-serif tracking-tight">
                            ₱{{ number_format($property->price, 0) }}
                            <span class="text-sm font-normal text-dim font-sans">/mo</span>
                        </p>
                    </div>
                    {{-- Favourite --}}
                    <button wire:click="toggleFavorite"
                        class="w-9 h-9 flex items-center justify-center rounded-full border transition-all
                            {{ $isFavorited ? 'bg-red-500 border-red-500 text-white' : 'border-line text-dim hover:border-foreground hover:text-foreground' }}">
                        <svg class="w-4 h-4" fill="{{ $isFavorited ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>
                </div>

                @if(!$showReservation)
                    {{-- CTA buttons --}}
                    <div class="space-y-2.5">
                        <button wire:click="$set('showReservation', true)"
                            class="w-full py-3 bg-foreground text-on-primary rounded-sm font-semibold text-sm hover:opacity-90 transition-all">
                            Reserve Now
                        </button>
                        <button wire:click="$set('showInquiry', true)"
                            class="block w-full py-3 border border-foreground text-foreground rounded-sm font-semibold text-sm text-center hover:bg-foreground hover:text-on-primary transition-all">
                            Send Inquiry
                        </button>
                    </div>
                    <p class="text-xs text-dim text-center">You won't be charged yet</p>
                @else
                    {{-- Reservation form --}}
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <button wire:click="$set('showReservation', false)" class="text-dim hover:text-foreground transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <h3 class="text-sm font-semibold text-foreground font-serif">Reserve This Property</h3>
                        </div>
                        <form wire:submit="submitReservation" class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-semibold text-dim uppercase tracking-wider mb-1.5">
                                    Move-in Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" wire:model.live="moveInDate" min="{{ date('Y-m-d') }}"
                                    class="w-full px-3 py-2 rounded-sm border border-line bg-page text-foreground text-sm focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground">
                                @error('moveInDate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold text-dim uppercase tracking-wider mb-1.5">
                                    Move-out Date <span class="text-dim/50 font-normal normal-case">(optional)</span>
                                </label>
                                <input type="date" wire:model.live="moveOutDate" min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                    class="w-full px-3 py-2 rounded-sm border border-line bg-page text-foreground text-sm focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground">
                                @error('moveOutDate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Live price estimate --}}
                            @if($moveInDate)
                                @php
                                    $months = 1;
                                    if ($moveOutDate) {
                                        $diff = \Carbon\Carbon::parse($moveInDate)->diffInMonths(\Carbon\Carbon::parse($moveOutDate));
                                        $months = max(1, (int) $diff);
                                    }
                                @endphp
                                <div class="rounded-sm border border-line bg-subtle/50 p-3">
                                    <p class="text-[10px] text-dim uppercase tracking-wider mb-1">Estimated Total</p>
                                    <p class="text-xl font-bold text-foreground font-serif">₱{{ number_format($property->price * $months, 2) }}</p>
                                    <p class="text-xs text-dim mt-0.5">₱{{ number_format($property->price, 0) }}/mo × {{ $months }} {{ Str::plural('month', $months) }}</p>
                                </div>
                            @endif

                            <div>
                                <label class="block text-[10px] font-semibold text-dim uppercase tracking-wider mb-1.5">
                                    Notes <span class="text-dim/50 font-normal normal-case">(optional)</span>
                                </label>
                                <textarea wire:model="reservationNotes" rows="2" placeholder="Any special requests…"
                                    class="w-full px-3 py-2 rounded-sm border border-line bg-page text-foreground text-sm placeholder-dim/50 focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground resize-none"></textarea>
                                @error('reservationNotes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex gap-2 pt-1">
                                <button type="submit"
                                    class="flex-1 py-2.5 bg-foreground text-on-primary rounded-sm font-semibold text-sm hover:opacity-90 transition-all">
                                    Confirm Reservation
                                </button>
                                <button type="button" wire:click="$set('showReservation', false)"
                                    class="flex-1 py-2.5 border border-line text-dim rounded-sm text-sm hover:text-foreground hover:border-foreground transition-all">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Reviews --}}
    @php $reviews = $property->reviews->where('is_verified', true)->sortByDesc('created_at'); @endphp
    @if($reviews->count())
        <div class="mt-12 pt-8 border-t border-line">
            {{-- Header --}}
            <div class="flex items-center gap-4 mb-6">
                <h2 class="text-xl font-semibold text-foreground font-serif">Reviews</h2>
                @php
                    $avgRating = round($reviews->avg('rating'), 1);
                    $count     = $reviews->count();
                @endphp
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-0.5">
                        @for($s = 1; $s <= 5; $s++)
                            <svg class="w-4 h-4 {{ $s <= round($avgRating) ? 'text-yellow-400' : 'text-dim/20' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-sm font-semibold text-foreground">{{ $avgRating }}</span>
                    <span class="text-sm text-dim">({{ $count }} {{ Str::plural('review', $count) }})</span>
                </div>
            </div>

            {{-- Review cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($reviews as $review)
                    <div class="border border-line rounded-sm p-5 bg-card space-y-3">
                        {{-- Reviewer + rating --}}
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-foreground flex items-center justify-center flex-shrink-0">
                                    <span class="text-sm font-semibold text-on-primary font-serif">
                                        {{ strtoupper(substr($review->renter->name ?? '?', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-foreground">{{ $review->renter->name ?? 'Anonymous' }}</p>
                                    <p class="text-xs text-dim">{{ $review->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-0.5 flex-shrink-0">
                                @for($s = 1; $s <= 5; $s++)
                                    <svg class="w-3.5 h-3.5 {{ $s <= $review->rating ? 'text-yellow-400' : 'text-dim/20' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>

                        {{-- Comment --}}
                        @if($review->comment)
                            <p class="text-sm text-dim leading-relaxed">{{ $review->comment }}</p>
                        @endif

                        {{-- Pros & Cons --}}
                        @if(!empty($review->pros) || !empty($review->cons))
                            <div class="grid grid-cols-2 gap-3 pt-2 border-t border-line">
                                @if(!empty($review->pros))
                                    <div>
                                        <p class="text-[10px] font-semibold uppercase tracking-wider text-green-600 dark:text-green-400 mb-1.5">Pros</p>
                                        <ul class="space-y-1">
                                            @foreach($review->pros as $pro)
                                                <li class="flex items-start gap-1.5 text-xs text-foreground">
                                                    <svg class="w-3.5 h-3.5 text-green-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                                    </svg>
                                                    {{ $pro }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if(!empty($review->cons))
                                    <div>
                                        <p class="text-[10px] font-semibold uppercase tracking-wider text-red-500 mb-1.5">Cons</p>
                                        <ul class="space-y-1">
                                            @foreach($review->cons as $con)
                                                <li class="flex items-start gap-1.5 text-xs text-foreground">
                                                    <svg class="w-3.5 h-3.5 text-red-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    {{ $con }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Inquiry Modal --}}
    @if($showInquiry)
        <div class="fixed inset-0 bg-foreground/40 backdrop-blur-sm z-50 flex items-center justify-center p-4"
             wire:click.self="$set('showInquiry', false)">
            <div class="bg-card border border-line rounded-sm max-w-lg w-full relative"
                 @click.stop style="box-shadow: var(--shadow-lg);">
                <button wire:click="$set('showInquiry', false)"
                    class="absolute top-3 right-3 text-dim hover:text-foreground transition-colors p-1">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-foreground mb-1">Send an Inquiry</h2>
                    <p class="text-sm text-dim mb-4">{{ $property->title }}</p>

                    @if($property->images->isNotEmpty())
                        <div class="rounded-sm overflow-hidden mb-4 h-32 bg-subtle">
                            <img src="{{ asset('storage/' . $property->images->first()->image_path) }}"
                                 alt="{{ $property->title }}"
                                 class="w-full h-full object-cover">
                        </div>
                    @endif

                    <form wire:submit.prevent="submitInquiry" class="space-y-3">
                        <div>
                            <textarea wire:model="inquiryMessage" rows="4"
                                placeholder="Write your message to the landlord..."
                                class="w-full px-3 py-2 text-sm rounded-sm border border-line bg-subtle text-foreground placeholder:text-dim focus:outline-none focus:border-foreground resize-none"></textarea>
                            @error('inquiryMessage')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex gap-3 pt-1">
                            <button type="submit"
                                class="flex-1 py-2.5 bg-foreground text-on-primary rounded-sm text-sm font-semibold hover:opacity-90 transition-all">
                                Send Inquiry
                            </button>
                            <button type="button" wire:click="$set('showInquiry', false)"
                                class="flex-1 py-2.5 border border-line text-foreground rounded-sm text-sm font-semibold hover:bg-subtle transition-all">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
