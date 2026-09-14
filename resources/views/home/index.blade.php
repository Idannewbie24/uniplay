@extends('layouts.app')

@section('title', 'UniPlay — Arena Home')

@section('content')
<section class="mb-10">
    @if($featuredMatch)
        @php
            $match = $featuredMatch;
            $isLive = $match->status === 'live';
        @endphp

        <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-0">

                {{-- Left: Match Details --}}
                <div class="lg:col-span-2 p-6 lg:p-8">
                    <div class="flex flex-wrap items-center gap-3 mb-6">
                        <span class="font-display text-xs font-semibold uppercase tracking-wider text-tertiary bg-tertiary/10 px-2.5 py-1 rounded">
                            {{ $match->tournament->name }}
                        </span>
                        <span class="text-xs font-mono font-bold text-text-muted bg-surface-elevated px-2 py-1 rounded">
                            {{ $match->stage ?? $match->tournament->stage }}
                        </span>
                        @if($isLive)
                            <span class="inline-flex items-center gap-1 bg-primary/20 text-primary text-xs font-semibold px-2.5 py-1 rounded-full">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-primary"></span>
                                </span>
                                LIVE NOW
                            </span>
                        @endif
                    </div>

                    {{-- Teams --}}
                    <div class="flex items-center justify-between mb-8">
                        {{-- Team A --}}
                        <div class="flex-1 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 lg:w-20 lg:h-20 rounded-xl bg-surface-elevated border border-border-hairline overflow-hidden mb-3">
                                @if($match->teamA->logo)
                                    <img src="{{ asset('storage/' . $match->teamA->logo) }}" alt="{{ $match->teamA->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="font-display text-xl lg:text-2xl font-bold text-primary">{{ $match->teamA->tag }}</span>
                                @endif
                            </div>
                            <p class="font-display text-sm lg:text-base font-semibold text-text-primary">{{ $match->teamA->name }}</p>
                            <p class="font-mono text-xs text-text-muted mt-0.5">{{ $match->teamA->tag }}</p>
                        </div>

                        {{-- Score / VS --}}
                        <div class="px-4 lg:px-8 text-center">
                            @if($isLive)
                                <div class="font-mono text-4xl lg:text-5xl font-bold text-text-primary tracking-tight">
                                    {{ $match->score_a }}<span class="text-text-muted mx-1">:</span>{{ $match->score_b }}
                                </div>
                            @else
                                <div class="font-display text-2xl lg:text-3xl font-bold text-text-muted">VS</div>
                            @endif
                        </div>

                        {{-- Team B --}}
                        <div class="flex-1 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 lg:w-20 lg:h-20 rounded-xl bg-surface-elevated border border-border-hairline overflow-hidden mb-3">
                                @if($match->teamB->logo)
                                    <img src="{{ asset('storage/' . $match->teamB->logo) }}" alt="{{ $match->teamB->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="font-display text-xl lg:text-2xl font-bold text-primary">{{ $match->teamB->tag }}</span>
                                @endif
                            </div>
                            <p class="font-display text-sm lg:text-base font-semibold text-text-primary">{{ $match->teamB->name }}</p>
                            <p class="font-mono text-xs text-text-muted mt-0.5">{{ $match->teamB->tag }}</p>
                        </div>
                    </div>

                    {{-- Countdown Timer (real-time, never resets on refresh) --}}
                    <div x-data="matchCountdown()" class="mb-8">
                        <p class="text-text-muted text-xs uppercase tracking-wider mb-3 font-display font-semibold">
                            {{ $isLive ? 'Match in Progress' : 'Starts in' }}
                        </p>
                        @unless($isLive)
                            <div class="flex items-center gap-3">
                                <div class="text-center">
                                    <div class="font-mono text-2xl lg:text-3xl font-bold text-text-primary bg-surface-elevated rounded-lg px-3 py-2 min-w-[3.5rem]">
                                        <span x-text="days">00</span>
                                    </div>
                                    <span class="text-text-muted text-[10px] uppercase tracking-wider mt-1 block">Days</span>
                                </div>
                                <span class="font-mono text-text-muted text-lg mt-[-1rem]">:</span>
                                <div class="text-center">
                                    <div class="font-mono text-2xl lg:text-3xl font-bold text-text-primary bg-surface-elevated rounded-lg px-3 py-2 min-w-[3.5rem]">
                                        <span x-text="hours">00</span>
                                    </div>
                                    <span class="text-text-muted text-[10px] uppercase tracking-wider mt-1 block">Hours</span>
                                </div>
                                <span class="font-mono text-text-muted text-lg mt-[-1rem]">:</span>
                                <div class="text-center">
                                    <div class="font-mono text-2xl lg:text-3xl font-bold text-text-primary bg-surface-elevated rounded-lg px-3 py-2 min-w-[3.5rem]">
                                        <span x-text="minutes">00</span>
                                    </div>
                                    <span class="text-text-muted text-[10px] uppercase tracking-wider mt-1 block">Min</span>
                                </div>
                                <span class="font-mono text-text-muted text-lg mt-[-1rem]">:</span>
                                <div class="text-center">
                                    <div class="font-mono text-2xl lg:text-3xl font-bold text-text-primary bg-surface-elevated rounded-lg px-3 py-2 min-w-[3.5rem]">
                                        <span x-text="seconds">00</span>
                                    </div>
                                    <span class="text-text-muted text-[10px] uppercase tracking-wider mt-1 block">Sec</span>
                                </div>
                            </div>
                        @endunless
                    </div>

                    {{-- CTA Buttons --}}
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('tickets.index') }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-secondary text-surface rounded-lg font-semibold text-sm hover:bg-secondary/90 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                            Book Arena Tickets
                        </a>
                    </div>
                </div>

                {{-- Right: Stream Info Panel --}}
                <div class="bg-surface-elevated p-6 lg:p-8 flex flex-col justify-between border-t lg:border-t-0 lg:border-l border-border-hairline">
                    <div>
                        <h3 class="font-display text-xs font-semibold uppercase tracking-wider text-text-muted mb-4">Stream Info</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-text-muted text-sm">Quality</span>
                                <span class="text-tertiary text-sm font-semibold">{{ $settings['stream_quality'] ?? '—' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-text-muted text-sm">Language</span>
                                <span class="text-text-secondary text-sm">{{ $settings['stream_language'] ?? '—' }}</span>
                            </div>
                            <div class="h-px bg-border-hairline"></div>
                            <div class="flex items-center justify-between">
                                <span class="text-text-muted text-sm">Venue</span>
                                <span class="text-text-secondary text-sm text-right max-w-[140px]">{{ $match->venue->name ?? 'TBA' }}</span>
                            </div>
                            @if(!empty($settings['stream_viewers']))
                                <div class="flex items-center justify-between">
                                    <span class="text-text-muted text-sm">Viewers</span>
                                    <span class="text-secondary text-sm font-semibold font-mono">{{ number_format((int) $settings['stream_viewers']) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Community Prediction --}}
                    <div class="mt-8" x-data="predictionVote({
                        matchId: {{ $match->id }},
                        a: {{ $predA ?? 'null' }},
                        b: {{ $predB ?? 'null' }},
                        my: {{ $myPrediction ? "'" . $myPrediction->team . "'" : 'null' }},
                        guest: {{ auth()->check() ? 'false' : 'true' }},
                        loginUrl: '{{ route('login') }}'
                    })">
                        <h3 class="font-display text-xs font-semibold uppercase tracking-wider text-text-muted mb-3">Community Prediction</h3>

                        {{-- Selectable teams --}}
                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <button type="button" @click="pick('a')"
                                    class="px-3 py-2 rounded-lg border text-xs font-semibold uppercase tracking-wider transition-all cursor-pointer"
                                    :class="selected === 'a' ? 'border-primary bg-primary/15 text-primary' : 'border-border-hairline bg-surface-card text-text-secondary hover:border-primary/40'">
                                {{ $match->teamA->tag }}
                            </button>
                            <button type="button" @click="pick('b')"
                                    class="px-3 py-2 rounded-lg border text-xs font-semibold uppercase tracking-wider transition-all cursor-pointer"
                                    :class="selected === 'b' ? 'border-tertiary bg-tertiary/15 text-tertiary' : 'border-border-hairline bg-surface-card text-text-secondary hover:border-tertiary/40'">
                                {{ $match->teamB->tag }}
                            </button>
                        </div>

                        {{-- Prediction bar --}}
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <span class="text-text-secondary font-semibold">{{ $match->teamA->tag }}</span>
                            <span class="text-text-secondary font-semibold">{{ $match->teamB->tag }}</span>
                        </div>
                        <div class="h-3 bg-surface-card rounded-full overflow-hidden flex">
                            <div class="h-full bg-primary transition-all duration-500" :style="'width: ' + (pctA()) + '%'"></div>
                            <div class="h-full bg-tertiary transition-all duration-500" :style="'width: ' + (pctB()) + '%'"></div>
                        </div>
                        <div class="flex items-center justify-between text-xs mt-1.5">
                            <span class="text-primary font-mono font-bold">
                                <template x-if="total() > 0"><span x-text="pctA() + '%'"></span></template>
                                <template x-if="total() === 0">—</template>
                            </span>
                            <span class="text-text-dim text-[10px] font-mono">
                                <template x-if="total() > 0"><span x-text="total() + ' vote' + (total() === 1 ? '' : 's')"></span></template>
                                <template x-if="total() === 0">No votes yet</template>
                            </span>
                            <span class="text-tertiary font-mono font-bold">
                                <template x-if="total() > 0"><span x-text="pctB() + '%'"></span></template>
                                <template x-if="total() === 0">—</template>
                            </span>
                        </div>

                        {{-- Vote button (only clickable when a team is selected) --}}
                        <button type="button" @click="submit()"
                                :disabled="!selected || submitting"
                                class="mt-3 w-full py-2.5 rounded-lg bg-primary text-white font-display text-xs font-bold uppercase tracking-wider hover:bg-primary/90 transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">
                            <template x-if="!submitting && !my">Submit Prediction</template>
                            <template x-if="!submitting && my">Change Vote</template>
                            <template x-if="submitting">Submitting...</template>
                        </button>
                        <template x-if="guest">
                            <p class="mt-2 text-[10px] text-text-dim text-center">You'll be asked to sign in after picking a team.</p>
                        </template>
                        <p class="mt-2 text-[10px] text-secondary font-medium text-center" x-show="message" x-text="message"></p>
                    </div>
                </div>
            </div>
        </div>

    @else
        {{-- Empty state: No upcoming match --}}
        <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-0">
                <div class="lg:col-span-2 p-6 lg:p-8 flex flex-col items-center justify-center text-center min-h-[300px]">
                    <div class="w-16 h-16 rounded-xl bg-surface-elevated border border-border-hairline flex items-center justify-center mb-4">
                        <svg class="h-8 w-8 text-text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="font-display text-lg font-bold text-text-secondary mb-1 uppercase tracking-wider">No Upcoming Match</h2>
                    <p class="text-text-muted text-sm max-w-xs">Hang tight — the next matchup will appear here as soon as it's scheduled by the admin.</p>
                </div>

                <div class="bg-surface-elevated p-6 lg:p-8 flex flex-col items-center justify-center text-center border-t lg:border-t-0 lg:border-l border-border-hairline min-h-[200px]">
                    <h3 class="font-display text-xs font-semibold uppercase tracking-wider text-text-muted mb-3">Stream Info</h3>
                    <p class="text-text-dim text-sm italic">No upcoming match</p>
                </div>
            </div>
        </div>
    @endif
