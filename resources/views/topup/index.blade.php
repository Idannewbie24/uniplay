@extends('layouts.app')

@section('content')
<section class="min-h-screen bg-surface">
  {{-- Breadcrumb --}}
  <div class="border-b border-border-hairline">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <nav class="flex items-center space-x-2 text-sm font-mono text-text-muted">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
        <span class="text-text-dim">/</span>
        <span class="text-text-primary">Top Up Hub</span>
      </nav>
    </div>
  </div>

  {{-- Hero Section --}}
  <div class="relative overflow-hidden border-b border-border-hairline">
    <div class="absolute inset-0 bg-gradient-to-br from-primary/10 via-transparent to-tertiary/5"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative">
      <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-text-primary uppercase tracking-wider">
        Instant <span class="text-primary">Top-Up</span> Arena
      </h1>
      <p class="mt-3 text-text-muted text-lg max-w-2xl">
        Fast, secure, and official in-game currency replenishment for your favorite esports titles. Zero delays, maximum value.
      </p>
      <div class="flex items-center gap-4 mt-6">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 border border-primary/30 text-primary text-xs font-mono uppercase">
          <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
          All Systems Online
        </span>
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-tertiary/10 border border-tertiary/30 text-tertiary text-xs font-mono uppercase">
          Instant Delivery
        </span>
      </div>
    </div>
  </div>

  {{-- Filter Tabs + Products --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="topupFilters()">

    {{-- Tab Filters --}}
    <div class="flex flex-wrap gap-2 mb-8">
      <template x-for="tab in tabs" :key="tab.key">
        <button
          @click="activeTab = tab.key"
          :class="activeTab === tab.key
            ? 'bg-primary text-white border-primary shadow-lg shadow-primary/20'
            : 'bg-surface-card text-text-muted border-border-hairline hover:border-border-elevated hover:text-text-primary'"
          class="px-5 py-2.5 rounded-lg border font-display text-sm uppercase tracking-wider transition-all duration-200"
          x-text="tab.label"
        ></button>
      </template>
    </div>

    {{-- Products Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      @foreach($products as $product)
        <div
          class="group bg-surface-card border border-border-hairline rounded-xl overflow-hidden hover:border-border-elevated hover:bg-surface-card-hover transition-all duration-300"
          x-show="activeTab === 'all' || activeTab === '{{ $product->game->category ?? 'all' }}'"
          x-transition:enter="transition ease-out duration-300"
          x-transition:enter-start="opacity-0 scale-95"
          x-transition:enter-end="opacity-100 scale-100"
        >
          {{-- Thumbnail --}}
          <div class="relative h-44 bg-surface-elevated flex items-center justify-center overflow-hidden">
            @if($product->game->icon)
              <img
                src="{{ asset('storage/' . $product->game->icon) }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              >
            @else
              <div class="w-20 h-20 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center">
                <span class="font-display text-3xl font-bold text-primary">{{ substr($product->game->name, 0, 1) }}</span>
              </div>
            @endif

            {{-- Badge --}}
            @if($product->is_official_partner)
              <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md bg-primary text-white text-[10px] font-mono font-bold uppercase tracking-wider">
                OFFICIAL PARTNER
              </span>
            @elseif($product->rating >= 4.7)
              <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md bg-secondary text-black text-[10px] font-mono font-bold uppercase tracking-wider">
                FEATURED
              </span>
            @endif

            {{-- Category Tag --}}
            <span class="absolute top-3 right-3 px-2 py-0.5 rounded bg-black/60 backdrop-blur-sm text-[10px] font-mono text-text-muted uppercase">
              {{ $product->game->category ?? 'Esports' }}
            </span>
          </div>

          {{-- Card Body --}}
          <div class="p-5">
            <h3 class="font-display text-lg font-bold text-text-primary uppercase tracking-wide group-hover:text-primary transition-colors">
              {{ $product->game->name }}
            </h3>
            <p class="text-text-dim text-sm mt-1 line-clamp-2">
              {{ $product->description ?? 'Official in-game currency top-up with instant delivery and anti-ban guarantee.' }}
            </p>

            <div class="flex items-center justify-between mt-5 pt-4 border-t border-border-hairline">
              <div>
                <span class="text-text-dim text-xs font-mono uppercase">Starting from</span>
                <p class="text-primary font-display text-xl font-bold">
                  Rp {{ number_format($product->min_price ?? 10000, 0, ',', '.') }}
                </p>
              </div>
              <a
                href="{{ route('topup.show', $product) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-primary/10 border border-primary/30 text-primary font-display text-sm uppercase tracking-wider hover:bg-primary hover:text-white hover:border-primary transition-all duration-200"
              >
                Top Up
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    {{-- Empty State --}}
    @if($products->isEmpty())
      <div class="text-center py-20">
        <div class="w-20 h-20 mx-auto rounded-2xl bg-surface-elevated border border-border-hairline flex items-center justify-center mb-6">
          <svg class="w-10 h-10 text-text-dim" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
          </svg>
        </div>
        <h3 class="font-display text-xl font-bold text-text-primary uppercase">No Products Available</h3>
        <p class="text-text-dim mt-2">New top-up products are being added regularly. Check back soon!</p>
      </div>
    @endif
  </div>
</section>

@push('scripts')
<script>
function topupFilters() {
  return {
    activeTab: 'all',
    tabs: [
      { key: 'all', label: 'All Games' },
      { key: 'mobile', label: 'Mobile Esports' },
      { key: 'pc', label: 'PC Arena' },
      { key: 'entertainment', label: 'Entertainment' },
    ]
  }
}
</script>
@endpush
@endsection