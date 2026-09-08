@extends('layouts.app')

@section('content')
<section class="min-h-screen bg-surface" x-data="standingsTab()">

  {{-- Breadcrumb --}}
  <div class="border-b border-border-hairline">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <nav class="flex items-center space-x-2 text-sm font-mono text-text-muted">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
        <span class="text-text-dim">/</span>
        <span class="text-text-primary">Leaderboards & Standings</span>
      </nav>
    </div>
  </div>

  {{-- Header --}}
  <div class="relative overflow-hidden border-b border-border-hairline">
    <div class="absolute inset-0 bg-gradient-to-br from-secondary/8 via-transparent to-primary/5"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative">
      <h1 class="font-display text-4xl sm:text-5xl font-bold text-text-primary uppercase tracking-wider">
        Leaderboards <span class="text-secondary">&</span> Standings
      </h1>
      <p class="mt-3 text-text-muted text-lg max-w-2xl">
        Live rankings and tournament standings updated after every match.
      </p>
      <div class="flex items-center gap-2 mt-4">
        <span class="w-2 h-2 rounded-full bg-tertiary animate-pulse"></span>
        <span class="text-tertiary text-xs font-mono uppercase">Live Data</span>
      </div>
    </div>
  </div>

  {{-- Content --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Tournament Tabs --}}
    <div class="flex flex-wrap gap-2 mb-8">
      @foreach($tournaments as $tournament)
        <button
          @click="activeTournament = {{ $tournament->id }}"
          :class="activeTournament === {{ $tournament->id }}
            ? 'bg-secondary text-black border-secondary shadow-lg shadow-secondary/20'
            : 'bg-surface-card text-text-muted border-border-hairline hover:border-border-elevated hover:text-text-primary'"
          class="px-5 py-2.5 rounded-lg border font-display text-sm uppercase tracking-wider transition-all duration-200"
        >
          {{ $tournament->name }}
        </button>
      @endforeach
    </div>

    {{-- Standings Tables --}}
    @foreach($tournaments as $tournament)
      <div
        x-show="activeTournament === {{ $tournament->id }}"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
      >
        <div class="bg-surface-card border border-border-hairline rounded-2xl overflow-hidden">
          {{-- Table Header --}}
          <div class="px-6 py-4 border-b border-border-hairline bg-surface-elevated">
            <div class="grid grid-cols-12 gap-4 items-center">
              <div class="col-span-1 text-text-dim text-xs font-mono uppercase">Rank</div>
              <div class="col-span-5 text-text-dim text-xs font-mono uppercase">Team</div>
              <div class="col-span-2 text-text-dim text-xs font-mono uppercase text-center">Wins</div>
              <div class="col-span-2 text-text-dim text-xs font-mono uppercase text-center">Losses</div>
              <div class="col-span-2 text-text-dim text-xs font-mono uppercase text-center">Points</div>
            </div>
          </div>

          {{-- Standings Rows --}}
          @php $rankings = $standings[$tournament->id] ?? collect(); @endphp
          @foreach($rankings as $index => $standing)
            @php
              $rank = $index + 1;
              $isTop3 = $rank <= 3;
            @endphp
            <div
              class="px-6 py-4 border-b border-border-hairline last:border-0 hover:bg-surface-card-hover transition-colors {{ $isTop3 ? 'bg-surface-elevated/50' : '' }}"
            >
              <div class="grid grid-cols-12 gap-4 items-center">

                {{-- Rank --}}
                <div class="col-span-1">
                  @if($rank === 1)
                    <div class="w-10 h-10 rounded-xl bg-secondary/10 border border-secondary/30 flex items-center justify-center">
                      <span class="font-mono font-bold text-secondary text-lg">1</span>
                    </div>
                  @elseif($rank === 2)
                    <div class="w-10 h-10 rounded-xl bg-[#C0C0C0]/10 border border-[#C0C0C0]/30 flex items-center justify-center">
                      <span class="font-mono font-bold text-[#C0C0C0] text-lg">2</span>
                    </div>
                  @elseif($rank === 3)
                    <div class="w-10 h-10 rounded-xl bg-[#CD7F32]/10 border border-[#CD7F32]/30 flex items-center justify-center">
                      <span class="font-mono font-bold text-[#CD7F32] text-lg">3</span>
                    </div>
                  @else
                    <div class="w-10 h-10 rounded-xl bg-surface-elevated border border-border-hairline flex items-center justify-center">
                      <span class="font-mono font-bold text-text-dim text-sm">{{ $rank }}</span>
                    </div>
                  @endif
                </div>

                {{-- Team --}}
                <div class="col-span-5 flex items-center gap-3">
                  <div class="w-10 h-10 rounded-lg bg-surface-elevated border border-border-hairline flex items-center justify-center overflow-hidden flex-shrink-0">
                    @if($standing->team?->logo)
                      <img src="{{ asset('storage/' . $standing->team->logo) }}" alt="{{ $standing->team->name }}" class="w-full h-full object-contain">
                    @else
                      <span class="font-display text-xs font-bold text-text-dim">{{ substr($standing->team->name ?? 'T', 0, 2) }}</span>
                    @endif
                  </div>
                  <div>
                    <p class="font-display font-bold text-text-primary text-sm uppercase {{ $isTop3 ? 'text-base' : '' }}">
                      {{ $standing->team->name ?? 'Unknown Team' }}
                    </p>
                    @if($rank <= 3)
                      <span class="text-[9px] font-mono uppercase
                        {{ $rank === 1 ? 'text-secondary' : '' }}
                        {{ $rank === 2 ? 'text-[#C0C0C0]' : '' }}
                        {{ $rank === 3 ? 'text-[#CD7F32]' : '' }}
                      ">
                        {{ $rank === 1 ? '👑 Champion' : ($rank === 2 ? '🥈 Runner-up' : '🥉 3rd Place') }}
                      </span>
                    @endif
                  </div>
                </div>

                {{-- Wins --}}
                <div class="col-span-2 text-center">
                  <span class="font-mono font-bold text-tertiary text-sm">{{ $standing->wins ?? 0 }}</span>
                </div>

                {{-- Losses --}}
                <div class="col-span-2 text-center">
                  <span class="font-mono font-bold text-primary text-sm">{{ $standing->losses ?? 0 }}</span>
                </div>

                {{-- Points --}}
                <div class="col-span-2 text-center">
                  <span class="font-mono font-bold {{ $isTop3 ? 'text-secondary text-lg' : 'text-text-primary text-sm' }}">
                    {{ $standing->points ?? 0 }}
                  </span>
                </div>
              </div>
            </div>
          @endforeach

          @if($rankings->isEmpty())
            <div class="px-6 py-12 text-center">
              <p class="text-text-dim font-mono text-sm">No standings available for this tournament yet.</p>
            </div>
          @endif
        </div>

        {{-- Legend --}}
        <div class="flex flex-wrap items-center gap-6 mt-4 px-2">
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-secondary"></div>
            <span class="text-text-dim text-xs font-mono">Gold (1st Place)</span>
          </div>
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-[#C0C0C0]"></div>
            <span class="text-text-dim text-xs font-mono">Silver (2nd Place)</span>
          </div>
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-[#CD7F32]"></div>
            <span class="text-text-dim text-xs font-mono">Bronze (3rd Place)</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="text-text-dim text-xs font-mono">Points = Wins × 3 + Draws × 1</span>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</section>

@push('scripts')
<script>
function standingsTab() {
  return {
    activeTournament: {{ $tournaments->first()->id ?? '1' }},
  }
}
</script>
@endpush
@endsection