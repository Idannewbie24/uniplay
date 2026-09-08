@extends('layouts.app')

@section('title', 'UniPlay — Portal Dashboard')

@section('content')
<section class="mb-8">
    <div class="bg-surface-card border border-border-hairline rounded-xl p-6 lg:p-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="relative flex h-14 w-14 items-center justify-center rounded-xl bg-primary/20 text-primary text-xl font-display font-bold">
                {{ substr(Auth::user()->name, 0, 1) }}
                <span class="absolute bottom-0 right-0 h-3.5 w-3.5 bg-green-500 border-2 border-surface-card rounded-full"></span>
            </div>
            <div class="flex-1">
                <h1 class="text-xl font-bold text-text-primary">Welcome back, {{ Auth::user()->name }}</h1>
                <p class="text-text-muted text-sm mt-0.5">Manage your portal, tickets, and top-ups from here.</p>
            </div>
            <div class="flex items-center gap-2">
                    <a href="{{ route('topup.index') }}" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">Top Up</a>
                    <a href="{{ route('tickets.index') }}" class="px-4 py-2 bg-surface-elevated text-text-secondary border border-border-hairline rounded-lg text-sm font-semibold hover:text-text-primary hover:border-primary/30 transition-colors">My Tickets</a>
            </div>
        </div>
    </div>
</section>

<section class="mb-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $stats = [
                ['label' => 'Total Orders', 'value' => '—', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color' => 'text-tertiary'],
                ['label' => 'Active Tickets', 'value' => '—', 'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z', 'color' => 'text-secondary'],
                ['label' => 'Wallet Balance', 'value' => '$0.00', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'text-primary'],
                ['label' => 'Matches Watched', 'value' => '—', 'icon' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z', 'color' => 'text-primary'],
            ];
        @endphp
        @foreach($stats as $stat)
            <div class="bg-surface-card border border-border-hairline rounded-xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-surface-elevated rounded-lg">
                        <svg class="h-5 w-5 {{ $stat['color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}" />
                        </svg>
                    </div>
                    <span class="text-text-muted text-xs font-medium uppercase tracking-wider">{{ $stat['label'] }}</span>
                </div>
                <p class="font-mono text-2xl font-bold text-text-primary">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>
</section>

<section class="mb-8" x-data="{ activeTab: 'overview' }">
    <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden">
        <div class="flex items-center gap-1 px-5 pt-4 border-b border-border-hairline">
            <button @click="activeTab = 'overview'"
                    :class="activeTab === 'overview' ? 'text-primary border-primary' : 'text-text-muted border-transparent hover:text-text-secondary'"
                    class="px-4 py-3 text-sm font-semibold border-b-2 transition-colors -mb-px">Overview</button>
            <button @click="activeTab = 'orders'"
                    :class="activeTab === 'orders' ? 'text-primary border-primary' : 'text-text-muted border-transparent hover:text-text-secondary'"
                    class="px-4 py-3 text-sm font-semibold border-b-2 transition-colors -mb-px">Recent Orders</button>
            <button @click="activeTab = 'settings'"
                    :class="activeTab === 'settings' ? 'text-primary border-primary' : 'text-text-muted border-transparent hover:text-text-secondary'"
                    class="px-4 py-3 text-sm font-semibold border-b-2 transition-colors -mb-px">Settings</button>
        </div>

        {{-- Overview Tab --}}
        <div x-show="activeTab === 'overview'" class="p-6">
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary/10 mb-4">
                    <svg class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-text-primary mb-2">Your Portal is Ready</h3>
                <p class="text-text-muted text-sm max-w-md mx-auto">Start by exploring top-ups, booking arena tickets for upcoming matches, or checking out the latest tournament standings.</p>
                <div class="flex flex-wrap items-center justify-center gap-3 mt-6">
                        <a href="{{ route('schedule') }}" class="px-5 py-2.5 bg-surface-elevated text-text-secondary border border-border-hairline rounded-lg text-sm font-semibold hover:text-text-primary hover:border-primary/30 transition-colors">View Schedule</a>
                        <a href="{{ route('standings') }}" class="px-5 py-2.5 bg-surface-elevated text-text-secondary border border-border-hairline rounded-lg text-sm font-semibold hover:text-text-primary hover:border-primary/30 transition-colors">Leaderboards</a>
                </div>
            </div>
        </div>

        {{-- Orders Tab --}}
        <div x-show="activeTab === 'orders'" class="p-6">
            <div class="text-center py-12">
                <svg class="h-10 w-10 text-text-muted mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-text-muted text-sm">No orders yet. Your purchase history will appear here.</p>
            </div>
        </div>

        {{-- Settings Tab --}}
        <div x-show="activeTab === 'settings'" class="p-6">
            <div class="max-w-lg space-y-6">
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1.5">Display Name</label>
                    <input type="text" value="{{ Auth::user()->name }}" class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1.5">Email</label>
                    <input type="email" value="{{ Auth::user()->email }}" class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30" readonly>
                </div>
                <div class="pt-2">
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 text-primary text-sm font-semibold hover:text-primary/80 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Edit Profile Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
