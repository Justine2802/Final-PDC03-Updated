<div>
    <x-slot:header>
        <a href="{{ route('admin.properties') }}" wire:navigate class="text-dim hover:text-foreground transition-colors">Properties</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-dim/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
        <span class="font-medium text-foreground truncate max-w-xs">{{ $property->title }}</span>
    </x-slot:header>

    {{-- Page header --}}
    <div class="mb-5">
        <a href="{{ route('admin.properties') }}" wire:navigate
            class="inline-flex items-center gap-1.5 text-sm text-dim hover:text-foreground transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Properties
        </a>
    </div>

    <div class="flex items-start justify-between mb-6 gap-4">
        <div>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl font-semibold text-foreground font-serif tracking-tight">{{ $property->title }}</h1>
                <span class="inline-flex items-center rounded-sm px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider
                    {{ $property->status ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                    {{ $property->status ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="flex flex-wrap items-center gap-2 mt-1 text-sm text-dim">
                <span class="font-medium text-accent uppercase tracking-wider text-[10px]">{{ $property->propertyType->name ?? 'Property' }}</span>
                @php
                    $hdrBrgy = $property->address?->barangay;
                    $hdrCity = $hdrBrgy?->city;
                @endphp
                @if($hdrCity)
                    <span class="text-dim/40">·</span>
                    <span>{{ $hdrCity->name }}, {{ $hdrCity->province?->name }}</span>
                @endif
                <span class="text-dim/40">·</span>
                <span>Created {{ $property->created_at->format('M d, Y') }}</span>
                <span class="text-dim/40">·</span>
                <span>Owned by <strong class="font-medium text-foreground">{{ $property->user->name ?? 'Unknown' }}</strong></span>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <button wire:click="openEdit"
                class="inline-flex items-center gap-1.5 rounded-sm bg-foreground px-3 py-2 text-sm font-medium text-on-primary hover:opacity-90 transition-all">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                </svg>
                Edit
            </button>
            <button wire:click="$set('showDeleteConfirm', true)"
                class="inline-flex items-center gap-1.5 rounded-sm border border-red-200 dark:border-red-500/30 px-3 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                </svg>
                Delete
            </button>
        </div>
    </div>

    {{-- Image gallery --}}
    @php
        $images    = $property->images;
        $imageUrls = $images->map(fn($i) => asset('storage/' . $i->image_path))->values()->toArray();
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
            @if($imageCount === 1)
                <div class="h-80 lg:h-[440px]">
                    <button @click="open(0)" class="w-full h-full relative group overflow-hidden bg-subtle block">
                        <img src="{{ $imageUrls[0] }}" alt="{{ $property->title }}"
                             class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300">
                        <div class="absolute bottom-3 right-3 bg-black/50 text-white text-xs px-2 py-1 rounded">1 photo</div>
                    </button>
                </div>
            @elseif($imageCount === 2)
                <div class="grid grid-cols-2 gap-1.5 h-80 lg:h-[440px]">
                    @foreach($imageUrls as $idx => $url)
                        <button @click="open({{ $idx }})" class="relative group overflow-hidden bg-subtle">
                            <img src="{{ $url }}" alt="Property photo"
                                 class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300">
                        </button>
                    @endforeach
                </div>
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

                <button @click="close()" class="absolute top-4 right-4 z-10 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div class="absolute top-4 left-1/2 -translate-x-1/2 text-white/70 text-sm select-none">
                    <span x-text="lightboxIndex + 1"></span> / <span x-text="images.length"></span>
                </div>
                <button @click="prev()" x-show="images.length > 1"
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 flex items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/25 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <div class="w-full h-full flex items-center justify-center p-16">
                    <img :src="images[lightboxIndex]" class="max-h-full max-w-full rounded object-contain select-none">
                </div>
                <button @click="next()" x-show="images.length > 1"
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 flex items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/25 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif
    </div>

    {{-- Main content: info left + details right --}}
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-10">

        {{-- Left: property info --}}
        <div class="space-y-8">

            {{-- Key stats --}}
            <div class="flex flex-wrap items-center gap-5 pb-6 border-b border-line">
                <div class="flex items-center gap-2 text-sm text-foreground">
                    <svg class="w-4 h-4 text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.5V19m0-9.5h18m-18 0V7a2 2 0 012-2h4a2 2 0 012 2v2.5m6-2.5V7a2 2 0 00-2-2h-4a2 2 0 00-2 2v2.5M3 19h18M3 19v-3a1 1 0 011-1h16a1 1 0 011 1v3"/>
                    </svg>
                    <span><strong class="font-semibold">{{ $property->bedrooms }}</strong> {{ Str::plural('bedroom', $property->bedrooms) }}</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-foreground">
                    <svg class="w-4 h-4 text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h18M3 13v5a2 2 0 002 2h14a2 2 0 002-2v-5M3 13H2m1 0V9a5 5 0 015-5h1m-1 5H4m15 4h1m-1 0V9a1 1 0 00-1-1h-1"/>
                    </svg>
                    <span><strong class="font-semibold">{{ $property->bathrooms }}</strong> {{ Str::plural('bathroom', $property->bathrooms) }}</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-foreground">
                    <svg class="w-4 h-4 text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5a2 2 0 012-2h14a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2v-9zm3 5h2m2 0h2m2 0h2M6 9.5v1m4-1v1m4-1v1"/>
                    </svg>
                    <span><strong class="font-semibold">{{ $property->area }}</strong> sqm</span>
                </div>
            </div>

            {{-- Owner --}}
            @if($property->user)
                <div class="flex items-center gap-3 pb-6 border-b border-line">
                    <div class="w-10 h-10 rounded-full bg-foreground flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-semibold text-on-primary font-serif">{{ strtoupper(substr($property->user->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-foreground">{{ $property->user->name }}</p>
                        <p class="text-xs text-dim">{{ $property->user->email }}</p>
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
            @php
                $amenities = is_array($property->amenities)
                    ? $property->amenities
                    : (is_string($property->amenities) && str_starts_with(trim($property->amenities ?? ''), '[')
                        ? json_decode($property->amenities, true)
                        : array_filter(array_map('trim', explode(',', $property->amenities ?? '')))
                    );
            @endphp
            @if(!empty($amenities))
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

            {{-- Location --}}
            @php
                $addr = $property->address;
                $brgy = $addr?->barangay;
                $city = $brgy?->city;
                $prov = $city?->province;
                $reg  = $prov?->region;
            @endphp
            @if($addr)
                <div>
                    <h2 class="text-base font-semibold text-foreground font-serif mb-4">Location</h2>
                    <div class="rounded-sm border border-line bg-subtle/30 p-4 text-sm space-y-2">
                        @foreach([
                            'Region'   => $reg?->name,
                            'Province' => $prov?->name,
                            'City'     => $city?->name,
                            'Barangay' => $brgy?->name,
                            'Street'   => $addr->street,
                            'ZIP'      => $addr->zip_code,
                        ] as $label => $value)
                            @if($value)
                                <div class="flex items-start gap-2">
                                    <span class="text-dim text-[10px] uppercase tracking-wider w-20 shrink-0 mt-0.5">{{ $label }}</span>
                                    <span class="text-foreground">{{ $value }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- Right: sticky info card --}}
        <div class="lg:sticky lg:top-20 self-start space-y-4">

            {{-- Price card --}}
            <div class="border border-line bg-card rounded-sm p-6" style="box-shadow: var(--shadow-lg);">
                <p class="text-[10px] text-dim uppercase tracking-wider mb-1">Monthly Rate</p>
                <p class="text-3xl font-bold text-foreground font-serif tracking-tight mb-5">
                    ₱{{ number_format($property->price, 2) }}
                    <span class="text-sm font-normal text-dim font-sans">/mo</span>
                </p>

                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="bg-subtle/50 rounded-sm p-3">
                        <p class="text-[10px] text-dim uppercase tracking-wider mb-1">Bedrooms</p>
                        <p class="font-semibold text-foreground">{{ $property->bedrooms }}</p>
                    </div>
                    <div class="bg-subtle/50 rounded-sm p-3">
                        <p class="text-[10px] text-dim uppercase tracking-wider mb-1">Bathrooms</p>
                        <p class="font-semibold text-foreground">{{ $property->bathrooms }}</p>
                    </div>
                    <div class="bg-subtle/50 rounded-sm p-3">
                        <p class="text-[10px] text-dim uppercase tracking-wider mb-1">Area</p>
                        <p class="font-semibold text-foreground">{{ $property->area }} sqm</p>
                    </div>
                    <div class="bg-subtle/50 rounded-sm p-3">
                        <p class="text-[10px] text-dim uppercase tracking-wider mb-1">Type</p>
                        <p class="font-semibold text-foreground">{{ $property->propertyType->name ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Reviews summary --}}
            @if($property->reviews->count())
                <div class="rounded-sm border border-line bg-card p-5" style="box-shadow: var(--shadow-xs);">
                    <h2 class="text-sm font-semibold text-foreground font-serif mb-3">Reviews</h2>
                    <div class="flex items-center gap-3">
                        <span class="text-2xl font-bold text-foreground font-serif">{{ round($property->reviews->avg('rating'), 1) }}</span>
                        <div>
                            <div class="flex gap-0.5">
                                @php $avg = round($property->reviews->avg('rating')); @endphp
                                @for($s = 1; $s <= 5; $s++)
                                    <svg class="w-3.5 h-3.5 {{ $s <= $avg ? 'text-yellow-400' : 'text-dim/20' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-xs text-dim mt-0.5">{{ $property->reviews->count() }} {{ Str::plural('review', $property->reviews->count()) }}</p>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Delete confirmation --}}
    @if($showDeleteConfirm)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-foreground/40 backdrop-blur-sm" wire:click="$set('showDeleteConfirm', false)"></div>
            <div class="relative w-full max-w-sm rounded-sm border border-line bg-card p-6" style="box-shadow: var(--shadow-lg);">
                <h3 class="text-base font-semibold text-foreground font-serif mb-2">Delete property?</h3>
                <p class="text-sm text-dim mb-5">This will permanently delete <strong class="text-foreground">{{ $property->title }}</strong> and all its images. This cannot be undone.</p>
                <div class="flex gap-2 justify-end">
                    <button wire:click="$set('showDeleteConfirm', false)"
                        class="rounded-sm border border-line px-4 py-2 text-sm font-medium text-dim hover:text-foreground hover:bg-subtle transition-colors">Cancel</button>
                    <button wire:click="delete"
                        class="rounded-sm bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition-colors">Delete Property</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Edit Wizard Modal --}}
    @if($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-foreground/40 backdrop-blur-sm" wire:click="closeEdit"></div>
            <div class="relative w-full max-w-4xl max-h-[90vh] overflow-hidden rounded-sm border border-line bg-card flex flex-col" style="box-shadow: var(--shadow-lg);">

                {{-- Header --}}
                <div class="flex items-center justify-between p-4 border-b border-line shrink-0">
                    <h3 class="text-base font-semibold text-foreground font-serif tracking-tight">Edit Property</h3>
                    <button wire:click="closeEdit" class="text-dim hover:text-foreground transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                {{-- Body: Sidebar + Content --}}
                <div class="flex flex-1 overflow-hidden">
                    {{-- Step Sidebar --}}
                    <nav class="hidden sm:flex w-56 shrink-0 flex-col gap-1 p-4 border-r border-line bg-subtle/30 overflow-y-auto">
                        @php
                            $steps = [
                                1 => ['label' => 'Property Type', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                                2 => ['label' => 'Location', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                                3 => ['label' => 'Photos', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                                4 => ['label' => 'Property Details', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                                5 => ['label' => 'Price', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ];
                        @endphp

                        @foreach($steps as $num => $step)
                            <button wire:click="goToStep({{ $num }})" type="button"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm transition-colors text-left
                                    {{ $currentStep === $num ? 'bg-foreground/5 text-foreground font-medium' : ($currentStep > $num ? 'text-foreground' : 'text-dim') }}
                                    hover:bg-subtle">
                                @if($currentStep > $num)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                @elseif($currentStep === $num)
                                    <span class="flex items-center justify-center h-5 w-5 rounded-full bg-foreground text-on-primary text-xs font-bold shrink-0">{{ $num }}</span>
                                @else
                                    <span class="flex items-center justify-center h-5 w-5 rounded-full border border-line text-dim text-xs shrink-0">{{ $num }}</span>
                                @endif
                                {{ $step['label'] }}
                            </button>
                        @endforeach
                    </nav>

                    {{-- Step Content --}}
                    <div class="flex-1 overflow-y-auto p-6">
                        {{-- Mobile Step Indicator --}}
                        <div class="sm:hidden flex items-center gap-2 mb-4">
                            @for($i = 1; $i <= $totalSteps; $i++)
                                <div class="flex-1 h-1.5 rounded-full {{ $i <= $currentStep ? 'bg-foreground' : 'bg-subtle' }}"></div>
                            @endfor
                        </div>

                        {{-- Step 1: Property Type --}}
                        @if($currentStep === 1)
                            <h2 class="text-lg font-semibold text-foreground font-serif tracking-tight mb-6">Property Type</h2>

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[10px] text-dim uppercase tracking-wider mb-1 font-semibold">Title <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="title" placeholder="Enter property title..."
                                        class="w-full rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground placeholder-dim/50 focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground" />
                                    @error('title') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-[10px] text-dim uppercase tracking-wider mb-2 font-semibold">Property Type <span class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                        @foreach($propertyTypes as $type)
                                            <label @click="$wire.set('property_type_id', '{{ $type->id }}', false)"
                                                class="flex flex-col items-center gap-2 p-4 rounded-sm border-2 cursor-pointer transition-colors"
                                                :class="$wire.property_type_id == '{{ $type->id }}' ? 'border-foreground bg-foreground/5' : 'border-line hover:border-dim'">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" :class="$wire.property_type_id == '{{ $type->id }}' ? 'text-foreground' : 'text-dim'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                                <span class="text-sm font-medium" :class="$wire.property_type_id == '{{ $type->id }}' ? 'text-foreground' : 'text-dim'">{{ $type->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('property_type_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-[10px] text-dim uppercase tracking-wider mb-2 font-semibold">Status</label>
                                    <div class="flex gap-3">
                                        <button type="button" @click="$wire.set('status', true, false)"
                                            class="flex-1 py-2.5 rounded-sm border-2 text-sm font-medium transition-colors"
                                            :class="$wire.status ? 'border-green-500 bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'border-line text-dim hover:border-dim'">
                                            Active
                                        </button>
                                        <button type="button" @click="$wire.set('status', false, false)"
                                            class="flex-1 py-2.5 rounded-sm border-2 text-sm font-medium transition-colors"
                                            :class="!$wire.status ? 'border-red-500 bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' : 'border-line text-dim hover:border-dim'">
                                            Inactive
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Step 2: Location --}}
                        @if($currentStep === 2)
                            <h2 class="text-lg font-semibold text-foreground font-serif tracking-tight mb-6">Location</h2>

                            <div class="space-y-5">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] text-dim uppercase tracking-wider mb-1 font-semibold">Region <span class="text-red-500">*</span></label>
                                        <select wire:model.live="region_id"
                                            class="w-full rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground">
                                            <option value="">Select Region</option>
                                            @foreach($regions as $region)
                                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('region_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-[10px] text-dim uppercase tracking-wider mb-1 font-semibold">Province <span class="text-red-500">*</span></label>
                                        <select wire:model.live="province_id" @disabled(!$region_id)
                                            class="w-full rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground disabled:opacity-50 disabled:cursor-not-allowed">
                                            <option value="">{{ $region_id ? 'Select Province' : 'Select Region first' }}</option>
                                            @foreach($provinces as $province)
                                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('province_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] text-dim uppercase tracking-wider mb-1 font-semibold">City / Municipality <span class="text-red-500">*</span></label>
                                        <select wire:model.live="city_id" @disabled(!$province_id)
                                            class="w-full rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground disabled:opacity-50 disabled:cursor-not-allowed">
                                            <option value="">{{ $province_id ? 'Select City' : 'Select Province first' }}</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('city_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-[10px] text-dim uppercase tracking-wider mb-1 font-semibold">Barangay <span class="text-red-500">*</span></label>
                                        <select wire:model="barangay_id" @disabled(!$city_id)
                                            class="w-full rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground disabled:opacity-50 disabled:cursor-not-allowed">
                                            <option value="">{{ $city_id ? 'Select Barangay' : 'Select City first' }}</option>
                                            @foreach($barangays as $barangay)
                                                <option value="{{ $barangay->id }}">{{ $barangay->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('barangay_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="sm:col-span-2">
                                        <label class="block text-[10px] text-dim uppercase tracking-wider mb-1 font-semibold">Street Address</label>
                                        <input type="text" wire:model="street" placeholder="e.g. 123 Rizal Street..."
                                            class="w-full rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground placeholder-dim/50 focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground" />
                                        @error('street') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-[10px] text-dim uppercase tracking-wider mb-1 font-semibold">Zip Code</label>
                                        <input type="text" wire:model="zip_code" placeholder="e.g. 1000"
                                            class="w-full rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground placeholder-dim/50 focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground" />
                                        @error('zip_code') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Step 3: Photos --}}
                        @if($currentStep === 3)
                            <h2 class="text-lg font-semibold text-foreground font-serif tracking-tight mb-2">Photos</h2>
                            <p class="text-sm text-dim mb-6">Max file size: 5MB. Formats: jpeg, jpg, png, webp.</p>

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach($existingPhotos as $idx => $photo)
                                    <div class="relative group aspect-[4/3] rounded-sm overflow-hidden border border-line bg-subtle">
                                        <img src="{{ asset('storage/' . $photo['path']) }}" class="w-full h-full object-cover" />
                                        @if($idx === 0)
                                            <span class="absolute top-2 left-2 bg-foreground text-on-primary text-xs font-medium px-2 py-0.5 rounded-sm">Cover</span>
                                        @endif
                                        <button type="button" wire:click="removeExistingPhoto({{ $photo['id'] }})"
                                            class="absolute top-2 right-2 h-7 w-7 flex items-center justify-center rounded-full bg-red-600 text-white opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                @endforeach

                                @foreach($photos as $idx => $photo)
                                    <div class="relative group aspect-[4/3] rounded-sm overflow-hidden border border-line bg-subtle">
                                        <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover" />
                                        @if(count($existingPhotos) === 0 && $idx === 0)
                                            <span class="absolute top-2 left-2 bg-foreground text-on-primary text-xs font-medium px-2 py-0.5 rounded-sm">Cover</span>
                                        @endif
                                        <button type="button" wire:click="removePhoto({{ $idx }})"
                                            class="absolute top-2 right-2 h-7 w-7 flex items-center justify-center rounded-full bg-red-600 text-white opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                @endforeach

                                <label class="aspect-[4/3] rounded-sm border-2 border-dashed border-line hover:border-dim bg-subtle/50 flex flex-col items-center justify-center cursor-pointer transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-dim mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                    <span class="text-sm text-dim">Upload photos</span>
                                    <input type="file" wire:model="photos" multiple accept="image/*" class="hidden" />
                                </label>
                            </div>

                            @error('photos.*') <span class="text-xs text-red-500 mt-2 block">{{ $message }}</span> @enderror

                            <div wire:loading wire:target="photos" class="mt-3 text-sm text-dim">
                                Uploading...
                            </div>
                        @endif

                        {{-- Step 4: Property Details --}}
                        @if($currentStep === 4)
                            <h2 class="text-lg font-semibold text-foreground font-serif tracking-tight mb-6">Property Details</h2>

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[10px] text-dim uppercase tracking-wider mb-1 font-semibold">Description <span class="text-red-500">*</span></label>
                                    <textarea wire:model="description" rows="4" placeholder="Describe the property..."
                                        class="w-full rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground placeholder-dim/50 focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground"></textarea>
                                    @error('description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-[10px] text-dim uppercase tracking-wider mb-1 font-semibold">Area (sqm) <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model="area" placeholder="sq.m."
                                        class="w-full sm:w-1/2 rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground placeholder-dim/50 focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground" />
                                    @error('area') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-[10px] text-dim uppercase tracking-wider mb-2 font-semibold">Bedrooms <span class="text-red-500">*</span></label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(['0', '1', '2', '3', '4', '5'] as $val)
                                            <button type="button" @click="$wire.set('bedrooms', '{{ $val }}', false)"
                                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-sm border-2 text-sm font-medium transition-colors"
                                                :class="$wire.bedrooms === '{{ $val }}' ? 'border-foreground bg-foreground/5 text-foreground' : 'border-line text-dim hover:border-dim'">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                                {{ $val === '0' ? 'Any' : $val }}
                                            </button>
                                        @endforeach
                                    </div>
                                    @error('bedrooms') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-[10px] text-dim uppercase tracking-wider mb-2 font-semibold">Bathrooms <span class="text-red-500">*</span></label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(['0', '1', '2', '3', '4', '5'] as $val)
                                            <button type="button" @click="$wire.set('bathrooms', '{{ $val }}', false)"
                                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-sm border-2 text-sm font-medium transition-colors"
                                                :class="$wire.bathrooms === '{{ $val }}' ? 'border-foreground bg-foreground/5 text-foreground' : 'border-line text-dim hover:border-dim'">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" /></svg>
                                                {{ $val === '0' ? 'Any' : $val }}
                                            </button>
                                        @endforeach
                                    </div>
                                    @error('bathrooms') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-[10px] text-dim uppercase tracking-wider mb-1 font-semibold">Amenities</label>
                                    <textarea wire:model="amenities" rows="2" placeholder="e.g. Pool, Gym, Parking, WiFi..."
                                        class="w-full rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground placeholder-dim/50 focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground"></textarea>
                                    @error('amenities') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif

                        {{-- Step 5: Price --}}
                        @if($currentStep === 5)
                            <h2 class="text-lg font-semibold text-foreground font-serif tracking-tight mb-6">Price</h2>

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[10px] text-dim uppercase tracking-wider mb-1 font-semibold">Price <span class="text-red-500">*</span></label>
                                    <div class="relative w-full sm:w-1/2">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-dim text-sm">&#8369;</span>
                                        <input type="number" step="0.01" wire:model="price" placeholder="Set a fair price"
                                            class="w-full rounded-sm border border-line bg-page pl-8 pr-3 py-2 text-sm text-foreground placeholder-dim/50 focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground" />
                                    </div>
                                    @error('price') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Footer Navigation --}}
                <div class="flex items-center justify-between p-4 border-t border-line shrink-0">
                    @if($currentStep > 1)
                        <button type="button" wire:click="prevStep"
                            class="inline-flex items-center gap-1.5 rounded-sm border border-line px-4 py-2 text-sm font-medium text-dim hover:bg-subtle hover:text-foreground transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                            Back
                        </button>
                    @else
                        <div></div>
                    @endif

                    @if($currentStep < $totalSteps)
                        <button type="button" wire:click="nextStep"
                            class="inline-flex items-center gap-1.5 rounded-sm bg-foreground px-5 py-2 text-sm font-medium text-on-primary hover:opacity-90 transition-all">
                            Next
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </button>
                    @else
                        <button type="button" wire:click="save"
                            class="inline-flex items-center gap-1.5 rounded-sm bg-foreground px-5 py-2 text-sm font-medium text-on-primary hover:opacity-90 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Update Property
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
