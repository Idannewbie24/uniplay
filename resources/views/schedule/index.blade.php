@extends('layouts.app')

@section('content')
<section class="min-h-screen bg-surface" x-data="scheduleFilters()">

  {{-- Breadcrumb --}}
  <div class="border-b border-border-hairline">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <nav class="flex items-center space-x-2 text-sm font-mono text-text-muted">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
        <span class="text-text-dim">/</span>
        <span class="text-text-primary">Match Schedule</span>
      </nav>
    </div>
  </div>

  {{-- Header --}}
  <div class="relative overflow-hidden border-b border-border-hairline">
    <div class="absolute inset-0 bg-gradient-to-br from-tertiary/8 via-transparent to-primary/5"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative">
      <h1 class="font-display text-4xl sm:text-5xl font-bold text-text-primary uppercase tracking-wider">
        Match <span class="text-tertiary">Schedule</span>
      </h1>
      <p class="mt-3 text-text-muted text-lg max-w-2xl">
        All upcoming and past fixtures across every tournament. Never miss a game.
      </p>
    </div>
  </div>

  {{-- Filters --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row gap-4 mb-8">
      {{-- Game Filter --}}
      <div class="relative flex-1 max-w-xs">
        <label class="block text-text-muted text-xs font-mono uppercase mb-2">Filter by Game</label>
        <select
          x-model="gameFilter"
          class="w-full appearance-none px-4 py-3 pr-10 rounded-xl bg-surface-card border border-border-hairline text-text-primary font-mono text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-colors cursor-pointer"
        >
          <option value="all">All Games</option>
          @foreach($games as $game)
            <option value="{{ $game->id }}">{{ $game->name }}</option>
          @endforeach
        </select>
        <svg class="absolute right-3 top-[2.35rem] w-4 h-4 text-text-dim pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
      </div>

      {{-- Tournament Filter --}}
      <div class="relative flex-1 max-w-xs">
        <label class="block text-text-muted text-xs font-mono uppercase mb-2">Filter by Tournament</label>
        <select
          x-model="tournamentFilter"
          class="w-full appearance-none px-4 py-3 pr-10 rounded-xl bg-surface-card border border-border-hairline text-text-primary font-mono text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-colors cursor-pointer"
        >
          <option value="all">All Tournaments</option>
          @foreach($tournaments as $tournament)
            <option value="{{ $tournament->id }}">{{ $tournament->name }}</option>
          @endforeach
        </select>
        <svg class="absolute right-3 top-[2.35rem] w-4 h-4 text-text-dim pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
      </div>
    </div>

    {{-- Matches Grouped by Date --}}
    <div class="space-y-10">
      @foreach($matches as $date => $dayMatches)
        <div x-show="true">
          {{-- Date Header --}}
          <div class="flex items-center gap-4 mb-5">
            <div class="flex items-center gap-3">
              <span class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/30 flex flex-col items-center justify-center">
                <span class="font-mono font-bold text-primary text-lg leading-none">{{ \Carbon\Carbon::parse($date)->format('d') }}</span>
                <span class="font-mono text-primary/60 text-[9px] uppercase leading-none mt-0.5">{{ \Carbon\Carbon::parse($date)->format('M') }}</span>
              </span>
              <div>
                <p class="font-display font-bold text-text-primary uppercase">{{ \Carbon\Carbon::parse($date)->format('l') }}</p>
                <p class="text-text-dim text-xs font-mono">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
              </div>
            </div>
            <div class="flex-1 h-px bg-border-hairline"></div>
            <span class="text-text-dim text-xs font-mono">{{ $dayMatches->count() }} match(es)</span>
          </div>

          {{-- Match Cards --}}
          <div class="space-y-3">
            @foreach($dayMatches as $match)
              <div
                class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden hover:border-border-elevated transition-all duration-300"
                x-show="(gameFilter === 'all' || gameFilter === '{{ $match->tournament->game_id ?? '' }}') && (tournamentFilter === 'all' || tournamentFilter === '{{ $match->tournament_id }}')"
                x-transition
              >
                <div class="p-5 flex flex-col sm:flex-row sm:items-center gap-4">

                  {{-- Time --}}
                  <div class="flex-shrink-0 sm:w-24 text-center sm:text-left">
                    <span class="font-mono font-bold text-text-primary text-lg">{{ \Carbon\Carbon::parse($match->scheduled_at)->format('H:i') }}</span>
                    <p class="text-text-dim text-[10px] font-mono uppercase">WIB</p>
                  </div>

                  {{-- Teams --}}
                  <div class="flex-1 flex items-center justify-center sm:justify-start gap-4">
                    {{-- Team A --}}
                    <div class="flex items-center gap-2">
                      <div class="w-8 h-8 rounded-lg bg-surface-elevated border border-border-hairline flex items-center justify-center overflow-hidden">
                        @if($match->teamA?->logo)
                          <img src="{{ asset('storage/' . $match->teamA->logo) }}" alt="{{ $match->teamA->name }}" class="w-full h-full object-contain">
                        @else
                          <span class="font-display text-[10px] font-bold text-text-dim">{{ substr($match->teamA->name ?? 'T', 0, 2) }}</span>
                        @endif
                      </div>
                      <span class="font-display font-bold text-text-primary text-sm uppercase">{{ $match->teamA->name ?? 'TBD' }}</span>
                    </div>

                    {{-- Score / VS --}}
                    <div class="flex-shrink-0">
                      @if($match->status === 'finished' || $match->score_a !== null)
                        <div class="flex items-center gap-1 px-2 py-1 rounded bg-surface-elevated border border-border-hairline">
                          <span class="font-mono font-bold text-text-primary text-sm">{{ $match->score_a ?? 0 }}</span>
                          <span class="text-text-dim text-xs">-</span>
                          <span class="font-mono font-bold text-text-primary text-sm">{{ $match->score_b ?? 0 }}</span>
                        </div>
                      @else
                        <span class="font-display text-xs font-bold text-text-dim uppercase px-2">VS</span>
                      @endif
                    </div>

                    {{-- Team B --}}
                    <div class="flex items-center gap-2">
                      <span class="font-display font-bold text-text-primary text-sm uppercase">{{ $match->teamB->name ?? 'TBD' }}</span>
                      <div class="w-8 h-8 rounded-lg bg-surface-elevated border border-border-hairline flex items-center justify-center overflow-hidden">
                        @if($match->teamB?->logo)
                          <img src="{{ asset('storage/' . $match->teamB->logo) }}" alt="{{ $match->teamB->name }}" class="w-full h-full object-contain">
                        @else
                          <span class="font-display text-[10px] font-bold text-text-dim">{{ substr($match->teamB->name ?? 'T', 0, 2) }}</span>
                        @endif
                      </div>
                    </div>
                  </div>

                  {{-- Meta --}}
                  <div class="flex-shrink-0 flex items-center gap-4 text-xs font-mono">
                    <span class="text-text-dim">{{ $match->tournament->name ?? 'Championship' }}</span>
                    <span class="text-text-dim hidden sm:inline">·</span>
                    <span class="text-text-dim hidden sm:inline">{{ $match->venue->name ?? 'Main Arena' }}</span>
                  </div>

                  {{-- Status Badge --}}
                  <div class="flex-shrink-0">
                    @if($match->status === 'live')
                      <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary text-white text-xs font-mono font-bold uppercase animate-pulse">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                        Live
                      </span>
                    @elseif($match->status === 'upcoming')
                      <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-tertiary/10 border border-tertiary/30 text-tertiary text-xs font-mono font-bold uppercase">
                        Upcoming
                      </span>
                    @elseif($match->status === 'finished')
                      <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-surface-elevated border border-border-hairline text-text-dim text-xs font-mono font-bold uppercase">
                        Finished
                      </span>
                    @else
                      <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-surface-elevated border border-border-hairline text-text-dim text-xs font-mono uppercase">
                        {{ ucfirst($match->status) }}
                      </span>
@endif
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endforeach

      @if($matches->isEmpty())
        <div class="text-center py-16">
          <div class="w-20 h-20 mx-auto rounded-2xl bg-surface-elevated border border-border-hairline flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-text-dim" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
          </div>
          <h3 class="font-display text-xl font-bold text-text-primary uppercase">No Matches Scheduled</h3>
          <p class="text-text-dim mt-2">Check back soon for upcoming fixtures.</p>
        </div>
      @endif
    </div>
  </div>
</section>

@push('scripts')
<script>
function scheduleFilters() {
  return {
    gameFilter: 'all',
    tournamentFilter: 'all',
  }
}
</script>
@endpush
@endsection