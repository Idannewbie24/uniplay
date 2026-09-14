@extends('layouts.app')

@section('content')
<section class="min-h-screen bg-surface" x-data="topupDetail()">

  {{-- Breadcrumb --}}
  <div class="border-b border-border-hairline">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <nav class="flex items-center space-x-2 text-sm font-mono text-text-muted">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
        <span class="text-text-dim">/</span>
        <a href="{{ route('topup.index') }}" class="hover:text-primary transition-colors">Top Up Hub</a>
        <span class="text-text-dim">/</span>
        <span class="text-text-primary">{{ $product->name }}</span>
      </nav>
    </div>
  </div>

  {{-- Product Header --}}
  <div class="relative border-b border-border-hairline">
    <div class="absolute inset-0 bg-gradient-to-r from-primary/8 via-transparent to-tertiary/5"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative">
      <div class="flex flex-col md:flex-row items-start gap-6">
        {{-- Game Image --}}
        <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-2xl bg-surface-elevated border border-border-hairline flex items-center justify-center overflow-hidden flex-shrink-0">
          @if($product->game->icon)
            <img src="{{ asset('storage/' . $product->game->icon) }}" alt="{{ $product->game->name }}" class="w-full h-full object-cover">
          @else
            <span class="font-display text-4xl font-bold text-primary">{{ substr($product->game->name, 0, 1) }}</span>
          @endif
        </div>

        <div class="flex-1">
          <div class="flex flex-wrap items-center gap-3 mb-2">
            <h1 class="font-display text-3xl sm:text-4xl font-bold text-text-primary uppercase tracking-wider">
              {{ $product->game->name }}
            </h1>
            <span class="px-3 py-1 rounded-md bg-primary text-white text-xs font-mono font-bold uppercase">
              Official Partner
            </span>
          </div>

          <p class="text-text-muted text-base max-w-xl">
            {{ $product->description ?? 'Secure and instant top-up service with official authorization. Your account is 100% safe.' }}
          </p>

          {{-- Badges --}}
          <div class="flex flex-wrap gap-3 mt-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-tertiary/10 border border-tertiary/30 text-tertiary text-xs font-mono">
              <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
              Instant Delivery
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-secondary/10 border border-secondary/30 text-secondary text-xs font-mono">
              <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
              Anti-Ban Guaranteed
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary/10 border border-primary/30 text-primary text-xs font-mono">
              <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
              Safe & Secure
            </span>
          </div>

          {{-- Star Rating --}}
          <div class="flex items-center gap-2 mt-4">
            <div class="flex items-center gap-0.5">
              @for($i = 1; $i <= 5; $i++)
                <svg class="w-4 h-4 {{ $i <= ($product->rating ?? 5) ? 'text-secondary' : 'text-text-dim' }}" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
              @endfor
            </div>
            <span class="text-text-muted text-sm font-mono">{{ $product->rating ?? '5.0' }} ({{ $product->reviews_count ?? 1247 }} reviews)</span>
          </div>
        </div>
      </div>

      {{-- Info Grid --}}
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8">
        <div class="bg-surface-elevated rounded-xl border border-border-hairline p-4 text-center">
          <span class="text-text-dim text-xs font-mono uppercase">Server Region</span>
          <p class="text-text-primary font-display font-bold text-lg mt-1">{{ $product->server_region ?? 'Global' }}</p>
        </div>
        <div class="bg-surface-elevated rounded-xl border border-border-hairline p-4 text-center">
          <span class="text-text-dim text-xs font-mono uppercase">Fulfillment</span>
          <p class="text-tertiary font-display font-bold text-lg mt-1">Automatic</p>
        </div>
        <div class="bg-surface-elevated rounded-xl border border-border-hairline p-4 text-center">
          <span class="text-text-dim text-xs font-mono uppercase">Support Hours</span>
          <p class="text-text-primary font-display font-bold text-lg mt-1">24/7 Online</p>
        </div>
        <div class="bg-surface-elevated rounded-xl border border-border-hairline p-4 text-center">
          <span class="text-text-dim text-xs font-mono uppercase">Queue Status</span>
          <p class="text-tertiary font-display font-bold text-lg mt-1 flex items-center justify-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-tertiary animate-pulse"></span>
            Low Queue
          </p>
        </div>
      </div>
    </div>
  </div>

  {{-- Main Content --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col lg:flex-row gap-8">

      {{-- Left Column: Steps --}}
      <div class="flex-1 space-y-8">

        {{-- STEP 1: Account Identification --}}
        <div class="bg-surface-card border border-border-hairline rounded-2xl p-6 sm:p-8">
          <div class="flex items-center gap-3 mb-6">
            <span class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center font-mono font-bold text-white text-sm">1</span>
            <div>
              <h2 class="font-display text-xl font-bold text-text-primary uppercase tracking-wide">Account Identification</h2>
              <p class="text-text-dim text-sm">Enter your in-game credentials</p>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-text-muted text-xs font-mono uppercase mb-2">User ID</label>
              <input
                type="text"
                x-model="form.userId"
                placeholder="e.g. 1234567890"
                class="w-full px-4 py-3 rounded-xl bg-surface-elevated border border-border-hairline text-text-primary placeholder-text-dim font-mono text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-colors"
              >
            </div>
            <div>
              <label class="block text-text-muted text-xs font-mono uppercase mb-2">Zone ID</label>
              <input
                type="text"
                x-model="form.zoneId"
                placeholder="e.g. 2345"
                class="w-full px-4 py-3 rounded-xl bg-surface-elevated border border-border-hairline text-text-primary placeholder-text-dim font-mono text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-colors"
              >
            </div>
          </div>

          <button
            @click="verifyAccount()"
            :disabled="!form.userId || !form.zoneId"
            :class="verifying ? 'opacity-75 cursor-wait' : ''"
            class="mt-4 px-6 py-3 rounded-xl bg-tertiary/10 border border-tertiary/30 text-tertiary font-display text-sm uppercase tracking-wider font-bold hover:bg-tertiary hover:text-black transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed"
          >
            <span x-show="!verifying">Verify Account</span>
            <span x-show="verifying" class="flex items-center gap-2">
              <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
              Verifying...
            </span>
          </button>

          {{-- Verified State --}}
          <div x-show="verified" x-transition class="mt-4 p-4 rounded-xl bg-tertiary/5 border border-tertiary/20">
            <div class="flex items-center gap-3">
              <span class="w-8 h-8 rounded-lg bg-tertiary/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-tertiary" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
              </span>
              <div>
                <p class="text-tertiary font-display font-bold text-sm uppercase">Verified Account</p>
                <p class="text-text-muted font-mono text-xs mt-0.5">
                  IGN: <span class="text-text-primary">xXDarkSniperXx</span> · Level 67 · Mythic Rank
                </p>
              </div>
            </div>
          </div>

          <button class="mt-3 text-secondary text-xs font-mono hover:underline">
            Where to Find ID? →
          </button>
        </div>

        {{-- STEP 2: Select Denomination --}}
        <div class="bg-surface-card border border-border-hairline rounded-2xl p-6 sm:p-8">
          <div class="flex items-center gap-3 mb-6">
            <span class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center font-mono font-bold text-white text-sm">2</span>
            <div>
              <h2 class="font-display text-xl font-bold text-text-primary uppercase tracking-wide">Select Denomination</h2>
              <p class="text-text-dim text-sm">Choose your desired amount</p>
            </div>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            @foreach($product->denominations as $denom)
              <button
                @click="selectedDenom = {{ $denom->id }}; selectedPrice = {{ $denom->price }}; selectedLabel = '{{ $denom->label }}'; selectedBonus = '{{ $denom->bonus_amount ?? 0 }}'"
                :class="selectedDenom === {{ $denom->id }}
                  ? 'border-primary bg-primary/10 shadow-lg shadow-primary/10'
                  : 'border-border-hairline hover:border-border-elevated bg-surface-elevated'"
                class="relative p-4 rounded-xl border text-left transition-all duration-200"
              >
                <p class="font-display font-bold text-text-primary text-base">{{ $denom->label }}</p>
                <div class="mt-1">
                  <span class="text-text-muted text-xs font-mono line-through">+{{ $denom->bonus_amount ?? 0 }} bonus</span>
                </div>
                <p class="text-primary font-mono font-bold text-lg mt-2">
                  Rp {{ number_format($denom->price, 0, ',', '.') }}
                </p>

                {{-- Selected indicator --}}
                <div
                  x-show="selectedDenom === {{ $denom->id }}"
                  class="absolute top-3 left-3 w-4 h-4 rounded-full bg-primary flex items-center justify-center"
                >
                  <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </div>
              </button>
            @endforeach
          </div>
        </div>

        {{-- STEP 3: Payment Method --}}
        <div class="bg-surface-card border border-border-hairline rounded-2xl p-6 sm:p-8">
          <div class="flex items-center gap-3 mb-6">
            <span class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center font-mono font-bold text-white text-sm">3</span>
            <div>
              <h2 class="font-display text-xl font-bold text-text-primary uppercase tracking-wide">Payment Method</h2>
              <p class="text-text-dim text-sm">Pay instantly via WhatsApp</p>
            </div>
          </div>

          {{-- WhatsApp Direct --}}
          <div class="bg-surface-elevated rounded-xl border border-border-hairline p-5 mb-6">
            <div class="flex items-center gap-4">
              <div class="w-14 h-14 rounded-xl bg-[#25D366]/10 border border-[#25D366]/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-[#25D366]" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
              </div>
              <div class="flex-1">
                <p class="font-display font-bold text-text-primary uppercase">WhatsApp Direct DM</p>
                <div class="flex items-center gap-2 mt-1">
                  <span class="w-2 h-2 rounded-full bg-tertiary animate-pulse"></span>
                  <span class="text-tertiary text-xs font-mono">Online</span>
                  <span class="text-text-dim text-xs font-mono">· Avg. response: ~2 min</span>
                </div>
                <p class="text-text-primary font-mono text-sm mt-1">+62 812-3456-7890</p>
              </div>
            </div>
          </div>

          {{-- 3-Step Process --}}
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-surface-elevated rounded-xl border border-border-hairline p-4 text-center">
              <span class="w-8 h-8 rounded-lg bg-secondary/10 flex items-center justify-center mx-auto font-mono font-bold text-secondary text-sm">1</span>
              <p class="font-display font-bold text-text-primary text-sm mt-3 uppercase">Send Order DM</p>
              <p class="text-text-dim text-xs mt-1">Message us your order details</p>
            </div>
            <div class="bg-surface-elevated rounded-xl border border-border-hairline p-4 text-center">
              <span class="w-8 h-8 rounded-lg bg-secondary/10 flex items-center justify-center mx-auto font-mono font-bold text-secondary text-sm">2</span>
              <p class="font-display font-bold text-text-primary text-sm mt-3 uppercase">Transfer</p>
              <p class="text-text-dim text-xs mt-1">Pay via bank or e-wallet</p>
            </div>
            <div class="bg-surface-elevated rounded-xl border border-border-hairline p-4 text-center">
              <span class="w-8 h-8 rounded-lg bg-secondary/10 flex items-center justify-center mx-auto font-mono font-bold text-secondary text-sm">3</span>
              <p class="font-display font-bold text-text-primary text-sm mt-3 uppercase">Instant Credit</p>
              <p class="text-text-dim text-xs mt-1">Currency delivered in-game</p>
            </div>
          </div>
        </div>
      </div>

      {{-- Right Column: Order Summary Sidebar --}}
      <div class="w-full lg:w-96 flex-shrink-0">
        <div class="lg:sticky lg:top-8 space-y-4">
          <div class="bg-surface-card border border-border-elevated rounded-2xl p-6">
            <h3 class="font-display text-lg font-bold text-text-primary uppercase tracking-wide mb-5">Order Summary</h3>

            {{-- Product --}}
            <div class="flex items-center gap-3 pb-4 border-b border-border-hairline">
              <div class="w-12 h-12 rounded-xl bg-surface-elevated border border-border-hairline flex items-center justify-center flex-shrink-0">
                <span class="font-display text-lg font-bold text-primary">{{ substr($product->game->name, 0, 1) }}</span>
              </div>
              <div>
                <p class="font-display font-bold text-text-primary text-sm">{{ $product->game->name }}</p>
                <p class="text-text-dim text-xs font-mono">{{ $product->name }}</p>
              </div>
            </div>

            {{-- Selected Denomination --}}
            <div class="py-4 border-b border-border-hairline space-y-2">
              <div class="flex justify-between text-sm">
                <span class="text-text-dim font-mono">Denomination</span>
                <span class="text-text-primary font-mono" x-text="selectedLabel || '—'">—</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-text-dim font-mono">Bonus</span>
                <span class="text-text-primary font-mono" x-text="selectedBonus ? ('+ ' + selectedBonus + ' bonus') : '—'">—</span>
              </div>
            </div>

            {{-- Account Info --}}
            <div class="py-4 border-b border-border-hairline space-y-2">
              <div class="flex justify-between text-sm">
                <span class="text-text-dim font-mono">User ID</span>
                <span class="text-text-primary font-mono" x-text="form.userId || '—'">—</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-text-dim font-mono">Zone ID</span>
                <span class="text-text-primary font-mono" x-text="form.zoneId || '—'">—</span>
              </div>
            </div>

            {{-- Price Breakdown --}}
            <div class="py-4 border-b border-border-hairline space-y-2">
              <div class="flex justify-between text-sm">
                <span class="text-text-dim font-mono">Subtotal</span>
                <span class="text-text-primary font-mono" x-text="selectedPrice ? 'Rp ' + Number(selectedPrice).toLocaleString('id-ID') : '—'">—</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-text-dim font-mono">Admin Fee</span>
                <span class="text-text-primary font-mono" x-text="selectedPrice ? 'Rp ' + Number(Math.round(selectedPrice * 0.02)).toLocaleString('id-ID') : '—'">—</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-tertiary font-mono">Discount</span>
                <span class="text-tertiary font-mono">- Rp 0</span>
              </div>
            </div>

            {{-- Total --}}
            <div class="pt-4 mb-6">
              <div class="flex justify-between items-center">
                <span class="text-text-muted font-display font-bold uppercase">Total</span>
                <span class="text-primary font-display text-2xl font-bold" x-text="selectedPrice ? 'Rp ' + Number(Math.round(selectedPrice * 1.02)).toLocaleString('id-ID') : 'Rp 0'">Rp 0</span>
              </div>
            </div>

            {{-- CTA --}}
            <form action="{{ route('topup.order') }}" method="POST">
              @csrf
              <input type="hidden" name="product_id" value="{{ $product->id }}">
              <input type="hidden" name="denomination_id" x-model="selectedDenom">
              <input type="hidden" name="user_id" x-model="form.userId">
              <input type="hidden" name="zone_id" x-model="form.zoneId">
              <button
                type="submit"
                :disabled="!selectedDenom || !form.userId"
                class="w-full py-4 rounded-xl bg-primary text-white font-display font-bold text-base uppercase tracking-wider hover:bg-primary/90 hover:shadow-lg hover:shadow-primary/20 transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed"
              >
                Order via WhatsApp
              </button>
            </form>

            <p class="text-text-dim text-[11px] font-mono text-center mt-3">
              You will be redirected to WhatsApp to complete payment
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
function topupDetail() {
  return {
    form: {
      userId: '',
      zoneId: '',
    },
    verifying: false,
    verified: false,
    selectedDenom: null,
    selectedPrice: null,
    selectedLabel: '',
    selectedBonus: '',
    verifyAccount() {
      this.verifying = true;
      setTimeout(() => {
        this.verifying = false;
        this.verified = true;
      }, 1500);
    }
  }
}
</script>
@endpush
@endsection