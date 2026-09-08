<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="UniPlay — The Ultimate Collegiate Esports Portal">

    <title>@yield('title', config('app.name', 'UniPlay'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Barlow+Condensed:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="bg-surface text-text-primary font-sans antialiased" x-data="siteShell()">

    {{-- Live Ticker Bar --}}
    <div class="bg-surface-elevated border-b border-border-hairline overflow-hidden relative">
        <div class="flex items-center">
            <div class="shrink-0 bg-primary/20 text-primary px-3 py-1.5 flex items-center gap-1.5">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                </span>
                <span class="font-display text-xs font-semibold uppercase tracking-wider">Live</span>
            </div>
            <div class="overflow-hidden flex-1">
                <div class="flex items-center gap-8 whitespace-nowrap animate-[marquee_40s_linear_infinite] py-1.5 px-4">
                    @php
                        $tickerMatches = $liveMatches ?? \App\Models\GameMatch::with([
                            'tournament:id,name',
                            'teamA:id,name,tag,logo',
                            'teamB:id,name,tag,logo',
                        ])
                            ->where('status', 'live')
                            ->orderBy('scheduled_at')
                            ->limit(10)
                            ->get();
                    @endphp
                    @forelse($tickerMatches as $tm)
                        <span class="inline-flex items-center gap-2 text-xs">
                            <span class="text-text-muted font-medium">{{ $tm->tournament->name }}</span>
                            <span class="text-primary font-mono font-bold">{{ $tm->teamA->tag }}</span>
                            <span class="font-mono text-sm font-bold text-text-primary">{{ $tm->score_a }} - {{ $tm->score_b }}</span>
                            <span class="text-primary font-mono font-bold">{{ $tm->teamB->tag }}</span>
                            <span class="text-primary text-[10px]">● LIVE</span>
                        </span>
                        <span class="text-border-hairline">│</span>
                    @empty
                        <span class="text-xs text-text-muted">No live matches right now.</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Header --}}
    <header class="sticky top-0 z-50 bg-surface-elevated/95 backdrop-blur-md border-b border-border-hairline">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo + Live Badge --}}
                <div class="flex items-center gap-3 lg:me-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <span class="font-display uppercase tracking-wider text-primary font-bold text-xl">UNIPLAY</span>
                    </a>
                    @if(isset($liveMatches) && $liveMatches->where('status','live')->count() > 0)
                        <a href="#live" class="hidden sm:inline-flex items-center gap-1 bg-primary/20 text-primary text-xs px-2 py-0.5 rounded font-semibold">
                            <span class="relative flex h-1.5 w-1.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-primary"></span>
                            </span>
                            {{ $liveMatches->where('status','live')->count() }} Live
                        </a>
                    @endif
                </div>

                {{-- Desktop Navigation --}}
                <nav class="hidden md:flex items-center gap-1 lg:gap-2 lg:mx-2 px-2 py-1 rounded-xl bg-surface-card/60">
                    @php
                        $navLinks = [
                            ['label' => 'Home', 'route' => 'home', 'active' => 'home'],
                            ['label' => 'Top Up', 'route' => 'topup.index', 'active' => 'topup.*'],
                            ['label' => 'Tickets', 'route' => 'tickets.index', 'active' => 'tickets.*'],
                            ['label' => 'Schedule', 'route' => 'schedule', 'active' => 'schedule'],
                            ['label' => 'Leaderboard', 'route' => 'standings', 'active' => 'standings'],
                            ['label' => 'Shorts', 'route' => 'shorts', 'active' => 'shorts'],
                            ['label' => 'Redeem', 'route' => 'redeem', 'active' => 'redeem'],
                        ];
                    @endphp
                    @foreach($navLinks as $link)
                            <a href="{{ route($link['route']) }}"
                               class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors
                                      {{ request()->routeIs($link['active']) ? 'text-primary bg-primary/10' : 'text-text-secondary hover:text-text-primary hover:bg-surface-card' }}">
                                {{ $link['label'] }}
                            </a>
                    @endforeach
                </nav>

                {{-- Right Section --}}
                @php
                    $searchPlaceholders = [
                        'topup.*'   => 'Search top-up products or games...',
                        'tickets.*' => 'Search matches, venues or tickets...',
                        'schedule'  => 'Search upcoming matches...',
                        'standings' => 'Search teams or standings...',
                        'shorts'    => 'Search shorts or clips...',
                        'search'    => 'Search again...',
                    ];
                    $searchPlaceholder = 'Search tournaments, games...';
                    $routeName = request()->route()?->getName() ?? '';
                    foreach ($searchPlaceholders as $pattern => $placeholder) {
                        if (str($routeName)->is($pattern)) {
                            $searchPlaceholder = $placeholder;
                            break;
                        }
                    }
                @endphp
                <div class="flex items-center gap-2 sm:gap-3 lg:gap-4 lg:ms-6 lg:me-4">

                    {{-- Search Bar --}}
                    <form action="{{ route('search') }}" method="GET" class="hidden lg:flex items-center" x-data="{ focused: false }">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="q" value="{{ request('q') }}"
                                   placeholder="{{ $searchPlaceholder }}"
                                   class="w-64 pl-10 pr-4 py-2 bg-surface-card border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-muted focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                                   :class="{ 'w-80': focused }"
                                   @focus="focused = true" @blur="focused = false">
                        </div>
                    </form>

                    {{-- Mobile Search Toggle --}}
                    <button @click="searchOpen = !searchOpen" class="lg:hidden p-2 text-text-muted hover:text-text-primary transition-colors cursor-pointer">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>

                    {{-- Notification Bell --}}
                    @auth
                    <div class="relative" x-data="notifications()" @click.outside="open = false">
                        <button @click="open = !open; if (open) load()" class="relative p-2 text-text-muted hover:text-text-primary transition-colors cursor-pointer">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <template x-if="unread > 0">
                                <span class="absolute top-0 right-0 inline-flex items-center justify-center min-w-[1.125rem] h-[1.125rem] px-1 rounded-full bg-primary text-white text-[9px] font-bold" x-text="unread"></span>
                            </template>
                        </button>

                        <div x-show="open" x-transition x-cloak
                             class="absolute right-0 mt-2 w-80 max-w-[calc(100vw-2rem)] bg-surface-elevated border border-border-hairline rounded-xl shadow-2xl z-50 overflow-hidden">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-border-hairline">
                                <span class="text-sm font-semibold text-text-primary">Notifications</span>
                                <button @click="markAllRead()" class="text-xs text-primary hover:text-primary/80 transition-colors cursor-pointer">Mark all read</button>
                            </div>
                            <div class="max-h-80 overflow-y-auto divide-y divide-border-hairline">
                                <template x-if="items.length === 0">
                                    <p class="px-4 py-10 text-center text-text-muted text-sm">No notifications</p>
                                </template>
                                <template x-for="n in items" :key="n.id">
                                    <a :href="n.link ?? '#'" @click.prevent="openItem(n)"
                                       class="flex items-start gap-3 px-4 py-3 hover:bg-surface-card transition-colors"
                                       :class="n.read ? '' : 'bg-primary/5'">
                                        <span class="mt-1.5 h-2 w-2 rounded-full shrink-0" :class="n.read ? 'bg-border-hairline' : 'bg-primary'"></span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-text-primary" x-text="n.title"></p>
                                            <p class="text-xs text-text-muted mt-0.5" x-text="n.message"></p>
                                            <p class="text-[10px] text-text-dim mt-1" x-text="n.created_at"></p>
                                        </div>
                                        <button @click.prevent.stop="remove(n)" class="text-text-dim hover:text-primary transition-colors p-1 cursor-pointer" title="Delete">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </a>
                                </template>
                            </div>
                            <div class="px-4 py-2.5 border-t border-border-hairline">
                                <button @click="clearAll()" class="w-full text-center text-xs text-text-muted hover:text-primary transition-colors cursor-pointer">
                                    Clear all (auto-deleted after 1 day)
                                </button>
                            </div>
                        </div>
                    </div>
                    @endauth

                    {{-- Auth Buttons: Sign In + (Sign Out when logged in) --}}
                    @auth
                        <a href="{{ route('home') }}"
                           class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-surface-card border border-border-hairline rounded-lg text-sm font-medium text-text-primary hover:border-primary/50 transition-colors cursor-pointer">
                            <span class="relative flex h-7 w-7 items-center justify-center rounded-full bg-primary/20 text-primary text-xs font-bold uppercase">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </span>
                            Sign In
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors cursor-pointer whitespace-nowrap">
                                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Sign Out
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="hidden sm:inline-flex items-center gap-2 px-5 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors cursor-pointer">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Sign In
                        </a>
                    @endauth

                    {{-- Mobile Hamburger --}}
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-text-muted hover:text-text-primary transition-colors">
                        <svg x-show="!mobileOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileOpen" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Search Bar --}}
        <div x-show="searchOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
             x-cloak class="lg:hidden border-t border-border-hairline px-4 py-3 bg-surface-elevated">
            <form action="{{ route('search') }}" method="GET" class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ $searchPlaceholder }}"
                       autofocus
                       class="w-full pl-10 pr-4 py-2.5 bg-surface-card border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-muted focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30">
            </form>
        </div>

        {{-- Mobile Navigation Menu --}}
        <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4"
             x-cloak class="md:hidden border-t border-border-hairline bg-surface-elevated">
            <div class="px-4 py-3 space-y-1">
                @foreach($navLinks as $link)
                        <a href="{{ route($link['route']) }}"
                           class="block px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                                  {{ request()->routeIs($link['active']) ? 'text-primary bg-primary/10' : 'text-text-secondary hover:text-text-primary hover:bg-surface-card' }}">
                            {{ $link['label'] }}
                        </a>
                @endforeach
                <div class="pt-2 mt-2 border-t border-border-hairline space-y-1">
                    @auth
                        <a href="{{ route('home') }}" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-primary bg-primary/10">
                            Sign In
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-primary text-center cursor-pointer">
                                Sign Out
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-2.5 rounded-lg text-sm font-semibold text-white bg-primary text-center">
                            Sign In
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @php
        $legalDocs = \App\Models\SiteContent::where('type', 'legal')->get()->keyBy('slug');
        $rulebooks = \App\Models\SiteContent::where('type', 'rule')->orderBy('order_index')->get();
        $whatsappNumber = \App\Models\SiteSetting::get('whatsapp_number', '6281318847041');
    @endphp
    <footer class="bg-surface-elevated border-t border-border-hairline mt-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

                {{-- Col 1: Brand --}}
                <div>
                    <a href="{{ route('home') }}" class="inline-block">
                        <span class="font-display uppercase tracking-wider text-primary font-bold text-2xl">UNIPLAY</span>
                    </a>
                    <p class="mt-3 text-text-muted text-sm leading-relaxed">The Ultimate Collegiate Esports Portal</p>
                    <div class="mt-4 inline-flex items-center gap-2 bg-secondary/10 text-secondary text-xs font-semibold px-3 py-1.5 rounded-full">
                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
                        </svg>
                        Official University Esports Partner
                    </div>
                </div>

                {{-- Col 2: Platform --}}
                <div>
                    <h4 class="font-display text-sm font-semibold uppercase tracking-wider text-text-primary mb-4">Platform</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('home') }}" class="text-sm text-text-muted hover:text-primary transition-colors">Arena Home</a></li>
                        <li><a href="{{ route('schedule') }}" class="text-sm text-text-muted hover:text-primary transition-colors">Upcoming Matches</a></li>
                        <li><a href="{{ route('standings') }}" class="text-sm text-text-muted hover:text-primary transition-colors">Leaderboards</a></li>
                        <li><a href="{{ route('shorts') }}" class="text-sm text-text-muted hover:text-primary transition-colors">Shorts & Clips</a></li>
                    </ul>
                </div>

                {{-- Col 3: Services --}}
                <div>
                    <h4 class="font-display text-sm font-semibold uppercase tracking-wider text-text-primary mb-4">Services</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('topup.index') }}" class="text-sm text-text-muted hover:text-primary transition-colors">Instant Top-Up Hub</a></li>
                        <li><a href="{{ route('tickets.index') }}" class="text-sm text-text-muted hover:text-primary transition-colors">Arena Tickets</a></li>
                        <li><a href="#" @click.prevent="prizeClaimOpen = true" class="text-sm text-text-muted hover:text-primary transition-colors">Prize Claim</a></li>
                    </ul>
                </div>

                {{-- Col 4: Support --}}
                <div>
                    <h4 class="font-display text-sm font-semibold uppercase tracking-wider text-text-primary mb-4">Support</h4>
                    <ul class="space-y-2.5">
                        <li>
                            <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" class="text-sm text-text-muted hover:text-primary transition-colors inline-flex items-center gap-1.5">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                WhatsApp
                            </a>
                        </li>
                        <li><a href="#" @click.prevent="openRulebooks" class="text-sm text-text-muted hover:text-primary transition-colors">Rulebooks</a></li>
                        <li><a href="#" @click.prevent="openContent('anti-cheat-policy', 'Anti-Cheat Policy')" class="text-sm text-text-muted hover:text-primary transition-colors">Anti-Cheat Policy</a></li>
                    </ul>
                    <div class="flex items-center gap-3 mt-4">
                        <a href="#" class="text-text-muted hover:text-primary transition-colors" aria-label="Discord">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M20.317 4.3698a19.7913 19.7913 0 00-4.8851-1.5152.0741.0741 0 00-.0785.0371c-.211.3753-.4447.8648-.6083 1.2495-1.8447-.2762-3.68-.2762-5.4868 0-.1636-.3933-.4058-.8742-.6177-1.2495a.077.077 0 00-.0785-.037 19.7363 19.7363 0 00-4.8852 1.515.0699.0699 0 00-.0321.0277C.5334 9.0458-.319 13.5799.0992 18.0578a.0824.0824 0 00.0312.0561c2.0528 1.5076 4.0413 2.4228 5.9929 3.0294a.0777.0777 0 00.0842-.0276c.4616-.6304.8731-1.2952 1.226-1.9942a.076.076 0 00-.0416-.1057c-.6528-.2476-1.2743-.5495-1.8722-.8923a.077.077 0 01-.0076-.1277c.1258-.0943.2517-.1923.3718-.2914a.0743.0743 0 01.0776-.0105c3.9278 1.7933 8.18 1.7933 12.0614 0a.0739.0739 0 01.0785.0095c.1202.099.246.1981.3728.2924a.077.077 0 01-.0066.1276 12.2986 12.2986 0 01-1.873.8914.0766.0766 0 00-.0407.1067c.3604.698.7719 1.3628 1.225 1.9932a.076.076 0 00.0842.0286c1.961-.6067 3.9495-1.5219 6.0023-3.0294a.077.077 0 00.0313-.0552c.5004-5.177-.8382-9.6739-3.5485-13.6604a.061.061 0 00-.0312-.0286zM8.02 15.3312c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9555-2.4189 2.157-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.9555 2.4189-2.1569 2.4189zm7.9748 0c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9554-2.4189 2.1569-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.946 2.4189-2.1568 2.4189z"/></svg>
                        </a>
                        <a href="#" class="text-text-muted hover:text-primary transition-colors" aria-label="Twitter / X">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" class="text-text-muted hover:text-primary transition-colors" aria-label="YouTube">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div class="mt-10 pt-6 border-t border-border-hairline flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-text-muted text-xs">&copy; {{ date('Y') }} UNIPLAY. All Rights Reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="#" @click.prevent="openContent('privacy-policy', 'Privacy Policy')" class="text-text-muted text-xs hover:text-text-secondary transition-colors">Privacy Policy</a>
                    <span class="text-border-hairline">|</span>
                    <a href="#" @click.prevent="openContent('terms-of-service', 'Terms of Service')" class="text-text-muted text-xs hover:text-text-secondary transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Content Modal (Rulebooks / Legal docs) --}}
    <div x-show="contentModal.open" x-cloak x-transition x-data
         class="fixed inset-0 z-[200] bg-black/70 backdrop-blur-sm flex items-center justify-center p-4"
         @click.self="contentModal.open = false" @keydown.escape.window="contentModal.open = false">
        <div class="bg-surface-card border border-border-hairline rounded-2xl w-full max-w-lg max-h-[80vh] overflow-hidden shadow-2xl">
            <div class="flex items-center justify-between px-5 py-4 border-b border-border-hairline bg-surface-elevated">
                <h3 class="font-display text-lg font-bold uppercase tracking-wider text-text-primary" x-text="contentModal.title"></h3>
                <button @click="contentModal.open = false" class="text-text-muted hover:text-primary transition-colors cursor-pointer" title="Close">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-5 overflow-y-auto max-h-[60vh] text-text-secondary text-sm leading-relaxed whitespace-pre-wrap font-mono" x-text="contentModal.body"></div>
        </div>
    </div>

    {{-- Prize Claim Modal --}}
    <div x-show="prizeClaimOpen" x-cloak x-transition
         class="fixed inset-0 z-[200] bg-black/70 backdrop-blur-sm flex items-center justify-center p-4"
         @click.self="prizeClaimOpen = false" @keydown.escape.window="prizeClaimOpen = false">
        <div class="bg-surface-card border border-border-hairline rounded-2xl w-full max-w-md overflow-hidden shadow-2xl">
            <div class="flex items-center justify-between px-5 py-4 border-b border-border-hairline bg-surface-elevated">
                <h3 class="font-display text-lg font-bold uppercase tracking-wider text-text-primary">Prize Claim</h3>
                <button @click="prizeClaimOpen = false" class="text-text-muted hover:text-primary transition-colors cursor-pointer" title="Close">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-5">
                <p class="text-text-dim text-sm mb-4">Enter the unique code you received after your Top-Up or Ticket purchase to claim your prize.</p>

                <template x-if="!claimResult">
                    <form @submit.prevent="submitClaim()" class="space-y-4">
                        <div>
                            <label class="block text-text-muted text-xs font-mono uppercase mb-2">Unique Code</label>
                            <input type="text"
                                   x-model="claimForm.code"
                                   maxlength="16"
                                   placeholder="e.g. AB3XK7PL"
                                   class="w-full px-4 py-3 rounded-xl bg-surface-elevated border border-border-hairline text-text-primary placeholder-text-dim font-mono text-sm uppercase tracking-wider focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-colors">
                        </div>
                        <button type="submit"
                                :disabled="claimForm.code.length < 4 || claiming"
                                class="w-full py-3 rounded-xl bg-primary text-white font-display font-bold text-sm uppercase tracking-wider hover:bg-primary/90 transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">
                            <span x-show="!claiming">Claim Prize</span>
                            <span x-show="claiming">Checking...</span>
                        </button>
                        <p class="text-text-dim text-[11px] font-mono text-center" x-text="prizeRules"></p>
                    </form>
                </template>

                <div x-show="claimResult" class="text-center py-4">
                    <div class="mb-3 inline-flex items-center justify-center w-14 h-14 rounded-full"
                         :class="claimResult.status === 'won' ? 'bg-secondary/20 text-secondary' : (claimResult.status === 'claimed' || claimResult.status === 'claimed_elsewhere' ? 'bg-primary/20 text-primary' : 'bg-surface-elevated text-text-muted')">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <template x-if="claimResult.status === 'won'">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </template>
                            <template x-if="claimResult.status !== 'won'">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </template>
                        </svg>
                    </div>
                    <h4 class="font-display text-lg font-bold text-text-primary uppercase" x-text="claimResult.heading"></h4>
                    <p class="text-text-muted text-sm mt-1" x-text="claimResult.message"></p>
                    <template x-if="claimResult.prize">
                        <p class="mt-3 inline-block px-4 py-2 rounded-lg bg-secondary/15 border border-secondary/30 text-secondary font-display font-bold uppercase" x-text="'You won: ' + claimResult.prize"></p>
                    </template>
                    <button @click="claimResult = null; claimForm.code = ''" class="mt-5 px-5 py-2.5 rounded-lg bg-surface-card border border-border-hairline text-text-muted hover:text-text-primary transition-colors text-sm font-medium cursor-pointer">
                        Claim Another
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function notifications() {
            return {
                open: false,
                items: [],
                unread: 0,
                init() {
                    this.load();
                    setInterval(() => this.load(), 30000);
                },
                async load() {
                    try {
                        const res = await fetch('{{ route('notifications.index') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                        const data = await res.json();
                        this.items = data.notifications;
                        this.unread = data.unread;
                    } catch (e) {}
                },
                async openItem(n) {
                    if (!n.read) {
                        try {
                            await fetch('/notifications/' + n.id + '/read', {
                                method: 'POST',
                                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                            });
                        } catch (e) {}
                    }
                    if (n.link) window.location.href = n.link;
                },
                async markAllRead() {
                    try {
                        await fetch('/notifications/read-all', {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                        });
                    } catch (e) {}
                    this.load();
                },
                async remove(n) {
                    try {
                        await fetch('/notifications/' + n.id, {
                            method: 'DELETE',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                        });
                    } catch (e) {}
                    this.load();
                },
                async clearAll() {
                    try {
                        await fetch('/notifications', {
                            method: 'DELETE',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                        });
                    } catch (e) {}
                    this.load();
                }
            };
        }

        function siteShell() {
            return {
                mobileOpen: false,
                searchOpen: false,
                prizeClaimOpen: false,
                claiming: false,
                prizeRules: @json(\App\Models\SiteSetting::get('prize_rules', '')),
                claimForm: { code: '' },
                claimResult: null,
                contentModal: { open: false, title: '', body: '' },
                contents: @json($legalDocs->mapWithKeys(fn ($doc) => [$doc->slug => ['title' => $doc->title, 'body' => $doc->body]])),
                rulebooks: @json($rulebooks->map(fn ($rule) => ['title' => $rule->title, 'body' => $rule->body])),
                openContent(slug, fallbackTitle) {
                    const item = this.contents[slug] || { title: fallbackTitle || 'Notice', body: 'This content has not been published yet by the admin.' };
                    this.contentModal.title = item.title;
                    const lines = item.body.split('\n').filter(l => l.trim());
                    this.contentModal.body = lines.map((line, i) => (i + 1) + '. ' + line.trim()).join('\n');
                    this.contentModal.open = true;
                },
                openRulebooks() {
                    const body = this.rulebooks.map((r, i) => (i + 1) + '. ' + r.title + "\n\n" + r.body.split('\n').filter(l => l.trim()).map((line, j) => '   ' + (j + 1) + '. ' + line.trim()).join('\n')).join("\n\n");
                    this.contentModal.title = 'Rulebooks';
                    this.contentModal.body = body || 'No rulebooks published yet by the admin.';
                    this.contentModal.open = true;
                },
                async submitClaim() {
                    const code = this.claimForm.code.trim().toUpperCase();
                    if (code.length < 4 || this.claiming) return;
                    this.claiming = true;
                    try {
                        const res = await fetch('{{ route('prize.claim') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ code })
                        });
                        const data = await res.json();
                        this.claimResult = data;
                    } catch (e) {
                        this.claimResult = { status: 'error', heading: 'Something went wrong', message: 'Please try again later.' };
                    } finally {
                        this.claiming = false;
                    }
                }
            };
        }
    </script>
    @endpush

    @stack('scripts')

    <style>
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-50%); }
        }
        button:not(:disabled) { cursor: pointer; }
    </style>
</body>
</html>