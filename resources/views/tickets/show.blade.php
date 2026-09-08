@extends('layouts.app')

@section('content')
<section class="min-h-screen bg-surface" x-data="ticketPurchase()">

  {{-- Breadcrumb --}}
  <div class="border-b border-border-hairline">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <nav class="flex items-center space-x-2 text-sm font-mono text-text-muted">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
        <span class="text-text-dim">/</span>
        <a href="{{ route('tickets.index') }}" class="hover:text-primary transition-colors">Match Tickets</a>
        <span class="text-text-dim">/</span>
        <span class="text-text-primary">{{ $match->teamA->name ?? 'TBD' }} vs {{ $match->teamB->name ?? 'TBD' }}</span>
      </nav>
    </div>
  </div>

  {{-- Match Header --}}
  <div class="relative border-b border-border-hairline">
    <div class="absolute inset-0 bg-gradient-to-r from-primary/8 via-transparent to-secondary/5"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative">
      <div class="flex flex-col items-center text-center">
        {{-- Tournament Name --}}
        <span class="px-3 py-1 rounded-full bg-primary/10 border border-primary/30 text-primary text-xs font-mono uppercase mb-4">
          {{ $match->tournament->name ?? 'Champions Arena 2026' }}
        </span>

        {{-- Teams --}}
        <div class="flex items-center gap-6 sm:gap-10 my-6">
          {{-- Team A --}}
          <div class="flex flex-col items-center">
            <div class="w-20 h-20 sm:w-28 sm:h-28 rounded-2xl bg-surface-elevated border border-border-hairline flex items-center justify-center overflow-hidden mb-3">
              @if($match->teamA?->logo)
                <img src="{{ asset('storage/' . $match->teamA->logo) }}" alt="{{ $match->teamA->name }}" class="w-full h-full object-contain p-2">
              @else
                <span class="font-display text-3xl font-bold text-primary">{{ substr($match->teamA->name ?? 'T', 0, 2) }}</span>
              @endif
            </div>
            <h3 class="font-display text-xl sm:text-2xl font-bold text-text-primary uppercase">{{ $match->teamA->name ?? 'TBD' }}</h3>
          </div>

          {{-- VS --}}
          <div class="flex-shrink-0">
            <span class="font-display text-3xl sm:text-4xl font-bold text-text-dim">VS</span>
          </div>

          {{-- Team B --}}
          <div class="flex flex-col items-center">
            <div class="w-20 h-20 sm:w-28 sm:h-28 rounded-2xl bg-surface-elevated border border-border-hairline flex items-center justify-center overflow-hidden mb-3">
              @if($match->teamB?->logo)
                <img src="{{ asset('storage/' . $match->teamB->logo) }}" alt="{{ $match->teamB->name }}" class="w-full h-full object-contain p-2">
              @else
                <span class="font-display text-3xl font-bold text-secondary">{{ substr($match->teamB->name ?? 'T', 0, 2) }}</span>
              @endif
            </div>
            <h3 class="font-display text-xl sm:text-2xl font-bold text-text-primary uppercase">{{ $match->teamB->name ?? 'TBD' }}</h3>
          </div>
        </div>

        {{-- Match Info --}}
        <div class="flex flex-wrap items-center justify-center gap-4 text-sm font-mono">
          <span class="flex items-center gap-1.5 text-text-muted">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            {{ \Carbon\Carbon::parse($match->scheduled_at)->format('l, d M Y') }}
          </span>
          <span class="flex items-center gap-1.5 text-text-muted">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ \Carbon\Carbon::parse($match->scheduled_at)->format('H:i') }} WIB
          </span>
          <span class="flex items-center gap-1.5 text-text-muted">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            {{ $match->venue->name ?? 'Main Arena' }}
          </span>
          @if($match->tournament?->format)
            <span class="px-2 py-0.5 rounded bg-surface-elevated border border-border-hairline text-text-dim text-xs">{{ $match->tournament->format }}</span>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Main Content --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col lg:flex-row gap-8">

      {{-- Left Column: Venue + Batches --}}
      <div class="flex-1 space-y-8">

        {{-- Venue Map --}}
        <div class="bg-surface-card border border-border-hairline rounded-2xl overflow-hidden">
          <div class="p-6">
            <h2 class="font-display text-lg font-bold text-text-primary uppercase tracking-wide">Venue</h2>
            <p class="text-text-dim text-sm mt-1">{{ $match->venue->name ?? 'Main Arena' }} · {{ $match->venue->address ?? 'Jakarta, Indonesia' }}</p>
          </div>
          <div class="h-48 bg-surface-elevated border-t border-border-hairline">
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

        {{-- Ticket Batches --}}
        <div>
          <h2 class="font-display text-xl font-bold text-text-primary uppercase tracking-wide mb-6">Select Your Tickets</h2>

          <div class="space-y-4">
            @foreach($match->ticketBatches as $batch)
              @php
                $remaining = $batch->seats_remaining;
                $remainingPct = $batch->seats_total > 0 ? max(2, ($remaining / $batch->seats_total) * 100) : 0;
                $isSoldOut = $remaining <= 0;
                $zoneName = $batch->venueZone->name ?? '';
              @endphp
              <div
                class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden transition-all duration-300 {{ $isSoldOut ? 'opacity-50' : 'hover:border-border-elevated' }}"
                @if(!$isSoldOut) :class="selectedBatch === {{ $batch->id }} ? 'border-primary shadow-lg shadow-primary/10' : ''" @endif
              >
                <div class="p-5 sm:p-6">
                  <div class="flex flex-col sm:flex-row sm:items-center gap-4">

                    {{-- Tier Info --}}
                    <div class="flex-1">
                      <div class="flex items-center gap-2 mb-1">
                        <h3 class="font-display font-bold text-text-primary uppercase">{{ $batch->tier_name }}</h3>
                        <span class="px-2 py-0.5 rounded text-[9px] font-mono uppercase
                          {{ str_contains($zoneName, 'Alpha') ? 'bg-primary/10 text-primary border border-primary/30' : '' }}
                          {{ str_contains($zoneName, 'Bravo') ? 'bg-secondary/10 text-secondary border border-secondary/30' : '' }}
                          {{ str_contains($zoneName, 'Charlie') ? 'bg-tertiary/10 text-tertiary border border-tertiary/30' : '' }}
                          {{ !str_contains($zoneName, 'Alpha') && !str_contains($zoneName, 'Bravo') && !str_contains($zoneName, 'Charlie') ? 'bg-surface-elevated text-text-dim border border-border-hairline' : '' }}
                        ">
                          {{ $zoneName }}
                        </span>
                      </div>

                      <div class="flex items-center gap-4 mt-2">
                        <div>
                          <span class="text-text-dim text-[10px] font-mono uppercase">Price per ticket</span>
                          <p class="text-primary font-mono font-bold text-lg">Rp {{ number_format($batch->price, 0, ',', '.') }}</p>
                        </div>
                        <div>
                          <span class="text-text-dim text-[10px] font-mono uppercase">Seats left</span>
                          <p class="text-text-primary font-mono font-bold text-sm">{{ $remaining }}</p>
                        </div>
                      </div>

                      {{-- Seats Progress --}}
                      <div class="mt-3 max-w-xs">
                        <div class="w-full h-1.5 rounded-full bg-surface-elevated overflow-hidden">
                          <div
                            class="h-full rounded-full transition-all duration-500 {{ $remainingPct > 50 ? 'bg-tertiary' : ($remainingPct > 20 ? 'bg-secondary' : 'bg-primary') }}"
                            style="width: {{ $remainingPct }}%"
                          ></div>
                        </div>
                      </div>
                    </div>

                    {{-- Quantity Selector --}}
                    @if(!$isSoldOut)
                      <div class="flex items-center gap-4">
                        {{-- Quantity --}}
                        <div class="flex items-center gap-0 border border-border-hairline rounded-xl overflow-hidden bg-surface-elevated">
                          <button
                            @click="if(selectedBatch === {{ $batch->id }} && quantity > 1) quantity--"
                            class="w-10 h-10 flex items-center justify-center text-text-muted hover:text-text-primary hover:bg-surface-card transition-colors"
                          >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                          </button>
                          <span
                            class="w-10 h-10 flex items-center justify-center font-mono font-bold text-text-primary text-sm border-x border-border-hairline"
                            x-text="selectedBatch === {{ $batch->id }} ? quantity : 1"
                          >1</span>
                          <button
                            @click="if(selectedBatch === {{ $batch->id }} && quantity < {{ $remaining }}) quantity++; else if(selectedBatch !== {{ $batch->id }}) { selectedBatch = {{ $batch->id }}; quantity = 1; }"
                            class="w-10 h-10 flex items-center justify-center text-text-muted hover:text-text-primary hover:bg-surface-card transition-colors"
                          >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                          </button>
                        </div>

                        {{-- Buy Button --}}
                        <button
                          @click="selectedBatch = {{ $batch->id }}; batchPrice = {{ $batch->price }}; batchRemaining = {{ $remaining }}; showPurchaseForm = true"
                          :class="selectedBatch === {{ $batch->id }} ? 'bg-primary shadow-lg shadow-primary/20' : 'bg-primary/10 border border-primary/30 text-primary hover:bg-primary hover:text-white'"
                          class="px-6 py-3 rounded-xl font-display font-bold text-sm uppercase tracking-wider transition-all duration-200"
                        >
                          Buy Now
                        </button>
                      </div>
                    @else
                      <span class="px-4 py-2 rounded-xl bg-surface-elevated border border-border-hairline text-text-dim font-mono text-xs uppercase">Sold Out</span>
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      {{-- Right Column: Purchase Form --}}
      <div class="w-full lg:w-96 flex-shrink-0">
        <div class="lg:sticky lg:top-8">
          <div class="bg-surface-card border border-border-elevated rounded-2xl p-6">
            <h3 class="font-display text-lg font-bold text-text-primary uppercase tracking-wide mb-5">Purchase Tickets</h3>

            <form action="{{ route('tickets.order') }}" method="POST" id="ticketOrderForm">
              @csrf
              <input type="hidden" name="match_id" value="{{ $match->id }}">
              <input type="hidden" name="ticket_batch_id" :value="selectedBatch">

              {{-- Buyer Name --}}
              <div class="mb-4">
                <label class="block text-text-muted text-xs font-mono uppercase mb-2">Full Name</label>
                <input
                  type="text"
                  name="buyer_name"
                  x-model="buyerName"
                  required
                  placeholder="Your full name"
                  class="w-full px-4 py-3 rounded-xl bg-surface-elevated border border-border-hairline text-text-primary placeholder-text-dim font-mono text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-colors"
                >
              </div>

              {{-- Buyer Phone --}}
              <div class="mb-4">
                <label class="block text-text-muted text-xs font-mono uppercase mb-2">Phone Number</label>
                <input
                  type="tel"
                  name="buyer_phone"
                  x-model="buyerPhone"
                  required
                  placeholder="+62 8xx-xxxx-xxxx"
                  class="w-full px-4 py-3 rounded-xl bg-surface-elevated border border-border-hairline text-text-primary placeholder-text-dim font-mono text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-colors"
                >
              </div>

              {{-- Quantity --}}
              <div class="mb-4">
                <label class="block text-text-muted text-xs font-mono uppercase mb-2">Quantity</label>
                <input type="hidden" name="quantity" :value="quantity">
                <div class="px-4 py-3 rounded-xl bg-surface-elevated border border-border-hairline text-text-primary font-mono text-sm">
                  <span x-text="quantity"></span> ticket(s)
                </div>
              </div>

              {{-- Price Breakdown --}}
              <div class="py-4 border-t border-b border-border-hairline space-y-2 mb-5">
                <div class="flex justify-between text-sm">
                  <span class="text-text-dim font-mono">Price per ticket</span>
                  <span class="text-text-primary font-mono" x-text="batchPrice ? 'Rp ' + Number(batchPrice).toLocaleString('id-ID') : '—'">—</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-text-dim font-mono">Quantity</span>
                  <span class="text-text-primary font-mono" x-text="quantity">1</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-text-dim font-mono">Service Fee</span>
                  <span class="text-text-primary font-mono" x-text="batchPrice ? 'Rp ' + Number(Math.round(batchPrice * quantity * 0.03)).toLocaleString('id-ID') : '—'">—</span>
                </div>
              </div>

              {{-- Total --}}
              <div class="flex justify-between items-center mb-6">
                <span class="text-text-muted font-display font-bold uppercase">Total</span>
                <span class="text-primary font-display text-2xl font-bold" x-text="batchPrice ? 'Rp ' + Number(Math.round(batchPrice * quantity * 1.03)).toLocaleString('id-ID') : 'Rp 0'">Rp 0</span>
              </div>

              {{-- Submit --}}
              <button
                type="submit"
                :disabled="!selectedBatch || !buyerName || !buyerPhone"
                class="w-full py-4 rounded-xl bg-primary text-white font-display font-bold text-base uppercase tracking-wider hover:bg-primary/90 hover:shadow-lg hover:shadow-primary/20 transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed"
              >
                Complete Purchase
              </button>

              <p class="text-text-dim text-[11px] font-mono text-center mt-3">
                You will receive your e-ticket via WhatsApp
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
function ticketPurchase() {
  return {
    selectedBatch: null,
    batchPrice: 0,
    batchRemaining: 0,
    quantity: 1,
    buyerName: '',
    buyerPhone: '',
    showPurchaseForm: false,
  }
}
</script>
@endpush
@endsection