</section>

{{-- Top-Up Catalog --}}
<section class="mb-10" x-data="{ activeTab: 'all' }">
    <div class="flex items-center justify-between mb-6 px-1 lg:px-3">
        <h2 class="font-display text-xl font-bold text-text-primary">Top-Up Catalog</h2>
        <a href="{{ route('topup.index') }}" class="inline-flex items-center gap-1.5 text-primary text-sm font-semibold hover:text-primary/80 transition-colors">
            View All Products
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
        </a>
    </div>
    <div class="flex items-center gap-2 mb-6 px-1 lg:px-3">
        <button @click="activeTab = 'all'"
                :class="activeTab === 'all' ? 'bg-primary text-white' : 'bg-surface-card text-text-muted hover:text-text-secondary'"
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors cursor-pointer">All Games</button>
        <button @click="activeTab = 'mobile'"
                :class="activeTab === 'mobile' ? 'bg-primary text-white' : 'bg-surface-card text-text-muted hover:text-text-secondary'"
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors cursor-pointer">Mobile Esports</button>
        <button @click="activeTab = 'pc'"
                :class="activeTab === 'pc' ? 'bg-primary text-white' : 'bg-surface-card text-text-muted hover:text-text-secondary'"
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors cursor-pointer">PC Arena</button>
        <button @click="activeTab = 'console'"
                :class="activeTab === 'console' ? 'bg-primary text-white' : 'bg-surface-card text-text-muted hover:text-text-secondary'"
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors cursor-pointer">Console</button>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        @php
            $gamesList = $games ?? collect();
        @endphp
        @forelse($gamesList as $game)
            <div x-show="activeTab === 'all' || activeTab === '{{ $game->category }}'">
                @php
                    $firstProduct = $game->topupProducts->first();
                    $price = $firstProduct && $firstProduct->denominations->count() ? $firstProduct->denominations->min('price') : null;
                @endphp
                <a href="{{ $firstProduct ? route('topup.show', $firstProduct) : route('topup.index') }}"
                   class="block bg-surface-card border border-border-hairline rounded-xl overflow-hidden hover:border-primary/30 transition-all group">
                    <div class="aspect-square bg-surface-elevated flex items-center justify-center overflow-hidden">
                        @if($game->icon)
                            <img src="{{ asset('storage/' . $game->icon) }}" alt="{{ $game->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        @else
                            <span class="font-display text-2xl font-bold text-primary/40">{{ strtoupper(substr($game->name, 0, 2)) }}</span>
                        @endif
                    </div>
                    <div class="p-3">
                        <h3 class="text-sm font-semibold text-text-primary truncate">{{ $game->name }}</h3>
                        <div class="flex items-center justify-between mt-1.5">
                            @if($price)
                                <span class="text-primary text-sm font-bold font-mono">Dari Rp {{ number_format($price, 0, ',', '.') }}</span>
                            @else
                                <span class="text-text-muted text-xs">View Products</span>
                            @endif
                            <span class="text-[10px] uppercase font-bold text-tertiary bg-tertiary/10 px-1.5 py-0.5 rounded">{{ $game->category }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-text-muted text-sm">No games available yet. Check back soon!</p>
            </div>
        @endforelse
    </div>
</section>

{{-- Arena Grandstand Tickets --}}
<section class="mb-10 px-1 lg:px-3">
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-display text-xl font-bold text-text-primary">Arena Grandstand Tickets</h2>
        <a href="{{ route('tickets.index') }}" class="text-primary text-sm font-semibold hover:text-primary/80 transition-colors">View All &rarr;</a>
    </div>
    @if($upcomingMatches->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($upcomingMatches->take(3) as $match)
                @php
                    $lowestBatch = $match->ticketBatches->where('seats_remaining', '>', 0)->sortBy('price')->first();
                    $totalSeats = $match->ticketBatches->sum('seats_total');
                    $remainingSeats = $match->ticketBatches->sum('seats_remaining');
                    $soldPct = $totalSeats > 0 ? round((($totalSeats - $remainingSeats) / $totalSeats) * 100) : 0;
                @endphp
                <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden hover:border-primary/20 transition-all">
                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-[10px] uppercase font-bold text-tertiary bg-tertiary/10 px-2 py-0.5 rounded">{{ $match->tournament->name ?? 'Tournament' }}</span>
                            <span class="text-[10px] uppercase font-bold text-text-muted bg-surface-elevated px-2 py-0.5 rounded">{{ $match->tournament->stage ?? 'Stage' }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-center flex-1">
                                <p class="font-display text-sm font-semibold text-text-primary">{{ $match->teamA->name ?? 'TBD' }}</p>
                            </div>
                            <span class="font-display text-xs font-bold text-text-muted px-3">VS</span>
                            <div class="text-center flex-1">
                                <p class="font-display text-sm font-semibold text-text-primary">{{ $match->teamB->name ?? 'TBD' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-text-muted mb-3">
                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $match->venue->name ?? 'TBA' }}</span>
                        </div>
                        <div class="mb-4">
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <span class="text-text-muted">Seat Availability</span>
                                <span class="text-text-secondary font-mono font-semibold">{{ $remainingSeats }} / {{ $totalSeats }}</span>
                            </div>
                            <div class="h-2 bg-surface-elevated rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 {{ $soldPct > 80 ? 'bg-red-500' : ($soldPct > 50 ? 'bg-secondary' : 'bg-tertiary') }}"
                                     style="width: {{ $soldPct }}%"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            @if($lowestBatch)
                                <span class="text-primary font-bold font-mono text-lg">Dari Rp {{ number_format($lowestBatch->price, 0, ',', '.') }}</span>
                            @else
                                <span class="text-text-muted text-sm">Sold Out</span>
                            @endif
                            @if($lowestBatch)
                                <a href="{{ route('tickets.index') }}" class="px-4 py-2 bg-primary text-white rounded-lg text-xs font-semibold hover:bg-primary/90 transition-colors">Select Seat</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-surface-card border border-dashed border-border-hairline rounded-xl p-10 text-center">
            <div class="w-14 h-14 mx-auto rounded-xl bg-surface-elevated flex items-center justify-center mb-4">
                <svg class="h-7 w-7 text-text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
            </div>
            <h3 class="font-display text-base font-bold text-text-secondary uppercase tracking-wider">No Tickets Available</h3>
            <p class="text-text-muted text-sm mt-1 max-w-sm mx-auto">Tickets will appear here as soon as the admin opens sales for an upcoming match.</p>
        </div>
    @endif
</section>

{{-- Banner Advertisements --}}
@php
    $banners = \App\Models\Banner::active()->latest()->get();
@endphp
@if($banners->count())
<section class="mb-10 px-1 lg:px-3">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($banners->take(4) as $banner)
            <div class="relative group bg-surface-card border border-border-hairline rounded-xl overflow-hidden hover:border-primary/30 transition-all cursor-pointer"
                 x-data="{ show: false }" @mouseenter="show = true" @mouseleave="show = false">
                @if($banner->image)
                    <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="w-full h-40 object-cover">
                @else
                    <div class="w-full h-40 bg-gradient-to-br from-primary/20 to-tertiary/10 flex items-center justify-center">
                        <span class="font-display text-lg font-bold text-primary uppercase">{{ $banner->title }}</span>
                    </div>
                @endif
                <div class="p-4">
                    <h3 class="font-display text-sm font-bold text-text-primary uppercase tracking-wider" x-text="'{{ addslashes($banner->title) }}'">{{ $banner->title }}</h3>
                    @if($banner->description)
                        <p class="text-text-muted text-xs mt-1 line-clamp-2" x-show="show" x-transition>{{ $banner->description }}</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

{{-- Trending Shorts --}}
<section class="mb-10 px-1 lg:px-3">
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-display text-xl font-bold text-text-primary">Trending Shorts</h2>
        <a href="{{ route('shorts') }}" class="text-primary text-sm font-semibold hover:text-primary/80 transition-colors">View All &rarr;</a>
    </div>
    @if($shorts->count())
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach($shorts->take(5) as $short)
                <a href="{{ route('shorts') }}" class="group" x-data="{ hovering: false }"
                   @mouseenter="hovering = true" @mouseleave="hovering = false">
                    <div class="relative aspect-[9/16] rounded-xl overflow-hidden bg-surface-card border border-border-hairline">
                        @if($short->thumbnail)
                            <img src="{{ asset('storage/' . $short->thumbnail) }}" alt="{{ $short->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" :class="hovering ? 'scale-110' : ''">
                        @else
                            <div class="w-full h-full bg-surface-elevated flex items-center justify-center">
                                <svg class="h-10 w-10 text-text-muted" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/></svg>
                            </div>
                        @endif
                        @if($short->video_path)
                            <template x-if="hovering">
                                <video
                                    src="{{ asset('storage/shorts/videos') }}/{{ basename($short->video_path) }}"
                                    class="absolute inset-0 w-full h-full object-cover"
                                    muted autoplay loop playsinline preload="metadata"
                                ></video>
                            </template>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-3">
                            <p class="text-white text-xs font-semibold line-clamp-2 leading-tight">{{ $short->title }}</p>
                            <div class="flex items-center gap-2 mt-1.5">
                                <span class="text-white/60 text-[10px] font-mono">
                                    {{ floor($short->duration_seconds / 60) }}:{{ str_pad($short->duration_seconds % 60, 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <span class="text-white/40">·</span>
                                <span class="text-white/60 text-[10px] font-mono">{{ number_format($short->views_count) }} views</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-surface-card border border-dashed border-border-hairline rounded-xl p-10 text-center">
            <div class="w-14 h-14 mx-auto rounded-xl bg-surface-elevated flex items-center justify-center mb-4">
                <svg class="h-7 w-7 text-text-dim" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/></svg>
            </div>
            <h3 class="font-display text-base font-bold text-text-secondary uppercase tracking-wider">No Shorts Yet</h3>
            <p class="text-text-muted text-sm mt-1 max-w-sm mx-auto">Latest highlights will appear here as soon as the admin publishes them.</p>
        </div>
    @endif
</section>

{{-- Secure Payments --}}
<section class="mb-6 px-1 lg:px-3">
    <div class="bg-surface-card border border-border-hairline rounded-xl p-6">
        <div class="flex flex-col sm:flex-row items-center justify-center gap-6 text-text-muted text-xs">
            <div class="flex items-center gap-3">
                <svg class="h-8 w-8 text-tertiary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                <span class="font-semibold text-text-secondary">Secure Payments</span>
            </div>
            <div class="hidden sm:block h-4 w-px bg-border-hairline"></div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 bg-surface-elevated rounded text-text-muted font-semibold">GoPay</span>
                <span class="px-2.5 py-1 bg-surface-elevated rounded text-text-muted font-semibold">Dana</span>
                <span class="px-2.5 py-1 bg-surface-elevated rounded text-text-muted font-semibold">QRIS</span>
                <span class="px-2.5 py-1 bg-surface-elevated rounded text-text-muted font-semibold">M-Bank</span>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function matchCountdown() {
        const target = @json($featuredMatch && $featuredMatch->scheduled_at ? $featuredMatch->scheduled_at->timestamp * 1000 : null);
        return {
            days: '00',
            hours: '00',
            minutes: '00',
            seconds: '00',
            init() {
                if (target) { this.update(); setInterval(() => this.update(), 1000); }
            },
            update() {
                const diff = Math.max(0, target - Date.now());
                const d = Math.floor(diff / 86400000);
                const h = Math.floor((diff % 86400000) / 3600000);
                const m = Math.floor((diff % 3600000) / 60000);
                const s = Math.floor((diff % 60000) / 1000);
                this.days = String(d).padStart(2, '0');
                this.hours = String(h).padStart(2, '0');
                this.minutes = String(m).padStart(2, '0');
                this.seconds = String(s).padStart(2, '0');
            }
        };
    }

    function predictionVote(config) {
        return {
            matchId: config.matchId,
            a: config.a ?? 0,
            b: config.b ?? 0,
            my: config.my,
            selected: config.my,
            guest: config.guest,
            loginUrl: config.loginUrl,
            submitting: false,
            message: '',
            pick(team) {
                this.selected = this.selected === team ? null : team;
                this.message = '';
            },
            pctA() {
                const t = this.a + this.b;
                return t ? Math.round((this.a / t) * 100) : 0;
            },
            pctB() {
                const t = this.a + this.b;
                return t ? Math.round((this.b / t) * 100) : 0;
            },
            total() {
                return this.a + this.b;
            },
            async submit() {
                if (!this.selected || this.submitting) return;
                if (this.guest) { window.location.href = this.loginUrl; return; }
                this.submitting = true;
                try {
                    const res = await fetch('/predictions', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ match_id: this.matchId, team: this.selected })
                    });
                    const data = await res.json();
                    if (data.ok) {
                        this.a = data.percentage_a;
                        this.b = data.percentage_b;
                        this.my = this.selected;
                        this.message = 'Your vote has been submitted!';
                    }
                } catch (e) {
                    this.message = 'Something went wrong. Please try again.';
                } finally {
                    this.submitting = false;
                }
            }
        };
    }
</script>
@endpush