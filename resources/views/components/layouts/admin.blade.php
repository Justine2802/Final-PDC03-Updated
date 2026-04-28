<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin' }} — {{ config('app.name') }}</title>

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
    <div x-data="{ mobileMenuOpen: false, darkMode: localStorage.getItem('darkMode') === 'true' }">

        {{-- ── Top Navbar ──────────────────────────────────────────── --}}
        <header class="sticky top-0 z-50 border-b border-line bg-card/95 backdrop-blur-md" style="box-shadow: var(--shadow-sm);">
            <div class="max-w-[1400px] mx-auto flex items-center h-14 px-5 lg:px-8">

                {{-- Brand (left) --}}
                <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center gap-2.5 shrink-0 group">
                    <div class="w-7 h-7 flex items-center justify-center rounded-sm bg-foreground group-hover:opacity-90 transition-opacity">
                        <span class="text-xs font-bold text-on-primary font-serif tracking-tight">{{ strtoupper(substr(config('app.name'), 0, 1)) }}</span>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold text-foreground tracking-tight font-serif leading-none">{{ config('app.name') }}</p>
                        <p class="text-[8px] uppercase tracking-[0.15em] text-dim mt-0.5">Admin Console</p>
                    </div>
                </a>

                {{-- Spacer pushes everything to the right --}}
                <div class="flex-1"></div>

                {{-- Desktop Navigation (right-aligned) --}}
                <nav class="hidden lg:flex items-center gap-0.5 mr-3">
                    @php
                    $mgmtLinks = [
                        ['route' => 'admin.dashboard',    'label' => 'Dashboard'],
                        ['route' => 'admin.properties',   'label' => 'Properties'],
                        ['route' => 'admin.users',        'label' => 'Users'],
                        ['route' => 'admin.reservations', 'label' => 'Reservations'],
                        ['route' => 'admin.inquiries',    'label' => 'Inquiries'],
                        ['route' => 'admin.reviews',      'label' => 'Reviews'],
                    ];
                    @endphp
                    @foreach($mgmtLinks as $link)
                        <a href="{{ route($link['route']) }}" wire:navigate
                           class="relative px-3 py-1.5 text-[13px] transition-all duration-200 rounded-sm
                               {{ request()->routeIs($link['route'] . '*') || request()->routeIs($link['route'])
                                   ? 'text-foreground font-semibold bg-subtle'
                                   : 'text-dim hover:text-foreground hover:bg-subtle/50' }}">
                            {{ $link['label'] }}
                            @if(request()->routeIs($link['route'] . '*') || request()->routeIs($link['route']))
                                <span class="absolute bottom-0 left-3 right-3 h-[2px] bg-foreground rounded-full"></span>
                            @endif
                        </a>
                    @endforeach

                    {{-- Config dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center gap-1 px-3 py-1.5 text-[13px] transition-all duration-200 rounded-sm
                                    {{ request()->routeIs('admin.property-types*') || request()->routeIs('admin.cities*') || request()->routeIs('admin.settings*')
                                        ? 'text-foreground font-semibold bg-subtle'
                                        : 'text-dim hover:text-foreground hover:bg-subtle/50' }}">
                            More
                            <svg class="w-3 h-3 opacity-50 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1"
                             class="absolute right-0 top-full mt-2 w-48 bg-card border border-line rounded-sm py-1.5 z-50"
                             style="display:none; box-shadow: var(--shadow-lg);">
                            <p class="px-3 py-1 text-[9px] font-semibold uppercase tracking-[0.15em] text-dim/60">Configuration</p>
                            <a href="{{ route('admin.property-types') }}" wire:navigate @click="open = false"
                               class="flex items-center gap-2.5 px-3 py-2 text-xs transition-colors {{ request()->routeIs('admin.property-types*') ? 'text-foreground font-medium bg-subtle' : 'text-dim hover:text-foreground hover:bg-subtle/50' }}">
                                <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                                Property Types
                            </a>
                            <a href="{{ route('admin.cities') }}" wire:navigate @click="open = false"
                               class="flex items-center gap-2.5 px-3 py-2 text-xs transition-colors {{ request()->routeIs('admin.cities*') ? 'text-foreground font-medium bg-subtle' : 'text-dim hover:text-foreground hover:bg-subtle/50' }}">
                                <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                Cities
                            </a>
                            <div class="my-1.5 border-t border-line"></div>
                            <a href="{{ route('admin.settings.profile') }}" wire:navigate @click="open = false"
                               class="flex items-center gap-2.5 px-3 py-2 text-xs transition-colors {{ request()->routeIs('admin.settings*') ? 'text-foreground font-medium bg-subtle' : 'text-dim hover:text-foreground hover:bg-subtle/50' }}">
                                <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.592c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.041.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.005-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.145-.086.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Settings
                            </a>
                        </div>
                    </div>
                </nav>

                {{-- Action buttons (right edge) --}}
                <div class="flex items-center gap-1">
                    {{-- Dark mode --}}
                    <button @click="darkMode = !darkMode; document.documentElement.classList.toggle('dark', darkMode); document.documentElement.setAttribute('data-theme', darkMode ? 'dark' : 'light'); localStorage.setItem('darkMode', darkMode)"
                            class="w-8 h-8 flex items-center justify-center text-dim hover:text-foreground hover:bg-subtle transition-all duration-200 rounded-sm">
                        <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                        <svg x-show="darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" x-cloak><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                    </button>

                    {{-- Divider --}}
                    <div class="hidden lg:block w-px h-5 bg-line mx-1"></div>

                    {{-- User dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center gap-2 text-xs hover:bg-subtle transition-all duration-200 px-2 py-1.5 rounded-sm">
                            <div class="w-7 h-7 rounded-sm bg-foreground flex items-center justify-center flex-shrink-0">
                                <span class="text-[10px] font-semibold text-on-primary font-serif">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                            </div>
                            <span class="hidden md:inline text-xs font-medium text-foreground">{{ auth()->user()->name ?? 'Admin' }}</span>
                            <svg class="w-3 h-3 text-dim transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </button>
                        <div x-show="open" @click.away="open = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1"
                             class="absolute right-0 top-full mt-2 w-48 bg-card border border-line rounded-sm py-1.5 z-50"
                             style="display:none; box-shadow: var(--shadow-lg);">
                            <div class="px-3 py-2 border-b border-line mb-1">
                                <p class="text-xs font-medium text-foreground">{{ auth()->user()->name ?? 'Admin' }}</p>
                                <p class="text-[10px] text-dim truncate">{{ auth()->user()->email ?? '' }}</p>
                            </div>
                            <a href="{{ route('admin.settings.profile') }}" wire:navigate class="block px-3 py-2 text-xs text-dim hover:text-foreground hover:bg-subtle/50 transition-colors">Settings</a>
                            <div class="my-1 border-t border-line"></div>
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-3 py-2 text-xs text-dim hover:text-foreground hover:bg-subtle/50 transition-colors">Log Out</button>
                            </form>
                        </div>
                    </div>

                    {{-- Mobile hamburger --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden w-8 h-8 flex items-center justify-center text-dim hover:text-foreground transition-all duration-200 rounded-sm hover:bg-subtle ml-1">
                        <svg x-show="!mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                        <svg x-show="mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" x-cloak><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Navigation --}}
            <div x-show="mobileMenuOpen" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="lg:hidden border-t border-line bg-card px-5 py-3 space-y-0.5">

                @foreach($mgmtLinks as $link)
                    <a href="{{ route($link['route']) }}" wire:navigate @click="mobileMenuOpen = false"
                       class="block px-3 py-2.5 text-sm rounded-sm transition-all duration-200 {{ request()->routeIs($link['route'] . '*') ? 'text-foreground font-semibold bg-subtle' : 'text-dim hover:text-foreground hover:bg-subtle/60' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
                <div class="my-2 border-t border-line"></div>
                <p class="px-3 py-1 text-[9px] font-semibold uppercase tracking-[0.15em] text-dim/50">Configuration</p>
                <a href="{{ route('admin.property-types') }}" wire:navigate @click="mobileMenuOpen = false"
                   class="block px-3 py-2.5 text-sm rounded-sm transition-all duration-200 {{ request()->routeIs('admin.property-types*') ? 'text-foreground font-semibold bg-subtle' : 'text-dim hover:text-foreground hover:bg-subtle/60' }}">Property Types</a>
                <a href="{{ route('admin.cities') }}" wire:navigate @click="mobileMenuOpen = false"
                   class="block px-3 py-2.5 text-sm rounded-sm transition-all duration-200 {{ request()->routeIs('admin.cities*') ? 'text-foreground font-semibold bg-subtle' : 'text-dim hover:text-foreground hover:bg-subtle/60' }}">Cities</a>
                <a href="{{ route('admin.settings.profile') }}" wire:navigate @click="mobileMenuOpen = false"
                   class="block px-3 py-2.5 text-sm rounded-sm transition-all duration-200 {{ request()->routeIs('admin.settings*') ? 'text-foreground font-semibold bg-subtle' : 'text-dim hover:text-foreground hover:bg-subtle/60' }}">Settings</a>
            </div>
        </header>

        {{-- ── Main Content ──────────────────────────────────────── --}}
        <main class="min-h-[calc(100vh-3.5rem)] p-5 lg:p-8 max-w-[1400px] w-full mx-auto">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="mb-5 flex items-center gap-2.5 border border-line bg-card px-4 py-3 text-xs text-foreground rounded-sm" style="box-shadow: var(--shadow-xs);">
                    <svg class="w-3.5 h-3.5 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-5 flex items-center gap-2.5 border border-red-200 bg-red-50 dark:border-red-500/20 dark:bg-red-500/10 px-4 py-3 text-xs text-red-700 dark:text-red-400 rounded-sm">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

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
                 class="fixed bottom-5 right-5 z-[99] flex items-center gap-2.5 bg-foreground text-on-primary text-xs font-medium px-4 py-3 rounded-sm max-w-xs"
                 style="display:none; box-shadow: var(--shadow-lg);">
                <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-70" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                <span x-text="message"></span>
            </div>

            {{ $slot }}
        </main>
    </div>
</body>
</html>
