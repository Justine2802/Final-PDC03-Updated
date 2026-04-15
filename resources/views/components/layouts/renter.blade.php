<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Renter Dashboard' }} - {{ config('app.name') }}</title>
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
                <a href="{{ route('renter.home') }}" wire:navigate class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-md bg-primary">
                        <span class="text-sm font-bold text-on-primary">L</span>
                    </div>
                    <span class="text-sm font-semibold text-foreground">{{ config('app.name') }}</span>
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto px-4 py-6">
                <div class="space-y-1">
                    <a href="{{ route('renter.home') }}" wire:navigate
                        class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                            {{ request()->routeIs('renter.home') ? 'bg-primary text-on-primary' : 'text-dim hover:bg-subtle hover:text-foreground' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Home
                    </a>
                </div>

                <div class="mt-8">
                    <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-dim/60">Rentals</p>
                    <div class="space-y-1">
                        <a href="{{ route('renter.explore') }}" wire:navigate
                            class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                                {{ request()->routeIs('renter.explore*') ? 'bg-primary text-on-primary' : 'text-dim hover:bg-subtle hover:text-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.5 5.5a7.5 7.5 0 0010.5 10.5z" />
                            </svg>
                            Explore
                        </a>
                        <a href="{{ route('renter.favorites') }}" wire:navigate
                            class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                                {{ request()->routeIs('renter.favorites*') ? 'bg-primary text-on-primary' : 'text-dim hover:bg-subtle hover:text-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-1.085-.45-2.084-1.175-2.792a2.251 2.251 0 00-3.16 0l-.828.828-.828-.828a2.252 2.252 0 00-3.162 0c-.723.708-1.175 1.707-1.175 2.792V19.75a.75.75 0 001.5 0V8.25a.75.75 0 011.06 0l.828.828.828-.828a.75.75 0 011.06 0l.828.828.828-.828a.75.75 0 011.06 0V19.75a.75.75 0 001.5 0V8.25z" />
                            </svg>
                            Favorites
                        </a>
                        <a href="{{ route('renter.inquiries') }}" wire:navigate
                            class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                                {{ request()->routeIs('renter.inquiries*') ? 'bg-primary text-on-primary' : 'text-dim hover:bg-subtle hover:text-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            My Inquiries
                        </a>
                    </div>
                </div>

                <div class="mt-8">
                    <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-dim/60">Account</p>
                    <div class="space-y-1">
                        <a href="{{ route('renter.profile') }}" wire:navigate
                            class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                                {{ request()->routeIs('renter.profile*') ? 'bg-primary text-on-primary' : 'text-dim hover:bg-subtle hover:text-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            My Profile
                        </a>
                        <a href="{{ route('renter.settings.account') }}" wire:navigate
                            class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                                {{ request()->routeIs('renter.settings*') ? 'bg-primary text-on-primary' : 'text-dim hover:bg-subtle hover:text-foreground' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.592c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.041.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.005-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.145-.086.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Settings
                        </a>
                    </div>
                </div>
            </nav>

            {{-- User Info at Bottom --}}
            <div class="border-t border-line px-4 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-primary text-on-primary text-sm font-medium">
                        {{ strtoupper(substr(auth()->user()->name ?? 'R', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="truncate text-sm font-medium text-foreground">{{ auth()->user()->name ?? 'Renter' }}</p>
                        <p class="truncate text-xs text-dim">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                    <form method="POST" action="{{ route('renter.logout') }}">
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

        {{-- Main Content --}}
        <div class="lg:pl-64">
            {{-- Header --}}
            <header class="sticky top-0 z-40 flex h-16 items-center justify-between border-b border-line bg-page/95 px-6 backdrop-blur supports-[backdrop-filter]:bg-page/60">
                <button @click="sidebarOpen = !sidebarOpen" class="inline-flex items-center justify-center rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary lg:hidden">
                    <span class="sr-only">Open main menu</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <div class="flex-1"></div>

                <div class="flex items-center gap-4">
                    {{-- Theme Toggle --}}
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode); document.documentElement.classList.toggle('dark'); document.documentElement.setAttribute('data-theme', darkMode ? 'dark' : 'light')" class="inline-flex items-center justify-center rounded-md p-2 text-dim hover:bg-subtle hover:text-foreground transition-colors">
                        <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                        </svg>
                        <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12a9 9 0 11-18 0 9 9 0 0118 0m0 0v2.25m-6.364.386l1.591 1.591M12 21v2.25m6.364-.386l1.591-1.591M21 12a9 9 0 01-9 9m0 0h2.25m-6.364.386l-1.591-1.591" />
                        </svg>
                    </button>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
