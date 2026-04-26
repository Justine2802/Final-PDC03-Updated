<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login' }} — {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap" rel="stylesheet">

    <script>
        (function() {
            var d = localStorage.getItem('darkMode') === 'true';
            if (d) document.documentElement.classList.add('dark');
            document.documentElement.setAttribute('data-theme', d ? 'dark' : 'light');
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .auth-input {
            width: 100%;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            border-radius: 0.25rem;
            border: 1px solid var(--c-border);
            background: var(--c-page);
            color: var(--c-text);
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .auth-input::placeholder { color: var(--c-muted); opacity: 0.6; }
        .auth-input:focus {
            outline: none;
            border-color: var(--c-text);
            box-shadow: 0 0 0 1px var(--c-text);
        }
        .auth-input.input-error { border-color: #ef4444; }
        .auth-input.input-error:focus { box-shadow: 0 0 0 1px #ef4444; }
    </style>
</head>

<body class="min-h-screen bg-page antialiased" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }">

    {{-- Full-page split layout --}}
    <div class="min-h-screen flex">

        {{-- Left: decorative panel (hidden on mobile) --}}
        <div class="hidden lg:flex lg:w-5/12 xl:w-1/2 bg-foreground relative flex-col justify-between p-12 text-on-primary">
            <div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-sm bg-on-primary flex items-center justify-center">
                        <span class="text-sm font-bold text-foreground font-serif">{{ strtoupper(substr(config('app.name'), 0, 1)) }}</span>
                    </div>
                    <span class="text-lg font-semibold font-serif tracking-tight">{{ config('app.name') }}</span>
                </div>
            </div>

            <div class="max-w-md">
                <blockquote class="text-2xl xl:text-3xl font-serif leading-snug tracking-tight italic opacity-90">
                    "Find your perfect space — where comfort meets convenience."
                </blockquote>
                <p class="mt-6 text-sm opacity-50 tracking-wide uppercase">Rental Management Platform</p>
            </div>

            <div class="text-xs opacity-40">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </div>

        {{-- Right: form area --}}
        <div class="flex-1 flex flex-col">

            {{-- Top bar with dark mode toggle --}}
            <div class="flex items-center justify-between p-6">
                {{-- Mobile logo --}}
                <div class="flex items-center gap-2.5 lg:hidden">
                    <div class="w-7 h-7 rounded-sm bg-foreground flex items-center justify-center">
                        <span class="text-xs font-bold text-on-primary font-serif">{{ strtoupper(substr(config('app.name'), 0, 1)) }}</span>
                    </div>
                    <span class="text-sm font-semibold font-serif text-foreground tracking-tight">{{ config('app.name') }}</span>
                </div>
                <div class="hidden lg:block"></div>

                {{-- Dark mode toggle --}}
                <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode); document.documentElement.classList.toggle('dark', darkMode); document.documentElement.setAttribute('data-theme', darkMode ? 'dark' : 'light')"
                        class="w-8 h-8 flex items-center justify-center text-dim hover:text-foreground transition-colors rounded-sm">
                    <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/>
                    </svg>
                    <svg x-show="darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                    </svg>
                </button>
            </div>

            {{-- Centered form --}}
            <div class="flex-1 flex items-center justify-center px-6 pb-12">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>
