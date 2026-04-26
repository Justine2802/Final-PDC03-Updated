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

        {{-- ── Sidebar ────────────────────────────────────────────── --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 flex w-60 flex-col bg-card border-r border-line transition-transform duration-300 ease-in-out lg:translate-x-0"
               style="box-shadow: var(--shadow-sm);">

            {{-- Brand --}}
            <div class="flex h-14 items-center gap-3 px-5 border-b border-line">
                <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center gap-2.5">
                    <div class="w-6 h-6 flex items-center justify-center rounded-sm bg-foreground">
                            <span class="text-xs font-bold text-on-primary font-serif tracking-tight">{{ strtoupper(substr(config('app.name'), 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="text-base font-semibold text-foreground tracking-tight font-serif leading-none">{{ config('app.name') }}</p>
                        <p class="text-[9px] uppercase tracking-[0.1em] text-dim mt-0.5">Admin Console</p>
                    </div>
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto px-3 py-5 space-y-6">

                <div>
                    <a href="{{ route('admin.dashboard') }}" wire:navigate
                       class="flex items-center gap-2.5 px-3 py-2 text-sm transition-colors rounded-sm
                           {{ request()->routeIs('admin.dashboard') ? 'text-foreground font-medium bg-subtle' : 'text-dim hover:text-foreground hover:bg-subtle/60' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </div>

                {{-- Management --}}
                <div>
                    <p class="px-3 mb-1.5 text-[10px] font-semibold uppercase tracking-[0.12em] text-dim/50">Management</p>
                    <div class="space-y-0.5">
                        @php
                        $mgmtLinks = [
                            ['route' => 'admin.properties',  'label' => 'Properties',    'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                            ['route' => 'admin.users',       'label' => 'Users',         'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z'],
                            ['route' => 'admin.reservations','label' => 'Reservations',  'icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5'],
                            ['route' => 'admin.inquiries',   'label' => 'Inquiries',     'icon' => 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75'],
                            ['route' => 'admin.reviews',     'label' => 'Reviews',       'icon' => 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z'],
                        ];
                        @endphp
                        @foreach($mgmtLinks as $link)
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

                {{-- Configuration --}}
                <div>
                    <p class="px-3 mb-1.5 text-[10px] font-semibold uppercase tracking-[0.12em] text-dim/50">Configuration</p>
                    <div class="space-y-0.5">
                        <a href="{{ route('admin.property-types') }}" wire:navigate
                           class="flex items-center gap-2.5 px-3 py-2 text-sm transition-colors rounded-sm
                               {{ request()->routeIs('admin.property-types*') ? 'text-foreground font-medium bg-subtle' : 'text-dim hover:text-foreground hover:bg-subtle/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                            </svg>
                            <span>Property Types</span>
                        </a>
                        <a href="{{ route('admin.cities') }}" wire:navigate
                           class="flex items-center gap-2.5 px-3 py-2 text-sm transition-colors rounded-sm
                               {{ request()->routeIs('admin.cities*') ? 'text-foreground font-medium bg-subtle' : 'text-dim hover:text-foreground hover:bg-subtle/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                            </svg>
                            <span>Cities</span>
                        </a>
                        <a href="{{ route('admin.settings.profile') }}" wire:navigate
                           class="flex items-center gap-2.5 px-3 py-2 text-sm transition-colors rounded-sm
                               {{ request()->routeIs('admin.settings*') ? 'text-foreground font-medium bg-subtle' : 'text-dim hover:text-foreground hover:bg-subtle/60' }}">
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
                    <div class="w-7 h-7 rounded-sm bg-foreground flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-semibold text-on-primary font-serif">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-foreground truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-[10px] text-dim truncate">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
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

        {{-- ── Main Content ──────────────────────────────────────── --}}
        <div class="lg:pl-60 min-h-screen flex flex-col">

            {{-- Top bar --}}
            <header class="sticky top-0 z-40 flex h-12 items-center gap-4 border-b border-line bg-page/90 backdrop-blur-md px-5 lg:px-7">
                <button @click="sidebarOpen = true" class="lg:hidden text-dim hover:text-foreground transition-colors p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>

                {{-- Breadcrumb --}}
                <div class="flex-1 flex items-center gap-1.5 text-xs text-dim">
                    <a href="{{ route('admin.dashboard') }}" wire:navigate class="hover:text-foreground transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                        </svg>
                    </a>
                    @if(isset($header))
                        <span class="text-dim/40">/</span>
                        <span class="text-foreground font-medium">{{ $header }}</span>
                    @endif
                </div>

                {{-- Dark mode --}}
                <button @click="darkMode = !darkMode; document.documentElement.classList.toggle('dark', darkMode); document.documentElement.setAttribute('data-theme', darkMode ? 'dark' : 'light'); localStorage.setItem('darkMode', darkMode)"
                        class="w-8 h-8 flex items-center justify-center text-dim hover:text-foreground hover:bg-subtle transition-colors rounded-sm">
                    <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/>
                    </svg>
                    <svg x-show="darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                    </svg>
                </button>

                {{-- User menu --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center gap-1.5 text-xs text-dim hover:text-foreground transition-colors px-2 py-1.5 rounded-sm hover:bg-subtle">
                        <span class="hidden sm:inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 top-full mt-1 w-44 bg-card border border-line rounded-sm shadow-editorial-md py-1 z-50"
                         style="display:none;">
                        <a href="{{ route('admin.settings.profile') }}" wire:navigate class="block px-3 py-2 text-xs text-dim hover:text-foreground hover:bg-subtle transition-colors">Settings</a>
                        <div class="my-1 border-t border-line"></div>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-3 py-2 text-xs text-dim hover:text-foreground hover:bg-subtle transition-colors">Log Out</button>
                        </form>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 p-5 lg:p-8 max-w-7xl w-full mx-auto">

                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="mb-5 flex items-center gap-2.5 border border-line bg-card px-4 py-3 text-xs text-foreground rounded-sm" style="box-shadow: var(--shadow-xs);">
                        <svg class="w-3.5 h-3.5 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-5 flex items-center gap-2.5 border border-red-200 bg-red-50 dark:border-red-500/20 dark:bg-red-500/10 px-4 py-3 text-xs text-red-700 dark:text-red-400 rounded-sm">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
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
