<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Renter Dashboard' }} — {{ config('app.name') }}</title>

    {{-- Google Fonts: Playfair Display (serif) + Inter (sans) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap" rel="stylesheet">

    <script>
        (function() {
            var d = localStorage.getItem('darkMode') === 'true';
            if (d) document.documentElement.classList.add('dark');
            document.documentElement.setAttribute('data-theme', d ? 'dark' : 'light');
            document.addEventListener('livewire:navigated', function() {
                var d2 = localStorage.getItem('darkMode') === 'true';
                document.documentElement.classList.toggle('dark', d2);
                document.documentElement.setAttribute('data-theme', d2 ? 'dark' : 'light');
            });
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-page antialiased">
    <div x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('darkMode') === 'true' }">

        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen"
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 bg-foreground/30 backdrop-blur-sm lg:hidden"
             @click="sidebarOpen = false">
        </div>

        {{-- ── Sidebar ──────────────────────────────────────────────── --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 flex w-60 flex-col bg-card border-r border-line transition-transform duration-300 ease-in-out lg:translate-x-0"
               style="box-shadow: var(--shadow-sm);">

            {{-- Brand --}}
            <div class="flex h-14 items-center gap-3 px-5 border-b border-line">
                <a href="{{ route('renter.home') }}" wire:navigate class="flex items-center gap-2.5 group">
                    <div class="w-6 h-6 flex items-center justify-center rounded-sm bg-foreground">
                        <span class="text-xs font-bold text-on-primary font-serif tracking-tight">{{ strtoupper(substr(config('app.name'), 0, 1)) }}</span>
                    </div>
                    <span class="text-base font-semibold text-foreground tracking-tight font-serif">{{ config('app.name') }}</span>
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto px-3 py-5 space-y-6">

                {{-- Overview --}}
                <div>
                    <a href="{{ route('renter.home') }}" wire:navigate
                       class="flex items-center gap-2.5 px-3 py-2 text-sm transition-colors rounded-sm
                           {{ request()->routeIs('renter.home') ? 'text-foreground font-medium bg-subtle' : 'text-dim hover:text-foreground hover:bg-subtle/60' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/>
                        </svg>
                        <span>Home</span>
                    </a>
                </div>

                {{-- Rentals --}}
                <div>
                    <p class="px-3 mb-1.5 text-[10px] font-semibold uppercase tracking-[0.12em] text-dim/50 font-sans">Rentals</p>
                    <div class="space-y-0.5">
                        @php
                        $navLinks = [
                            ['route' => 'renter.explore',      'label' => 'Explore',         'icon' => 'M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.5 5.5a7.5 7.5 0 0010.5 10.5z'],
                            ['route' => 'renter.favorites',    'label' => 'Favourites',       'icon' => 'M21 8.25c0-1.085-.45-2.084-1.175-2.792a2.251 2.251 0 00-3.16 0l-.828.828-.828-.828a2.252 2.252 0 00-3.162 0c-.723.708-1.175 1.707-1.175 2.792V19.75a.75.75 0 001.5 0V8.25a.75.75 0 011.06 0l.828.828.828-.828a.75.75 0 011.06 0l.828.828.828-.828a.75.75 0 011.06 0V19.75a.75.75 0 001.5 0V8.25z'],
                            ['route' => 'renter.reservations', 'label' => 'Reservations',    'icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5'],
                            ['route' => 'renter.reviews',      'label' => 'My Reviews',      'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                            ['route' => 'renter.inquiries',    'label' => 'Inquiries',       'icon' => 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75'],
                        ];
                        @endphp
                        @foreach($navLinks as $link)
                            <a href="{{ route($link['route']) }}" wire:navigate
                               class="flex items-center gap-2.5 px-3 py-2 text-sm transition-colors rounded-sm
                                   {{ request()->routeIs($link['route'] . '*') ? 'text-foreground font-medium bg-subtle' : 'text-dim hover:text-foreground hover:bg-subtle/60' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}"/>
                                </svg>
                                <span>{{ $link['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Account --}}
                <div>
                    <p class="px-3 mb-1.5 text-[10px] font-semibold uppercase tracking-[0.12em] text-dim/50 font-sans">Account</p>
                    <div class="space-y-0.5">
                        <a href="{{ route('renter.profile') }}" wire:navigate
                           class="flex items-center gap-2.5 px-3 py-2 text-sm transition-colors rounded-sm
                               {{ request()->routeIs('renter.profile*') ? 'text-foreground font-medium bg-subtle' : 'text-dim hover:text-foreground hover:bg-subtle/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                            <span>Profile</span>
                        </a>
                        <a href="{{ route('renter.settings.account') }}" wire:navigate
                           class="flex items-center gap-2.5 px-3 py-2 text-sm transition-colors rounded-sm
                               {{ request()->routeIs('renter.settings*') ? 'text-foreground font-medium bg-subtle' : 'text-dim hover:text-foreground hover:bg-subtle/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.592c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.041.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.005-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.145-.086.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Settings</span>
                        </a>
                    </div>
                </div>
            </nav>

            {{-- User footer --}}
            <div class="border-t border-line px-4 py-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-subtle border border-line flex items-center justify-center flex-shrink-0">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full rounded-full object-cover" alt="">
                        @else
                            <span class="text-xs font-semibold text-foreground font-serif">{{ strtoupper(substr(auth()->user()->name ?? 'R', 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-foreground truncate">{{ auth()->user()->name ?? 'Renter' }}</p>
                        <p class="text-[10px] text-dim truncate">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                    <form method="POST" action="{{ route('renter.logout') }}">
                        @csrf
                        <button type="submit" title="Log out"
                                class="w-7 h-7 flex items-center justify-center text-dim hover:text-foreground transition-colors rounded-sm hover:bg-subtle">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ── Main Content ────────────────────────────────────────── --}}
        <div class="lg:pl-60 min-h-screen flex flex-col">

            {{-- Top bar --}}
            <header class="sticky top-0 z-40 flex h-12 items-center justify-between border-b border-line bg-page/90 backdrop-blur-md px-5 lg:px-7">
                {{-- Mobile hamburger --}}
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-dim hover:text-foreground transition-colors p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>

                <div class="flex-1"></div>

                {{-- Dark mode toggle --}}
                <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode); document.documentElement.classList.toggle('dark', darkMode); document.documentElement.setAttribute('data-theme', darkMode ? 'dark' : 'light')"
                        class="w-8 h-8 flex items-center justify-center text-dim hover:text-foreground hover:bg-subtle transition-colors rounded-sm">
                    <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/>
                    </svg>
                    <svg x-show="darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                    </svg>
                </button>
            </header>

            {{-- Page content --}}
            <main class="flex-1 p-5 lg:p-8">
                {{-- Livewire notify toast --}}
                <div x-data="{ show: false, message: '' }"
                     x-on:notify.window="message = $event.detail.message; show = true; setTimeout(() => show = false, 3500)"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-1"
                     class="fixed bottom-5 right-5 z-[99] flex items-center gap-3 bg-foreground text-on-primary text-xs font-medium px-4 py-3 rounded-sm shadow-editorial-lg max-w-xs"
                     style="display:none;">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-70" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                    </svg>
                    <span x-text="message"></span>
                </div>

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
