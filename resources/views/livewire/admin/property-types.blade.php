<div>
    <x-slot:header>
        <span class="font-medium text-foreground">Property Types</span>
    </x-slot:header>

    {{-- Action bar --}}
    <div class="flex items-center justify-between mb-4">
        <div></div>
        <button wire:click="create"
            class="inline-flex items-center gap-1.5 rounded-md bg-primary px-3 py-2 text-sm font-medium text-on-primary hover:bg-primary/90 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            Add Type
        </button>
    </div>

    <div class="rounded-lg border border-line bg-card">
        {{-- Toolbar --}}
        <div class="p-4 border-b border-line">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search property types..."
                class="w-full sm:max-w-xs rounded-md border border-line bg-card px-3 py-2 text-sm text-foreground placeholder-dim focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" />
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto transition-opacity duration-200" wire:loading.class="opacity-50 pointer-events-none">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-line bg-subtle">
                        <th class="px-4 py-3 text-left font-medium text-dim">
                            <button wire:click="sortBy('id')" class="inline-flex items-center gap-1 hover:text-foreground">
                                ID
                                @if($sortBy === 'id')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sortDir === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" /></svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left font-medium text-dim">
                            <button wire:click="sortBy('name')" class="inline-flex items-center gap-1 hover:text-foreground">
                                Name
                                @if($sortBy === 'name')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sortDir === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" /></svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left font-medium text-dim">Properties</th>
                        <th class="px-4 py-3 text-left font-medium text-dim">
                            <button wire:click="sortBy('created_at')" class="inline-flex items-center gap-1 hover:text-foreground">
                                Created
                                @if($sortBy === 'created_at')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sortDir === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" /></svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left font-medium text-dim">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse($propertyTypes as $type)
                        <tr class="hover:bg-subtle transition-colors">
                            <td class="px-4 py-3 text-dim">{{ $type->id }}</td>
                            <td class="px-4 py-3 font-medium text-foreground">{{ $type->name }}</td>
                            <td class="px-4 py-3 text-dim">{{ $type->properties_count }}</td>
                            <td class="px-4 py-3 text-dim">{{ $type->created_at?->format('M d, Y') ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <button wire:click="edit({{ $type->id }})" class="text-sm text-dim hover:text-foreground">Edit</button>
                                    <button wire:click="confirmDelete({{ $type->id }})" class="text-sm text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-dim">No property types found.</td>
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
                {{ $propertyTypes->links() }}
            </div>
        </div>
    </div>

    {{-- Create / Edit Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" wire:click="$set('showModal', false)"></div>
            <div class="relative w-full max-w-md rounded-lg border border-line bg-card shadow-xl">
                <div class="flex items-center justify-between p-4 border-b border-line">
                    <h3 class="text-lg font-semibold text-foreground">{{ $editingId ? 'Edit Property Type' : 'Add Property Type' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-dim hover:text-foreground">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit="save" class="p-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" placeholder="e.g. Apartment, House, Condo..."
                            class="w-full rounded-md border border-line bg-card px-3 py-2.5 text-sm text-foreground placeholder-dim focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" />
                        @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
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

    {{-- Delete Confirmation Modal --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" wire:click="$set('showDeleteModal', false)"></div>
            <div class="relative w-full max-w-sm rounded-lg border border-line bg-card shadow-xl">
                <div class="p-6 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-red-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-foreground mb-2">Delete Property Type</h3>
                    <p class="text-sm text-dim mb-6">Are you sure? Properties using this type may be affected.</p>
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
