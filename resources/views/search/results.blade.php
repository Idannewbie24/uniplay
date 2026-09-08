@extends('layouts.app')

@section('content')
<section class="min-h-screen bg-surface">
  <div class="border-b border-border-hairline">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <nav class="flex items-center space-x-2 text-sm font-mono text-text-muted">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
        <span class="text-text-dim">/</span>
        <span class="text-text-primary">Search Results</span>
      </nav>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @php
        $totalResults = $games->count() + $tournaments->count() + $teams->count() + $matches->count() + $topupProducts->count();
    @endphp
    <h1 class="font-display text-3xl font-bold text-text-primary uppercase tracking-wider mb-2">
      Search: <span class="text-primary">"{{ $query }}"</span>
    </h1>
    <p class="text-text-muted text-sm font-mono mb-8">
      {{ $totalResults }} result{{ $totalResults !== 1 ? 's' : '' }} found
    </p>

    @if($matches->count())
    <div class="mb-8">
      <h2 class="font-display text-lg font-bold text-text-primary uppercase tracking-wide mb-4">Matches</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($matches as $match)
        <a href="{{ route('schedule') }}" class="p-4 bg-surface-card border border-border-hairline rounded-xl hover:border-primary/30 transition-all">
          <p class="font-display font-bold text-text-primary text-sm uppercase">{{ $match->teamA->tag ?? '?' }} vs {{ $match->teamB->tag ?? '?' }}</p>
          <p class="text-text-dim text-xs font-mono mt-1">{{ $match->tournament->name ?? '' }} — {{ $match->status }}</p>
          @if($match->scheduled_at)
            <p class="text-text-dim text-[10px] font-mono mt-1">{{ $match->scheduled_at->format('M j, Y H:i') }}</p>
          @endif
        </a>
        @endforeach
      </div>
    </div>
    @endif

    @if($topupProducts->count())
    <div class="mb-8">
      <h2 class="font-display text-lg font-bold text-text-primary uppercase tracking-wide mb-4">Top-Up Products</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($topupProducts as $product)
        <a href="{{ route('topup.show', $product->id) }}" class="p-4 bg-surface-card border border-border-hairline rounded-xl hover:border-primary/30 transition-all">
          <p class="font-display font-bold text-text-primary text-sm uppercase">{{ $product->name }}</p>
          <p class="text-text-dim text-xs font-mono mt-1">{{ $product->game->name ?? '' }}</p>
        </a>
        @endforeach
      </div>
    </div>
    @endif

    @if($games->count())
    <div class="mb-8">
      <h2 class="font-display text-lg font-bold text-text-primary uppercase tracking-wide mb-4">Games</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($games as $game)
        <a href="{{ route('topup.index', ['category' => $game->category]) }}"
           class="flex items-center gap-4 p-4 bg-surface-card border border-border-hairline rounded-xl hover:border-primary/30 transition-all">
          <div class="w-12 h-12 rounded-xl bg-surface-elevated flex items-center justify-center flex-shrink-0">
            <span class="font-display text-lg font-bold text-primary">{{ substr($game->name, 0, 1) }}</span>
          </div>
          <div>
            <p class="font-display font-bold text-text-primary text-sm uppercase">{{ $game->name }}</p>
            <p class="text-text-dim text-xs font-mono">{{ ucfirst($game->category) }}</p>
          </div>
        </a>
        @endforeach
      </div>
    </div>
    @endif

    @if($tournaments->count())
    <div class="mb-8">
      <h2 class="font-display text-lg font-bold text-text-primary uppercase tracking-wide mb-4">Tournaments</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($tournaments as $tournament)
        <div class="p-4 bg-surface-card border border-border-hairline rounded-xl">
          <p class="font-display font-bold text-text-primary text-sm uppercase">{{ $tournament->name }}</p>
          <p class="text-text-dim text-xs font-mono mt-1">{{ $tournament->stage ?? $tournament->status }}</p>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    @if($teams->count())
    <div class="mb-8">
      <h2 class="font-display text-lg font-bold text-text-primary uppercase tracking-wide mb-4">Teams</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($teams as $team)
        <div class="flex items-center gap-4 p-4 bg-surface-card border border-border-hairline rounded-xl">
          <div class="w-12 h-12 rounded-full bg-surface-elevated border border-border-elevated flex items-center justify-center flex-shrink-0">
            <span class="font-display text-sm font-bold text-secondary">{{ $team->tag }}</span>
          </div>
          <div>
            <p class="font-display font-bold text-text-primary text-sm uppercase">{{ $team->name }}</p>
            <p class="text-text-dim text-xs font-mono">Tag: {{ $team->tag }}</p>
          </div>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    @if($totalResults === 0)
    <div class="text-center py-20">
      <div class="w-20 h-20 mx-auto rounded-2xl bg-surface-elevated border border-border-hairline flex items-center justify-center mb-6">
        <svg class="w-10 h-10 text-text-dim" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
      </div>
      <h3 class="font-display text-xl font-bold text-text-primary uppercase">No Results Found</h3>
      <p class="text-text-dim mt-2">Try searching for a different term.</p>
    </div>
    @endif
  </div>
</section>
@endsection
