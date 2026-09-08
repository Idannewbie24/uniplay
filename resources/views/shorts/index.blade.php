@extends('layouts.app')

@section('content')
@php
    $shortsData = $shorts->map(fn($s) => [
        'id' => $s->id,
        'title' => $s->title,
        'description' => $s->description,
        'video_path' => $s->video_path,
        'video_url' => $s->video_url,
        'views_count' => $s->views_count,
        'creator_name' => $s->creator_name,
    ])->values();
@endphp
<section class="min-h-screen bg-surface" x-data="shortsFilters()">

  {{-- Breadcrumb --}}
  <div class="border-b border-border-hairline">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <nav class="flex items-center space-x-2 text-sm font-mono text-text-muted">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
        <span class="text-text-dim">/</span>
        <span class="text-text-primary">Shorts & Clips</span>
      </nav>
    </div>
  </div>

  {{-- Header --}}
  <div class="relative overflow-hidden border-b border-border-hairline">
    <div class="absolute inset-0 bg-gradient-to-br from-primary/10 via-transparent to-tertiary/5"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative">
      <h1 class="font-display text-4xl sm:text-5xl font-bold text-text-primary uppercase tracking-wider">
        Trending <span class="text-primary">Shorts</span> & Clutch Plays
      </h1>
      <p class="mt-3 text-text-muted text-lg max-w-2xl">
        The most epic moments, clutch plays, and highlight reels from across the competitive scene.
      </p>
      <div class="flex items-center gap-3 mt-5">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 border border-primary/30 text-primary text-xs font-mono uppercase">
          <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/></svg>
          Trending Now
        </span>
      </div>
    </div>
  </div>

  {{-- Content --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Category Filter Chips --}}
    <div class="flex flex-wrap gap-2 mb-8">
      <template x-for="cat in categories" :key="cat.key">
        <button
          @click="activeCategory = cat.key"
          :class="activeCategory === cat.key
            ? 'bg-primary text-white border-primary shadow-lg shadow-primary/20'
            : 'bg-surface-card text-text-muted border-border-hairline hover:border-border-elevated hover:text-text-primary'"
          class="px-4 py-2 rounded-full border font-mono text-xs uppercase tracking-wider transition-all duration-200"
          x-text="cat.label"
        ></button>
      </template>
    </div>

    {{-- Shorts Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
      @foreach($shorts as $short)
        <div
          class="group bg-surface-card border border-border-hairline rounded-xl overflow-hidden hover:border-border-elevated hover:bg-surface-card-hover transition-all duration-300"
          x-show="activeCategory === 'all' || activeCategory === '{{ $short->category_tag ?? 'all' }}'"
          x-transition:enter="transition ease-out duration-300"
          x-transition:enter-start="opacity-0 scale-95"
          x-transition:enter-end="opacity-100 scale-100"
        >
          {{-- Thumbnail --}}
          <div
            class="relative aspect-[9/16] max-h-64 bg-surface-elevated overflow-hidden"
            x-data="{ playing: false }"
            @mouseenter="playing = true"
            @mouseleave="playing = false"
          >
            @if($short->thumbnail)
              <img
                src="{{ asset('storage/' . $short->thumbnail) }}"
                alt="{{ $short->title }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                :class="playing ? 'scale-110' : ''"
              >
            @else
              <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-b from-primary/5 to-surface-elevated">
                <svg class="w-12 h-12 text-text-dim" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-text-dim text-xs font-mono mt-2 uppercase">No Preview</span>
              </div>
            @endif

            {{-- Hover-to-play for local .mp4 shorts --}}
            @if($short->video_path)
              <template x-if="playing">
                <video
                  :src="getVideoSrc('{{ $short->video_path }}')"
                  class="absolute inset-0 w-full h-full object-cover"
                  muted
                  autoplay
                  loop
                  playsinline
                  preload="metadata"
                ></video>
              </template>
            @endif

            {{-- Play Overlay --}}
            <div class="absolute inset-0 flex items-center justify-center bg-black/0 group-hover:bg-black/20 transition-all duration-300">
              <div class="w-12 h-12 rounded-full bg-primary/90 flex items-center justify-center opacity-0 group-hover:opacity-100 scale-75 group-hover:scale-100 transition-all duration-300 shadow-lg shadow-primary/30">
                <svg class="w-5 h-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
              </div>
            </div>

            {{-- Duration Overlay --}}
            <span class="absolute bottom-2 right-2 px-2 py-0.5 rounded bg-black/70 backdrop-blur-sm text-white text-[10px] font-mono font-bold">
              {{ floor(($short->duration_seconds ?? 0) / 60) }}:{{ str_pad(($short->duration_seconds ?? 0) % 60, 2, '0', STR_PAD_LEFT) }}
            </span>

            {{-- View Count --}}
            <span class="absolute top-2 right-2 flex items-center gap-1 px-2 py-0.5 rounded bg-black/60 backdrop-blur-sm text-white text-[10px] font-mono">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              {{ number_format($short->views_count ?? 0) }}
            </span>

            {{-- Category Badge --}}
            <span
              @class([
                'absolute top-2 left-2 px-2 py-0.5 rounded text-[9px] font-mono font-bold uppercase',
                'bg-primary/90 text-white' => ($short->category_tag ?? '') === 'clutch',
                'bg-secondary/90 text-black' => ($short->category_tag ?? '') === 'ace',
                'bg-tertiary/90 text-black' => ($short->category_tag ?? '') === 'mvp',
                'bg-[#9B59B6]/90 text-white' => ($short->category_tag ?? '') === 'teamfight',
                'bg-[#E67E22]/90 text-white' => ($short->category_tag ?? '') === 'highlight',
                'bg-surface-elevated/90 text-text-primary border border-border-hairline' => !in_array(($short->category_tag ?? ''), ['clutch', 'ace', 'mvp', 'teamfight', 'highlight']),
              ])
            >
              {{ ucfirst($short->category_tag ?? 'clip') }}
            </span>

            {{-- Maximize / Fullscreen Button --}}
            <button
              @click="openShort({{ $short->id }})"
              class="absolute bottom-2 left-2 w-8 h-8 rounded-lg bg-black/60 backdrop-blur-sm flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all hover:bg-primary/80"
              title="Maximize"
            >
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
              </svg>
            </button>
          </div>

          {{-- Card Body --}}
          <div class="p-4">
            <h3 class="font-display font-bold text-text-primary text-sm leading-snug line-clamp-2 group-hover:text-primary transition-colors">
              {{ $short->title }}
            </h3>
            @if($short->description)
              <p class="text-text-dim text-xs mt-1.5 line-clamp-2 leading-relaxed">{{ $short->description }}</p>
            @endif

            <div class="flex items-center justify-between mt-3 pt-3 border-t border-border-hairline">
              <span class="text-text-dim text-xs font-mono">{{ $short->creator_name ?? 'UniPlay' }}</span>
              <span class="text-text-dim text-[10px] font-mono">{{ $short->created_at?->diffForHumans() }}</span>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    {{-- Empty State --}}
    @if($shorts->isEmpty())
      <div class="text-center py-16">
        <div class="w-20 h-20 mx-auto rounded-2xl bg-surface-elevated border border-border-hairline flex items-center justify-center mb-6">
          <svg class="w-10 h-10 text-text-dim" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <h3 class="font-display text-xl font-bold text-text-primary uppercase">No Clips Available</h3>
        <p class="text-text-dim mt-2">Epic moments are being captured. Check back soon!</p>
      </div>
    @endif

    {{-- Pagination --}}
    @if(method_exists($shorts, 'links') && $shorts->hasPages())
      <div class="mt-10">
        {{ $shorts->links() }}
      </div>
    @endif
  </div>

  {{-- Fullscreen Video Modal --}}
  <div
    x-show="modalOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-cloak
    class="fixed inset-0 z-[100] bg-black/90 backdrop-blur-sm flex items-center justify-center"
    @click.self="closeShort()"
    @keydown.escape.window="closeShort()"
  >
    <div class="relative w-full max-w-2xl mx-4" x-show="modalOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
      {{-- Close button --}}
      <button
        @click="closeShort()"
        class="absolute -top-12 right-0 text-white/70 hover:text-white transition-colors z-10"
        title="Close (Esc)"
      >
        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      {{-- Video Player --}}
      <div class="rounded-2xl overflow-hidden bg-black border border-white/10 shadow-2xl">
        <template x-if="modalShort && modalShort.video_path">
          <video
            :src="getVideoSrc(modalShort.video_path)"
            class="w-full aspect-video"
            controls
            autoplay
          ></video>
        </template>
        <template x-if="modalShort && modalShort.video_url && !modalShort.video_path">
          <div class="w-full aspect-video">
            <iframe :src="modalShort.video_url" class="w-full h-full" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
          </div>
        </template>
        <template x-if="modalShort && !modalShort.video_path && !modalShort.video_url">
          <div class="w-full aspect-video flex items-center justify-center bg-surface-elevated">
            <p class="text-text-muted text-sm">No video available</p>
          </div>
        </template>
      </div>

      {{-- Info Bar --}}
      <div class="mt-3 px-1" x-show="modalShort">
        <h3 class="font-display font-bold text-white text-base" x-text="modalShort?.title"></h3>
        <p class="text-white/50 text-xs mt-1" x-text="modalShort?.description || ''" x-show="modalShort?.description"></p>
        <div class="flex items-center gap-4 mt-2">
          <span class="text-white/40 text-[10px] font-mono" x-text="modalShort?.creator_name || 'UniPlay'"></span>
          <span class="text-white/40 text-[10px] font-mono" x-text="(modalShort?.views_count ?? 0).toLocaleString() + ' views'"></span>
        </div>
      </div>
    </div>
  </div>

</section>

@push('scripts')
<script>
function shortsFilters() {
  return {
    activeCategory: 'all',
    modalOpen: false,
    modalShort: null,
    categories: [
      { key: 'all', label: 'All' },
      { key: 'clutch', label: 'Clutch' },
      { key: 'ace', label: 'Ace' },
      { key: 'mvp', label: 'MVP' },
      { key: 'teamfight', label: 'Teamfight' },
      { key: 'highlight', label: 'Highlight' },
    ],
    shortsData: @json($shortsData),
    openShort(id) {
      this.modalShort = this.shortsData.find(s => s.id === id);
      this.modalOpen = true;
      document.body.style.overflow = 'hidden';
    },
    closeShort() {
      this.modalOpen = false;
      this.modalShort = null;
      document.body.style.overflow = '';
    },
    getVideoSrc(path) {
      if (!path) return '';
      const filename = path.split('/').pop();
      return '{{ asset("storage/shorts/videos") }}/' + filename;
    }
  }
}
</script>
@endpush
@endsection
