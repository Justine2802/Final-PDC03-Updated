<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard' }} - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <script>
        (function() {
            function applyDarkMode() {
                var d = localStorage.getItem('darkMode') === 'true';
                if (d) document.documentElement.classList.add('dark');
                else document.documentElement.classList.remove('dark');
                document.documentElement.setAttribute('data-theme', d ? 'dark' : 'light');
            }
            applyDarkMode();
            document.addEventListener('livewire:navigated', applyDarkMode);
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-page">
    {{-- Mobile sidebar backdrop --}}
    <div x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('darkMode') === 'true' }">
        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-primary/50 lg:hidden" @click="sidebarOpen = false">
        </div>

        {{-- Sidebar --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-page border-r border-line transition-transform duration-300 lg:translate-x-0">

            {{-- Logo --}}
            <div class="flex h-16 items-center gap-3 px-6 border-b border-line">
                <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-md bg-primary">
                        <span class="text-sm font-bold text-on-primary">L</span>
                    </div>
                    <span class="text-sm font-semibold text-foreground">{{ config('app.name') }}</span>
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto px-4 py-6">
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" wire:navigate
                        class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                            {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-on-primary' : 'text-dim hover:bg-subtle hover:text-foreground' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1" />
                        </svg>
                        Dashboard
                    </a>
                </div>

                <div class="mt-8">
                    <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-dim/60">Management</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.properties') }}" wire:navigate
                            class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                                {{ request()->routeIs('admin.properties*') ? 'bg-primary text-on-primary' : 'text-dim hover:bg-subtle hover:text-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Properties
                        </a>
                        <a href="{{ route('admin.users') }}" wire:navigate
                            class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                                {{ request()->routeIs('admin.users*') ? 'bg-primary text-on-primary' : 'text-dim hover:bg-subtle hover:text-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            Users
                        </a>
                        <a href="{{ route('admin.reservations') }}" wire:navigate
                            class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                                {{ request()->routeIs('admin.reservations*') ? 'bg-primary text-on-primary' : 'text-dim hover:bg-subtle hover:text-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            Reservations
                        </a>
                        <a href="{{ route('admin.inquiries') }}" wire:navigate
                            class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                                {{ request()->routeIs('admin.inquiries*') ? 'bg-primary text-on-primary' : 'text-dim hover:bg-subtle hover:text-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            Inquiries
                        </a>
                        <a href="{{ route('admin.reviews') }}" wire:navigate
                            class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                                {{ request()->routeIs('admin.reviews*') ? 'bg-primary text-on-primary' : 'text-dim hover:bg-subtle hover:text-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                            </svg>
                            Reviews
                        </a>
                    </div>
                </div>

                <div class="mt-8">
                    <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-dim/60">Settings</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.property-types') }}" wire:navigate
                            class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                                {{ request()->routeIs('admin.property-types*') ? 'bg-primary text-on-primary' : 'text-dim hover:bg-subtle hover:text-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                            </svg>
                            Property Types
                        </a>
                        <a href="{{ route('admin.cities') }}" wire:navigate
                            class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                                {{ request()->routeIs('admin.cities*') ? 'bg-primary text-on-primary' : 'text-dim hover:bg-subtle hover:text-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            Cities
                        </a>
                    </div>
                </div>
            </nav>

            {{-- User Info at Bottom --}}
            <div class="border-t border-line px-4 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-primary text-on-primary text-sm font-medium">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="truncate text-sm font-medium text-foreground">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="truncate text-xs text-dim">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-md text-dim hover:bg-subtle hover:text-foreground transition-colors" title="Logout">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main content area --}}
        <div class="lg:pl-64">
            {{-- Top header --}}
            <header class="sticky top-0 z-40 flex h-16 items-center gap-4 border-b border-line bg-page px-6">
                {{-- Mobile menu button --}}
                <button @click="sidebarOpen = true" class="lg:hidden flex h-9 w-9 items-center justify-center rounded-md text-dim hover:bg-subtle hover:text-foreground transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                {{-- Breadcrumb / Page title --}}
                <div class="flex-1">
                    <nav class="flex items-center gap-1.5 text-sm">
                        <a href="{{ route('admin.dashboard') }}" wire:navigate class="text-dim hover:text-foreground transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                        </a>
                        @if (isset($header))
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-dim/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                            {{ $header }}
                        @endif
                    </nav>
                </div>

                {{-- Dark mode toggle --}}
                <button @click="darkMode = !darkMode; document.documentElement.classList.toggle('dark', darkMode); document.documentElement.setAttribute('data-theme', darkMode ? 'dark' : 'light'); localStorage.setItem('darkMode', darkMode)"
                    class="flex h-9 w-9 items-center justify-center rounded-md text-dim hover:bg-subtle hover:text-foreground transition-colors">
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                    </svg>
                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                </button>

                {{-- User dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-dim hover:bg-subtle hover:text-foreground transition-colors">
                        <span class="hidden sm:inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 origin-top-right rounded-md border border-line bg-card py-1 shadow-lg">
                        <a href="{{ route('admin.dashboard') }}" wire:navigate class="block px-4 py-2 text-sm text-dim hover:bg-subtle hover:text-foreground">Dashboard</a>
                        <a href="{{ route('admin.settings.profile') }}" wire:navigate class="block px-4 py-2 text-sm text-dim hover:bg-subtle hover:text-foreground">Settings</a>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-dim hover:bg-subtle hover:text-foreground">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="p-6">
                {{-- Flash Messages --}}
                @if (session('success'))
                    <div role="alert" class="mb-4 flex items-center gap-3 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-500/20 dark:bg-green-500/10 dark:text-green-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div role="alert" class="mb-4 flex items-center gap-3 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
