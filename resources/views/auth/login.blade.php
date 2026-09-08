<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? config('app.name', 'UniPlay') }} — Login</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Barlow+Condensed:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="bg-surface text-text-primary font-sans antialiased">
        <section class="min-h-screen flex items-center justify-center px-4">
            <div class="w-full max-w-md">
                <div class="text-center mb-8">
                    <a href="/" class="font-display text-4xl font-bold text-primary uppercase tracking-wider">
                        UNI<span class="text-secondary">PLAY</span>
                    </a>
                    <p class="text-text-muted text-sm font-mono mt-2 uppercase tracking-widest">Sign in to your account</p>
                </div>

                <div class="bg-surface-card border border-border-hairline rounded-xl p-8">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="email" :value="'Email'" class="text-text-muted text-xs font-mono uppercase tracking-wider" />
                            <x-text-input id="email" name="email" type="email" :value="old('email')" required autofocus autocomplete="username"
                                class="mt-1 block w-full px-4 py-3 rounded-xl bg-surface-elevated border border-border-hairline text-text-primary placeholder-text-dim font-mono text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-colors" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="password" :value="'Password'" class="text-text-muted text-xs font-mono uppercase tracking-wider" />
                            <x-text-input id="password" name="password" type="password" required autocomplete="current-password"
                                class="mt-1 block w-full px-4 py-3 rounded-xl bg-surface-elevated border border-border-hairline text-text-primary placeholder-text-dim font-mono text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-colors" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between mb-6">
                            <label for="remember_me" class="flex items-center gap-2">
                                <input id="remember_me" type="checkbox" name="remember"
                                    class="rounded border-border-hairline bg-surface-elevated text-primary focus:ring-primary/30">
                                <span class="text-text-muted text-sm">Remember me</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-primary text-sm hover:text-primary/80 transition-colors">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <button type="submit"
                            class="w-full py-3 rounded-xl bg-primary text-white font-display font-bold text-sm uppercase tracking-wider hover:bg-primary/90 hover:shadow-lg hover:shadow-primary/20 transition-all duration-200 cursor-pointer">
                            Sign In
                        </button>
                    </form>
                </div>

                @if (Route::has('register'))
                <p class="text-center text-text-muted text-sm mt-6">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-primary hover:text-primary/80 font-semibold transition-colors">Register</a>
                </p>
                @endif
                <p class="text-center text-text-muted text-sm mt-2">
                    <a href="/home" class="text-text-dim hover:text-text-secondary transition-colors">Enter without an account</a>
                </p>
            </div>
        </section>
    </body>
</html>
