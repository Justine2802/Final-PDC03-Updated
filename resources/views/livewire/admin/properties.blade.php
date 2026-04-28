<div>
    <x-slot:header>
        <span class="font-medium text-foreground">Properties</span>
    </x-slot:header>

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-foreground font-serif tracking-tight">Properties</h1>
            <p class="text-xs text-dim mt-1">Manage all listed properties.</p>
        </div>
        <button wire:click="create"
            class="inline-flex items-center gap-1.5 rounded-sm bg-foreground px-4 py-2 text-sm font-medium text-on-primary hover:opacity-90 transition-all" style="box-shadow: var(--shadow-xs);">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            Add Property
        </button>
    </div>

    <div class="rounded-sm border border-line bg-card" x-data="{ showFilters: false }" style="box-shadow: var(--shadow-xs);">
        {{-- Toolbar --}}
        <div class="p-4 border-b border-line flex flex-col sm:flex-row sm:items-center gap-3">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search properties..."
                class="w-full sm:max-w-xs rounded-sm border border-line bg-page px-3 py-2 text-sm text-foreground placeholder-dim/50 focus:border-foreground focus:outline-none focus:ring-1 focus:ring-foreground" />

            <div class="flex items-center gap-2 sm:ml-auto">
                <button @click="showFilters = !showFilters"
                    class="inline-flex items-center gap-1.5 rounded-md border border-line px-3 py-2 text-sm font-medium text-dim hover:bg-subtle hover:text-foreground transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                    Filters
                    @if($activeFilterCount > 0)
                        <span class="inline-flex items-center justify-center h-4 min-w-[1rem] rounded-sm bg-foreground text-on-primary text-[10px] font-semibold px-1">{{ $activeFilterCount }}</span>
                    @endif
                </button>

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="inline-flex items-center gap-1.5 rounded-sm bg-foreground px-3 py-2 text-sm font-medium text-on-primary hover:opacity-90 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Export
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute right-0 mt-1 w-40 rounded-sm border border-line bg-card z-50" style="box-shadow: var(--shadow-lg);">
                        <button wire:click="export" @click="open = false"
                            class="flex w-full items-center gap-2 px-3 py-2 text-sm text-foreground hover:bg-subtle transition-colors rounded-t-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Export as CSV
                        </button>
                        <button wire:click="exportExcel" @click="open = false"
                            class="flex w-full items-center gap-2 px-3 py-2 text-sm text-foreground hover:bg-subtle transition-colors rounded-b-sm border-t border-line">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Export as Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters Panel --}}
            <div x-show="showFilters" x-cloak class="p-4 border-b border-line bg-subtle/50">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-dim mb-1">Status</label>
                        <select wire:model.live="filterStatus"
                            class="w-full rounded-md border border-line bg-card px-3 py-2 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                            <option value="">All Statuses</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-dim mb-1">City</label>
                        <select wire:model.live="filterCity"
                            class="w-full rounded-md border border-line bg-card px-3 py-2 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                            <option value="">All Cities</option>
                            @foreach($allCities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}, {{ $city->province?->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-dim mb-1">Property Type</label>
                        <select wire:model.live="filterType"
                            class="w-full rounded-md border border-line bg-card px-3 py-2 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                            <option value="">All Types</option>
                            @foreach($propertyTypes as $type)
                                <option value="{{ $type->id }}">Type #{{ $type->id }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if($activeFilterCount > 0)
                    <div class="mt-3">
                        <button wire:click="resetFilters" class="text-sm text-dim hover:text-foreground underline underline-offset-2">
                            Clear all filters
                        </button>
                    </div>
                @endif
            </div>

        {{-- Table --}}
        <div class="overflow-x-auto transition-opacity duration-200" wire:loading.class="opacity-50 pointer-events-none">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-line bg-subtle/50">
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">
                            <button wire:click="sortBy('id')" class="inline-flex items-center gap-1 hover:text-foreground">
                                ID
                                @if($sortBy === 'id')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sortDir === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" /></svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">
                            <button wire:click="sortBy('title')" class="inline-flex items-center gap-1 hover:text-foreground">
                                Title
                                @if($sortBy === 'title')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sortDir === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" /></svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">
                            <button wire:click="sortBy('price')" class="inline-flex items-center gap-1 hover:text-foreground">
                                Price
                                @if($sortBy === 'price')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sortDir === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" /></svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">City</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Status</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">
                            <button wire:click="sortBy('created_at')" class="inline-flex items-center gap-1 hover:text-foreground">
                                Created
                                @if($sortBy === 'created_at')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sortDir === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" /></svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse($properties as $property)
                        <tr class="hover:bg-subtle transition-colors">
                            <td class="px-4 py-3 text-dim">{{ $property->id }}</td>
                            <td class="px-4 py-3 font-medium text-foreground">{{ $property->title }}</td>
                            <td class="px-4 py-3 text-foreground">&#8369;{{ number_format($property->price, 2) }}</td>
                            <td class="px-4 py-3 text-foreground">{{ $property->address?->barangay?->city?->name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-sm px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider {{ $property->status ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                                    {{ $property->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-dim">{{ $property->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <button wire:click="view({{ $property->id }})" class="text-sm text-dim hover:text-foreground">View</button>
                                    <button wire:click="edit({{ $property->id }})" class="text-sm text-dim hover:text-foreground">Edit</button>
                                    <button wire:click="confirmDelete({{ $property->id }})" class="text-sm text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-dim">No properties found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-line flex items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-sm text-dim">
                <span>Rows per page:</span>
                <select wire:model.live="perPage" class="rounded-md border border-line bg-card text-foreground text-sm py-1 px-2 focus:outline-none focus:ring-1 focus:ring-black/20 dark:focus:ring-white/20">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
            <div class="flex-1">
                {{ $properties->links() }}
            </div>
        </div>
    </div>

    {{-- Multi-Step Create / Edit Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-foreground/40 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
            <div class="relative w-full max-w-4xl max-h-[90vh] overflow-hidden rounded-sm border border-line bg-card flex flex-col" style="box-shadow: var(--shadow-lg);">

                {{-- Header --}}
                <div class="flex items-center justify-between p-4 border-b border-line shrink-0">
                    <h3 class="text-base font-semibold text-foreground font-serif tracking-tight">{{ $editingId ? 'Edit Property' : 'Add Property' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-dim hover:text-foreground transition-colors">
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
                                {{-- Existing photos (edit mode) --}}
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

                                {{-- New uploaded photos --}}
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

                                {{-- Upload button --}}
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
                            {{ $editingId ? 'Update Property' : 'Create Property' }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- View Modal --}}
    @if($showViewModal && $viewProperty)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-foreground/40 backdrop-blur-sm" wire:click="$set('showViewModal', false)"></div>
            <div class="relative w-full max-w-lg max-h-[90vh] overflow-hidden rounded-sm border border-line bg-card flex flex-col" style="box-shadow: var(--shadow-lg);">
                {{-- Header --}}
                <div class="flex items-center justify-between p-4 border-b border-line shrink-0">
                    <div>
                        <h3 class="text-base font-semibold text-foreground font-serif tracking-tight">{{ $viewProperty->title }}</h3>
                        <p class="text-[10px] text-dim mt-0.5">Created {{ $viewProperty->created_at->format('M d, Y') }}</p>
                    </div>
                    <span class="inline-flex items-center rounded-sm px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider {{ $viewProperty->status ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                        {{ $viewProperty->status ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                {{-- Body --}}
                <div class="p-5 space-y-4 overflow-y-auto flex-1">
                    {{-- Property Images --}}
                    @if($viewProperty->images && $viewProperty->images->count() > 0)
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($viewProperty->images->take(3) as $img)
                                <div class="aspect-[4/3] rounded-sm overflow-hidden border border-line bg-subtle">
                                    <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover" alt="" />
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Price & Key Stats --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-subtle/50 border border-line rounded-sm p-3">
                            <span class="block text-[10px] text-dim uppercase tracking-wider mb-1">Price</span>
                            <p class="text-lg font-semibold text-foreground">&#8369;{{ number_format($viewProperty->price, 2) }}</p>
                        </div>
                        <div class="bg-subtle/50 border border-line rounded-sm p-3">
                            <span class="block text-[10px] text-dim uppercase tracking-wider mb-1">Area</span>
                            <p class="text-lg font-semibold text-foreground">{{ $viewProperty->area }} <span class="text-sm font-normal text-dim">sqm</span></p>
                        </div>
                    </div>

                    {{-- Details Grid --}}
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="block text-[10px] text-dim uppercase tracking-wider mb-0.5">Property Type</span>
                            <span class="font-medium text-foreground">{{ $viewProperty->propertyType?->name ?? ('Type #' . $viewProperty->property_type_id) }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-dim uppercase tracking-wider mb-0.5">Owner</span>
                            <span class="font-medium text-foreground">{{ $viewProperty->user?->name ?? '—' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-dim uppercase tracking-wider mb-0.5">Bedrooms</span>
                            <span class="text-foreground">{{ $viewProperty->bedrooms }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-dim uppercase tracking-wider mb-0.5">Bathrooms</span>
                            <span class="text-foreground">{{ $viewProperty->bathrooms }}</span>
                        </div>
                    </div>

                    {{-- Location Card --}}
                    <div class="bg-subtle/50 border border-line rounded-sm p-3">
                        <span class="block text-[10px] text-dim uppercase tracking-wider mb-2 font-semibold">Location</span>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="block text-[10px] text-dim uppercase tracking-wider mb-0.5">Region</span>
                                <span class="text-foreground">{{ $viewProperty->address?->barangay?->city?->province?->region?->name ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] text-dim uppercase tracking-wider mb-0.5">Province</span>
                                <span class="text-foreground">{{ $viewProperty->address?->barangay?->city?->province?->name ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] text-dim uppercase tracking-wider mb-0.5">City</span>
                                <span class="text-foreground">{{ $viewProperty->address?->barangay?->city?->name ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] text-dim uppercase tracking-wider mb-0.5">Barangay</span>
                                <span class="text-foreground">{{ $viewProperty->address?->barangay?->name ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] text-dim uppercase tracking-wider mb-0.5">Street</span>
                                <span class="text-foreground">{{ $viewProperty->address?->street ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] text-dim uppercase tracking-wider mb-0.5">Zip Code</span>
                                <span class="text-foreground">{{ $viewProperty->address?->zip_code ?? '—' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <span class="block text-[10px] text-dim uppercase tracking-wider mb-1">Description</span>
                        <p class="text-sm text-foreground bg-subtle/50 border border-line rounded-sm p-3">{{ $viewProperty->description }}</p>
                    </div>

                    {{-- Amenities --}}
                    @if($viewProperty->amenities)
                        <div>
                            <span class="block text-[10px] text-dim uppercase tracking-wider mb-1">Amenities</span>
                            <p class="text-sm text-foreground bg-subtle/50 border border-line rounded-sm p-3">{{ $viewProperty->amenities }}</p>
                        </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between p-4 border-t border-line shrink-0">
                    <div class="flex items-center gap-2">
                        <button wire:click="edit({{ $viewProperty->id }})"
                                class="rounded-sm bg-foreground px-3 py-1.5 text-xs font-medium text-on-primary hover:opacity-90 transition-all">Edit</button>
                        <button wire:click="confirmDelete({{ $viewProperty->id }})"
                                class="rounded-sm bg-red-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-700 transition-colors">Delete</button>
                    </div>
                    <button wire:click="$set('showViewModal', false)"
                            class="rounded-sm border border-line px-4 py-1.5 text-xs font-medium text-dim hover:text-foreground hover:bg-subtle transition-colors">Close</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-foreground/40 backdrop-blur-sm" wire:click="$set('showDeleteModal', false)"></div>
            <div class="relative w-full max-w-sm rounded-sm border border-line bg-card" style="box-shadow: var(--shadow-lg);">
                <div class="p-6 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-red-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-foreground mb-2">Delete Property</h3>
                    <p class="text-sm text-dim mb-6">Are you sure you want to delete this property? This action cannot be undone.</p>
                    <div class="flex justify-center gap-3">
                        <button wire:click="$set('showDeleteModal', false)"
                            class="rounded-md border border-line px-4 py-2 text-sm font-medium text-dim hover:bg-subtle hover:text-foreground transition-colors">
                            Cancel
                        </button>
                        <button wire:click="delete"
                            class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition-colors">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
