<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'UniPlay') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Barlow+Condensed:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="bg-surface text-text-primary font-sans antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/" class="font-display text-3xl font-bold text-primary uppercase tracking-wider">
                    UNI<span class="text-secondary">PLAY</span>
                </a>
                <p class="text-text-muted text-xs font-mono text-center mt-1 tracking-widest uppercase">Esports Portal</p>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-surface-card border border-border-hairline sm:rounded-xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
