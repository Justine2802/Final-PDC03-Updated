<div>
    <x-slot:header>
        <span class="font-medium text-foreground">Dashboard</span>
    </x-slot:header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
        <div class="rounded-lg border border-line bg-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-dim">Total Properties</p>
                    <p class="mt-1 text-2xl font-semibold text-foreground">{{ $totalProperties }}</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-subtle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-line bg-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-dim">Total Users</p>
                    <p class="mt-1 text-2xl font-semibold text-foreground">{{ $totalUsers }}</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-subtle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-line bg-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-dim">Reservations</p>
                    <p class="mt-1 text-2xl font-semibold text-foreground">{{ $totalReservations }}</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-subtle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-line bg-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-dim">Pending Inquiries</p>
                    <p class="mt-1 text-2xl font-semibold text-foreground">{{ $pendingInquiries }}</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-subtle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions & System Info --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="rounded-lg border border-line bg-card p-6">
            <h2 class="text-base font-semibold text-foreground mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('admin.properties') }}" wire:navigate
                    class="flex items-center justify-center rounded-md border border-line px-4 py-2.5 text-sm font-medium text-foreground hover:bg-subtle transition-colors">
                    Manage Properties
                </a>
                <a href="{{ route('admin.users') }}" wire:navigate
                    class="flex items-center justify-center rounded-md border border-line px-4 py-2.5 text-sm font-medium text-foreground hover:bg-subtle transition-colors">
                    Manage Users
                </a>
                <a href="{{ route('admin.reservations') }}" wire:navigate
                    class="flex items-center justify-center rounded-md border border-line px-4 py-2.5 text-sm font-medium text-foreground hover:bg-subtle transition-colors">
                    View Reservations
                </a>
                <a href="{{ route('admin.inquiries') }}" wire:navigate
                    class="flex items-center justify-center rounded-md border border-line px-4 py-2.5 text-sm font-medium text-foreground hover:bg-subtle transition-colors">
                    View Inquiries
                </a>
            </div>
        </div>

        <div class="rounded-lg border border-line bg-card p-6">
            <h2 class="text-base font-semibold text-foreground mb-4">System Info</h2>
            <dl class="space-y-3">
                <div class="flex items-center justify-between">
                    <dt class="text-sm text-dim">Laravel Version</dt>
                    <dd class="text-sm font-medium text-foreground">{{ app()->version() }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-sm text-dim">PHP Version</dt>
                    <dd class="text-sm font-medium text-foreground">{{ PHP_VERSION }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-sm text-dim">Environment</dt>
                    <dd>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ app()->environment('production') ? 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' : 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' }}">
                            {{ app()->environment() }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
