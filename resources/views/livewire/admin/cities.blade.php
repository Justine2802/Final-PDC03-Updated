<div>
    <x-slot:header>
        <span class="font-medium text-foreground">Cities / Municipalities</span>
    </x-slot:header>

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-foreground font-serif tracking-tight">Cities / Municipalities</h1>
            <p class="text-xs text-dim mt-1">Manage cities and their barangays.</p>
        </div>
        <button wire:click="create"
            class="inline-flex items-center gap-1.5 rounded-sm bg-foreground px-4 py-2 text-sm font-medium text-on-primary hover:opacity-90 transition-all" style="box-shadow: var(--shadow-xs);">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            Add City
        </button>
    </div>

    <div class="rounded-sm border border-line bg-card" style="box-shadow: var(--shadow-xs);">
        {{-- Toolbar --}}
        <div class="p-4 border-b border-line space-y-3">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search cities..."
                class="w-full sm:max-w-xs rounded-md border border-line bg-card px-3 py-2 text-sm text-foreground placeholder-dim focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" />

            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs font-medium text-dim mb-1">Region</label>
                    <select wire:model.live="filterRegion"
                        class="rounded-md border border-line bg-card px-3 py-2 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                        <option value="">All Regions</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-dim mb-1">Province</label>
                    <select wire:model.live="filterProvince" @disabled(!$filterRegion)
                        class="rounded-md border border-line bg-card px-3 py-2 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary disabled:opacity-50 disabled:cursor-not-allowed">
                        <option value="">{{ $filterRegion ? 'All Provinces' : 'Select Region first' }}</option>
                        @foreach($filterProvinces as $province)
                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if($filterRegion || $filterProvince || $search)
                    <button wire:click="resetFilters" class="text-sm text-dim hover:text-foreground underline underline-offset-2 pb-2">
                        Clear filters
                    </button>
                @endif
            </div>
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
                            <button wire:click="sortBy('name')" class="inline-flex items-center gap-1 hover:text-foreground">
                                City / Municipality
                                @if($sortBy === 'name')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sortDir === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" /></svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Province</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Region</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">
                            <button wire:click="sortBy('zip_code')" class="inline-flex items-center gap-1 hover:text-foreground">
                                Zip Code
                                @if($sortBy === 'zip_code')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sortDir === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" /></svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Barangays</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse($cities as $city)
                        <tr class="hover:bg-subtle transition-colors">
                            <td class="px-4 py-3 text-dim">{{ $city->id }}</td>
                            <td class="px-4 py-3 font-medium text-foreground">{{ $city->name }}</td>
                            <td class="px-4 py-3 text-foreground">{{ $city->province?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-foreground">{{ $city->province?->region?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-dim">{{ $city->zip_code ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <button wire:click="manageBarangays({{ $city->id }})" class="text-sm text-primary hover:underline">
                                    {{ $city->barangays_count }}
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <button wire:click="edit({{ $city->id }})" class="text-sm text-dim hover:text-foreground">Edit</button>
                                    <button wire:click="confirmDelete({{ $city->id }})" class="text-sm text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-dim">No cities found.</td>
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
                {{ $cities->links() }}
            </div>
        </div>
    </div>

    {{-- Create / Edit City Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-foreground/40 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
            <div class="relative w-full max-w-md rounded-sm border border-line bg-card" style="box-shadow: var(--shadow-lg);">
                <div class="flex items-center justify-between p-4 border-b border-line">
                    <h3 class="text-lg font-semibold text-foreground">{{ $editingId ? 'Edit City' : 'Add City' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-dim hover:text-foreground">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit="save" class="p-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Region <span class="text-red-500">*</span></label>
                        <select wire:model.live="regionId"
                            class="w-full rounded-md border border-line bg-card px-3 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                            <option value="">Select Region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </select>
                        @error('regionId') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Province <span class="text-red-500">*</span></label>
                        <select wire:model="provinceId" @disabled(!$regionId)
                            class="w-full rounded-md border border-line bg-card px-3 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary disabled:opacity-50 disabled:cursor-not-allowed">
                            <option value="">{{ $regionId ? 'Select Province' : 'Select Region first' }}</option>
                            @foreach($modalProvinces as $province)
                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                        @error('provinceId') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">City / Municipality Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" placeholder="e.g. Angeles City, Malolos..."
                            class="w-full rounded-md border border-line bg-card px-3 py-2.5 text-sm text-foreground placeholder-dim focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" />
                        @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Zip / Postal Code</label>
                        <input type="text" wire:model="zip_code" placeholder="e.g. 2009"
                            class="w-full sm:w-1/3 rounded-md border border-line bg-card px-3 py-2.5 text-sm text-foreground placeholder-dim focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" />
                        @error('zip_code') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-line">
                        <button type="button" wire:click="$set('showModal', false)"
                            class="rounded-md border border-line px-4 py-2 text-sm font-medium text-dim hover:bg-subtle hover:text-foreground transition-colors">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50"
                            class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-on-primary hover:bg-primary/90 transition-colors">
                            <span wire:loading.remove wire:target="save">{{ $editingId ? 'Update' : 'Create' }}</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Barangay Management Modal --}}
    @if($showBarangayModal && $barangayCity)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-foreground/40 backdrop-blur-sm" wire:click="$set('showBarangayModal', false)"></div>
            <div class="relative w-full max-w-lg rounded-sm border border-line bg-card" style="box-shadow: var(--shadow-lg);">
                <div class="flex items-center justify-between p-4 border-b border-line">
                    <h3 class="text-lg font-semibold text-foreground">Barangays — {{ $barangayCity->name }}</h3>
                    <button wire:click="$set('showBarangayModal', false)" class="text-dim hover:text-foreground">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="p-4">
                    {{-- Add barangay form --}}
                    <form wire:submit="addBarangay" class="flex gap-2 mb-4">
                        <input type="text" wire:model="newBarangay" placeholder="Enter barangay name..."
                            class="flex-1 rounded-md border border-line bg-card px-3 py-2 text-sm text-foreground placeholder-dim focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" />
                        <button type="submit" wire:loading.attr="disabled"
                            class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-on-primary hover:bg-primary/90 transition-colors whitespace-nowrap">
                            Add
                        </button>
                    </form>
                    @error('newBarangay') <span class="text-xs text-red-500 mb-3 block">{{ $message }}</span> @enderror

                    {{-- Barangay list --}}
                    <div class="max-h-72 overflow-y-auto rounded-md border border-line divide-y divide-line">
                        @forelse($barangayCity->barangays as $barangay)
                            <div class="flex items-center justify-between px-3 py-2 hover:bg-subtle transition-colors">
                                <span class="text-sm text-foreground">{{ $barangay->name }}</span>
                                <button wire:click="deleteBarangay({{ $barangay->id }})" wire:confirm="Delete barangay '{{ $barangay->name }}'?"
                                    class="text-xs text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                    Remove
                                </button>
                            </div>
                        @empty
                            <div class="px-3 py-6 text-center text-sm text-dim">No barangays yet.</div>
                        @endforelse
                    </div>
                    <p class="text-xs text-dim mt-2">{{ $barangayCity->barangays->count() }} barangay(s)</p>
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
                    <h3 class="text-lg font-semibold text-foreground mb-2">Delete City</h3>
                    <p class="text-sm text-dim mb-6">This will also delete all barangays under this city. Are you sure?</p>
                    <div class="flex justify-center gap-3">
                        <button wire:click="$set('showDeleteModal', false)"
                            class="rounded-md border border-line px-4 py-2 text-sm font-medium text-dim hover:bg-subtle hover:text-foreground transition-colors">
                            Cancel
                        </button>
                        <button wire:click="delete" wire:loading.attr="disabled" wire:loading.class="opacity-50"
                            class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition-colors">
                            <span wire:loading.remove wire:target="delete">Delete</span>
                            <span wire:loading wire:target="delete">Deleting...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
