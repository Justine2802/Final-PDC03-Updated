<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login' }} - {{ config('app.name') }}</title>
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

<body class="min-h-screen bg-page flex items-center justify-center">
    {{ $slot }}
</body>

</html>
