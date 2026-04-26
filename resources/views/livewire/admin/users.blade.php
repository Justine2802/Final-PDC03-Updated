<div>
    <x-slot:header>
        <span class="font-medium text-foreground">Users</span>
    </x-slot:header>

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-foreground font-serif tracking-tight">Users</h1>
        <p class="text-xs text-dim mt-1">Manage all registered users.</p>
    </div>

    <div class="rounded-sm border border-line bg-card" x-data="{ showFilters: false, columns: { id: true, name: true, email: true, role: true, status: true, phone: false, joined: true } }" style="box-shadow: var(--shadow-xs);">
        {{-- Toolbar: Search + Filter toggle + Export --}}
        <div class="p-4 border-b border-line flex flex-col sm:flex-row sm:items-center gap-3">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search users..."
                class="w-full sm:max-w-xs rounded-md border border-line bg-card px-3 py-2 text-sm text-foreground placeholder-dim focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" />

            <div class="flex items-center gap-2 sm:ml-auto">
                <button @click="showFilters = !showFilters"
                    class="inline-flex items-center gap-1.5 rounded-md border border-line px-3 py-2 text-sm font-medium text-dim hover:bg-subtle hover:text-foreground transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                    Filters
                    @if($activeFilterCount > 0)
                        <span class="inline-flex items-center justify-center h-5 min-w-[1.25rem] rounded-full bg-primary text-on-primary text-xs font-medium px-1.5">{{ $activeFilterCount }}</span>
                    @endif
                </button>

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="inline-flex items-center gap-1.5 rounded-md border border-line px-3 py-2 text-sm font-medium text-dim hover:bg-subtle hover:text-foreground transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" /></svg>
                        Columns
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute right-0 mt-1 w-44 rounded-md border border-line bg-card shadow-lg z-50 py-1">
                        @foreach(['id' => 'ID', 'name' => 'Name', 'email' => 'Email', 'role' => 'Role', 'status' => 'Status', 'phone' => 'Phone', 'joined' => 'Joined'] as $key => $label)
                            <label class="flex items-center gap-2 px-3 py-1.5 text-sm text-foreground hover:bg-subtle cursor-pointer">
                                <input type="checkbox" x-model="columns.{{ $key }}"
                                    class="rounded border-line text-primary focus:ring-primary focus:ring-offset-0 h-3.5 w-3.5" />
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="inline-flex items-center gap-1.5 rounded-md bg-primary px-3 py-2 text-sm font-medium text-on-primary hover:bg-primary/90 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Export
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute right-0 mt-1 w-40 rounded-md border border-line bg-card shadow-lg z-50">
                        <button wire:click="export" @click="open = false"
                            class="flex w-full items-center gap-2 px-3 py-2 text-sm text-foreground hover:bg-subtle transition-colors rounded-t-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Export as CSV
                        </button>
                        <button wire:click="exportExcel" @click="open = false"
                            class="flex w-full items-center gap-2 px-3 py-2 text-sm text-foreground hover:bg-subtle transition-colors rounded-b-md border-t border-line">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Export as Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Advanced Filters Panel --}}
            <div x-show="showFilters" x-cloak class="p-4 border-b border-line bg-subtle/50">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-dim mb-1">Role</label>
                        <select wire:model.live="filterRole"
                            class="w-full rounded-md border border-line bg-card px-3 py-2 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                            <option value="">All Roles</option>
                            <option value="admin">Admin</option>
                            <option value="renter">Renter</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-dim mb-1">Status</label>
                        <select wire:model.live="filterStatus"
                            class="w-full rounded-md border border-line bg-card px-3 py-2 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                            <option value="">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-dim mb-1">Joined From</label>
                        <input type="date" wire:model.live="dateFrom"
                            class="w-full rounded-md border border-line bg-card px-3 py-2 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-dim mb-1">Joined To</label>
                        <input type="date" wire:model.live="dateTo"
                            class="w-full rounded-md border border-line bg-card px-3 py-2 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" />
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

        <div class="overflow-x-auto transition-opacity duration-200" wire:loading.class="opacity-50 pointer-events-none">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-line bg-subtle/50">
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]" x-show="columns.id">
                            <button wire:click="sortBy('id')" class="inline-flex items-center gap-1 hover:text-foreground">
                                ID
                                @if($sortBy === 'id')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sortDir === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" /></svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]" x-show="columns.name">
                            <button wire:click="sortBy('name')" class="inline-flex items-center gap-1 hover:text-foreground">
                                Name
                                @if($sortBy === 'name')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sortDir === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" /></svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]" x-show="columns.email">
                            <button wire:click="sortBy('email')" class="inline-flex items-center gap-1 hover:text-foreground">
                                Email
                                @if($sortBy === 'email')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sortDir === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" /></svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]" x-show="columns.role">Role</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]" x-show="columns.status">Status</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]" x-show="columns.phone">
                            <button wire:click="sortBy('phone')" class="inline-flex items-center gap-1 hover:text-foreground">
                                Phone
                                @if($sortBy === 'phone')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sortDir === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" /></svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]" x-show="columns.joined">
                            <button wire:click="sortBy('created_at')" class="inline-flex items-center gap-1 hover:text-foreground">
                                Joined
                                @if($sortBy === 'created_at')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sortDir === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" /></svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-dim uppercase tracking-[0.1em]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse($users as $user)
                        <tr class="hover:bg-subtle transition-colors">
                            <td class="px-4 py-3 text-dim" x-show="columns.id">{{ $user->id }}</td>
                            <td class="px-4 py-3 font-medium text-foreground" x-show="columns.name">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-foreground" x-show="columns.email">{{ $user->email }}</td>
                            <td class="px-4 py-3" x-show="columns.role">
                                <span class="inline-flex items-center rounded-full border border-line px-2 py-0.5 text-xs font-medium text-dim">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td class="px-4 py-3" x-show="columns.status">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $user->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : ($user->status === 'suspended' ? 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400') }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-dim" x-show="columns.phone">{{ $user->phone ?? '—' }}</td>
                            <td class="px-4 py-3 text-dim" x-show="columns.joined">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <button class="text-sm text-dim hover:text-foreground">View</button>
                                    <button class="text-sm text-dim hover:text-foreground">Edit</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-dim">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

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
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
