@extends('layouts.app')

@section('content')
<section class="min-h-screen bg-surface">

  {{-- Breadcrumb --}}
  <div class="border-b border-border-hairline">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <nav class="flex items-center space-x-2 text-sm font-mono text-text-muted">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
        <span class="text-text-dim">/</span>
        <span class="text-text-primary">Match Tickets</span>
      </nav>
    </div>
  </div>

  {{-- Header --}}
  <div class="relative overflow-hidden border-b border-border-hairline">
    <div class="absolute inset-0 bg-gradient-to-br from-primary/10 via-transparent to-secondary/5"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <div class="flex items-center gap-3 mb-2">
            <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-text-primary uppercase tracking-wider">
              {{ $tournament->name ?? 'Champions Arena' }}
            </h1>
            <span class="px-3 py-1 rounded-md bg-primary text-white text-xs font-mono font-bold uppercase hidden sm:inline-block">
              Official Ticketing Hub
            </span>
          </div>
          <p class="text-text-muted text-base max-w-xl">Secure your seats for the most anticipated esports event of the season. VIP & standard passes available.</p>
        </div>
      </div>

      {{-- Info Grid --}}
      <div class="grid grid-cols-3 gap-4 mt-8 max-w-lg">
        <div class="bg-surface-elevated rounded-xl border border-border-hairline p-4 text-center">
          <span class="text-text-dim text-xs font-mono uppercase">Current Stage</span>
          <p class="text-primary font-display font-bold text-base mt-1">{{ $tournament->current_stage ?? 'Group Stage' }}</p>
        </div>
        <div class="bg-surface-elevated rounded-xl border border-border-hairline p-4 text-center">
          <span class="text-text-dim text-xs font-mono uppercase">Capacity</span>
          <p class="text-text-primary font-display font-bold text-base mt-1">{{ number_format($venue->capacity ?? 5000) }}</p>
        </div>
        <div class="bg-surface-elevated rounded-xl border border-border-hairline p-4 text-center">
          <span class="text-text-dim text-xs font-mono uppercase">Timezone</span>
          <p class="text-secondary font-display font-bold text-base mt-1">WIB (GMT+7)</p>
        </div>
      </div>
    </div>
  </div>

  {{-- Filters & Matches --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="ticketFilters()">

    {{-- Filter Tabs --}}
    <div class="flex flex-wrap gap-2 mb-4">
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

    {{-- Team Filter Chips --}}
    <div class="flex flex-wrap gap-2 mb-8">
      <button
        @click="teamFilter = 'all'"
        :class="teamFilter === 'all' ? 'bg-secondary/20 text-secondary border-secondary/40' : 'bg-surface-card text-text-dim border-border-hairline hover:border-border-elevated'"
        class="px-3 py-1.5 rounded-full border text-xs font-mono transition-all duration-200"
      >
        All Teams
      </button>
      @foreach($teams ?? [] as $team)
        <button
          @click="teamFilter = '{{ $team->slug }}'"
          :class="teamFilter === '{{ $team->slug }}' ? 'bg-secondary/20 text-secondary border-secondary/40' : 'bg-surface-card text-text-dim border-border-hairline hover:border-border-elevated'"
          class="px-3 py-1.5 rounded-full border text-xs font-mono transition-all duration-200"
        >
          {{ $team->name }}
        </button>
      @endforeach
    </div>

    {{-- Available Fixtures --}}
    <h2 class="font-display text-xl font-bold text-text-primary uppercase tracking-wide mb-6">Available Fixtures</h2>
    <div class="space-y-4">
      @foreach($matches as $match)
        <div
          class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden hover:border-border-elevated transition-all duration-300"
          x-show="activeTab === 'all' || activeTab === '{{ $match->stage ?? 'all' }}'"
          x-transition
        >
          <div class="p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">

              {{-- Status Badge --}}
              <div class="flex-shrink-0">
                @if($match->status === 'selling_fast')
                  <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary text-white text-xs font-mono font-bold uppercase animate-pulse">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                    Selling Fast
                  </span>
                @elseif($match->status === 'limited_seats')
                  <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-secondary text-black text-xs font-mono font-bold uppercase">
                    Limited Seats
                  </span>
                @else
                  <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-tertiary text-black text-xs font-mono font-bold uppercase">
                    Available
                  </span>
                @endif
              </div>

              {{-- Date & Venue --}}
              <div class="flex-shrink-0 sm:w-40">
                <p class="text-text-primary font-mono text-sm font-bold">{{ \Carbon\Carbon::parse($match->scheduled_at)->format('d M Y') }}</p>
                <p class="text-text-dim font-mono text-xs mt-0.5">{{ \Carbon\Carbon::parse($match->scheduled_at)->format('H:i') }} WIB</p>
                <p class="text-secondary text-xs font-mono mt-1">{{ $match->venue->name ?? 'Main Arena' }}</p>
              </div>

              {{-- Matchup --}}
              <div class="flex-1">
                <div class="flex items-center gap-3">
                  {{-- Team A --}}
                  <div class="flex items-center gap-2 flex-1 justify-end">
                    <div class="text-right">
                      <p class="font-display font-bold text-text-primary text-sm uppercase">{{ $match->teamA->name ?? 'TBD' }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-surface-elevated border border-border-hairline flex items-center justify-center flex-shrink-0 overflow-hidden">
                      @if($match->teamA?->logo)
                        <img src="{{ asset('storage/' . $match->teamA->logo) }}" alt="{{ $match->teamA->name }}" class="w-full h-full object-contain">
                      @else
                        <span class="font-display text-xs font-bold text-text-dim">{{ substr($match->teamA->name ?? 'T', 0, 2) }}</span>
                      @endif
                    </div>
                  </div>

                  {{-- VS / Score --}}
                  <div class="flex-shrink-0 px-3 text-center">
                    @if($match->status === 'finished' || $match->score_a !== null)
                      <div class="flex items-center gap-1">
                        <span class="font-mono font-bold text-lg text-text-primary">{{ $match->score_a ?? 0 }}</span>
                        <span class="text-text-dim text-xs">-</span>
                        <span class="font-mono font-bold text-lg text-text-primary">{{ $match->score_b ?? 0 }}</span>
                      </div>
                    @else
                      <span class="font-display text-sm font-bold text-text-dim uppercase">VS</span>
                    @endif
                  </div>

                  {{-- Team B --}}
                  <div class="flex items-center gap-2 flex-1">
                    <div class="w-10 h-10 rounded-lg bg-surface-elevated border border-border-hairline flex items-center justify-center flex-shrink-0 overflow-hidden">
                      @if($match->teamB?->logo)
                        <img src="{{ asset('storage/' . $match->teamB->logo) }}" alt="{{ $match->teamB->name }}" class="w-full h-full object-contain">
                      @else
                        <span class="font-display text-xs font-bold text-text-dim">{{ substr($match->teamB->name ?? 'T', 0, 2) }}</span>
                      @endif
                    </div>
                    <div>
                      <p class="font-display font-bold text-text-primary text-sm uppercase">{{ $match->teamB->name ?? 'TBD' }}</p>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Price Range --}}
              <div class="flex-shrink-0 text-right sm:w-32">
                <span class="text-text-dim text-[10px] font-mono uppercase">From</span>
                <p class="text-primary font-mono font-bold text-base">
                  Rp {{ number_format($match->ticketBatches->min('price') ?? 50000, 0, ',', '.') }}
                </p>
              </div>
            </div>

            {{-- Seats Progress Bar --}}
            @php
              $totalSeats = $match->ticketBatches->sum('total_seats') ?: 5000;
              $bookedSeats = $match->ticketBatches->sum('booked_seats') ?: 0;
              $remainingPct = max(5, (($totalSeats - $bookedSeats) / $totalSeats) * 100);
            @endphp
            <div class="mt-4">
              <div class="flex items-center justify-between mb-1.5">
                <span class="text-text-dim text-[10px] font-mono uppercase">Seats Remaining</span>
                <span class="text-text-muted text-[10px] font-mono">{{ number_format($totalSeats - $bookedSeats) }} / {{ number_format($totalSeats) }}</span>
              </div>
              <div class="w-full h-2 rounded-full bg-surface-elevated overflow-hidden">
                <div
                  class="h-full rounded-full transition-all duration-500"
                  :class="remainingPct > 50 ? 'bg-tertiary' : remainingPct > 20 ? 'bg-secondary' : 'bg-primary'"
                  style="width: {{ $remainingPct }}%"
                ></div>
              </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 mt-4 pt-4 border-t border-border-hairline">
              <button class="px-4 py-2 rounded-lg bg-surface-elevated border border-border-hairline text-text-muted text-xs font-mono uppercase hover:border-border-elevated hover:text-text-primary transition-colors">
                Seating Map
              </button>
              <a
                href="{{ route('tickets.show', $match) }}"
                class="px-6 py-2 rounded-lg bg-primary text-white text-xs font-display font-bold uppercase tracking-wider hover:bg-primary/90 hover:shadow-lg hover:shadow-primary/20 transition-all duration-200"
              >
                Buy Ticket
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    {{-- Empty State --}}
    @if($matches->isEmpty())
      <div class="text-center py-16">
        <div class="w-20 h-20 mx-auto rounded-2xl bg-surface-elevated border border-border-hairline flex items-center justify-center mb-6">
          <svg class="w-10 h-10 text-text-dim" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
          </svg>
        </div>
        <h3 class="font-display text-xl font-bold text-text-primary uppercase">No Matches Available</h3>
        <p class="text-text-dim mt-2">Ticket sales will open soon. Stay tuned!</p>
      </div>
    @endif

    {{-- Venue & Matchday Operations --}}
    <div class="mt-16">
      <h2 class="font-display text-2xl font-bold text-text-primary uppercase tracking-wide mb-8">Venue & Matchday Operations</h2>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Map --}}
        <div class="bg-surface-card border border-border-hairline rounded-2xl overflow-hidden">
          <div class="p-6">
            <h3 class="font-display text-lg font-bold text-text-primary uppercase">{{ $venue->name ?? 'Main Arena' }}</h3>
            <p class="text-text-dim text-sm mt-1">{{ $venue->address ?? 'Jl. Esports No. 123, Jakarta' }}</p>
          </div>
          <div class="h-64 bg-surface-elevated flex items-center justify-center border-t border-border-hairline">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126918.15056791456!2d106.8210314!3d-6.2295785!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f1498b6e7e03%3A0x2e52f14030a43e0!2sJakarta!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid"
              width="100%"
              height="100%"
              style="border:0;"
              allowfullscreen=""
              loading="lazy"
              class="grayscale opacity-60 hover:grayscale-0 hover:opacity-100 transition-all duration-500"
            ></iframe>
          </div>
        </div>

        {{-- Zone Cards --}}
        <div class="space-y-4">
          <div class="bg-surface-card border border-border-hairline rounded-xl p-5 hover:border-border-elevated transition-colors">
            <div class="flex items-center gap-3">
              <span class="w-10 h-10 rounded-xl bg-primary/10 border border-primary/30 flex items-center justify-center font-mono font-bold text-primary text-sm">A</span>
              <div>
                <h4 class="font-display font-bold text-text-primary uppercase">Zone Alpha</h4>
                <p class="text-text-dim text-xs mt-0.5">VIP Section · Front rows · Premium seating with unobstructed stage view. Includes complimentary merchandise.</p>
              </div>
            </div>
          </div>
          <div class="bg-surface-card border border-border-hairline rounded-xl p-5 hover:border-border-elevated transition-colors">
            <div class="flex items-center gap-3">
              <span class="w-10 h-10 rounded-xl bg-secondary/10 border border-secondary/30 flex items-center justify-center font-mono font-bold text-secondary text-sm">B</span>
              <div>
                <h4 class="font-display font-bold text-text-primary uppercase">Zone Bravo</h4>
                <p class="text-text-dim text-xs mt-0.5">Standard Section · Mid-tier seating with great sightlines. The best value-for-money option for fans.</p>
              </div>
            </div>
          </div>
          <div class="bg-surface-card border border-border-hairline rounded-xl p-5 hover:border-border-elevated transition-colors">
            <div class="flex items-center gap-3">
              <span class="w-10 h-10 rounded-xl bg-tertiary/10 border border-tertiary/30 flex items-center justify-center font-mono font-bold text-tertiary text-sm">C</span>
              <div>
                <h4 class="font-display font-bold text-text-primary uppercase">Zone Charlie</h4>
                <p class="text-text-dim text-xs mt-0.5">Economy Section · Elevated rear seating with full venue overview. Budget-friendly for casual attendees.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- FAQ Accordion --}}
    <div class="mt-16 mb-12">
      <h2 class="font-display text-2xl font-bold text-text-primary uppercase tracking-wide mb-8">Frequently Asked Questions</h2>

      <div class="space-y-3" x-data="{ openFaq: null }">
        @foreach($faqs as $index => $faq)
          <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden">
            <button
              @click="openFaq === {{ $index }} ? openFaq = null : openFaq = {{ $index }}"
              class="w-full flex items-center justify-between p-5 text-left hover:bg-surface-card-hover transition-colors"
            >
              <span class="font-display font-bold text-text-primary text-sm uppercase pr-4">{{ $faq['question'] ?? $faq->question }}</span>
              <svg
                class="w-5 h-5 text-text-dim flex-shrink-0 transition-transform duration-300"
                :class="openFaq === {{ $index }} ? 'rotate-180' : ''"
                fill="none" stroke="currentColor" viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
            <div
              x-show="openFaq === {{ $index }}"
              x-collapse
              x-cloak
              class="px-5 pb-5"
            >
              <p class="text-text-muted text-sm leading-relaxed">{{ $faq['answer'] ?? $faq->answer }}</p>
            </div>
          </div>
        @endforeach

        @if(!isset($faqs) || $faqs->isEmpty())
          @php
            $defaultFaqs = [
              ['question' => 'How do I receive my ticket after purchase?', 'answer' => 'After completing your payment via WhatsApp, you will receive an e-ticket via email and WhatsApp within 5 minutes. Bring the QR code on match day.'],
              ['question' => 'Can I transfer my ticket to someone else?', 'answer' => 'Yes, tickets can be transferred up to 24 hours before the match. Contact our CS team via WhatsApp with the new attendee details.'],
              ['question' => 'What payment methods are accepted?', 'answer' => 'We accept bank transfers (BCA, Mandiri, BRI, BNI), e-wallets (GoPay, OVO, Dana, ShopeePay), and credit/debit cards.'],
              ['question' => 'Is there a refund policy?', 'answer' => 'Full refunds are available up to 7 days before the event. Within 7 days, a 50% refund is offered. No refunds on match day.'],
              ['question' => 'Are there age restrictions?', 'answer' => 'All ages are welcome. Attendees under 16 must be accompanied by a parent or guardian. Valid ID is required at entry.'],
            ];
          @endphp
          @foreach($defaultFaqs as $index => $faq)
            <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden">
              <button
                @click="openFaq === {{ $index }} ? openFaq = null : openFaq = {{ $index }}"
                class="w-full flex items-center justify-between p-5 text-left hover:bg-surface-card-hover transition-colors"
              >
                <span class="font-display font-bold text-text-primary text-sm uppercase pr-4">{{ $faq['question'] }}</span>
                <svg
                  class="w-5 h-5 text-text-dim flex-shrink-0 transition-transform duration-300"
                  :class="openFaq === {{ $index }} ? 'rotate-180' : ''"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
              <div
                x-show="openFaq === {{ $index }}"
                x-collapse
                x-cloak
                class="px-5 pb-5"
              >
                <p class="text-text-muted text-sm leading-relaxed">{{ $faq['answer'] }}</p>
              </div>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
function ticketFilters() {
  return {
    activeTab: 'all',
    teamFilter: 'all',
    tabs: [
      { key: 'all', label: 'All Matches' },
      { key: 'week1', label: 'Week 1' },
      { key: 'week2', label: 'Week 2' },
      { key: 'week3', label: 'Week 3' },
      { key: 'playoffs', label: 'Playoffs Finals' },
    ]
  }
}
</script>
@endpush
@endsection