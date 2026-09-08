@extends('layouts.app')

@section('title', 'UniPlay — Redeem Code')

@section('content')
<section class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md" x-data="redeemForm()">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary/10 mb-4">
                <svg class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                </svg>
            </div>
            <h1 class="font-display text-3xl font-bold text-text-primary uppercase tracking-wider">Redeem Code</h1>
            <p class="text-text-muted text-sm mt-2">Enter your unique prize code to claim your reward.</p>
        </div>

        <div class="bg-surface-card border border-border-hairline rounded-xl p-8">
            @if(!auth()->check())
                <div class="text-center">
                    <p class="text-text-muted text-sm mb-4">Please sign in to redeem your prize code.</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
                        Sign In
                    </a>
                </div>
            @else
                <template x-if="!result">
                    <form @submit.prevent="submit()" class="space-y-4">
                        <div>
                            <label class="block text-text-muted text-xs font-mono uppercase tracking-wider mb-2">Email</label>
                            <input type="email" x-model="form.email" required
                                   class="w-full px-4 py-3 rounded-xl bg-surface-elevated border border-border-hairline text-text-primary placeholder-text-dim font-mono text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-colors"
                                   placeholder="your@email.com">
                        </div>
                        <div>
                            <label class="block text-text-muted text-xs font-mono uppercase tracking-wider mb-2">Redeem Code</label>
                            <input type="text" x-model="form.code" required maxlength="16"
                                   class="w-full px-4 py-3 rounded-xl bg-surface-elevated border border-border-hairline text-text-primary placeholder-text-dim font-mono text-sm uppercase tracking-wider focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-colors"
                                   placeholder="e.g. AB3XK7PL">
                        </div>
                        <button type="submit" :disabled="submitting"
                                class="w-full py-3 rounded-xl bg-primary text-white font-display font-bold text-sm uppercase tracking-wider hover:bg-primary/90 transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">
                            <span x-show="!submitting">Send</span>
                            <span x-show="submitting">Checking...</span>
                        </button>
                    </form>
                </template>

                <template x-if="result">
                    <div class="text-center py-4">
                        <div class="mb-3 inline-flex items-center justify-center w-14 h-14 rounded-full"
                             :class="result.status === 'won' ? 'bg-secondary/20 text-secondary' : 'bg-primary/10 text-primary'">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <template x-if="result.status === 'won'">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </template>
                                <template x-if="result.status !== 'won'">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </template>
                            </svg>
                        </div>
                        <h4 class="font-display text-lg font-bold text-text-primary uppercase" x-text="result.status === 'won' ? 'Congratulations! You\\'re the Winner!' : 'You\\'re not the Winner!'"></h4>
                        <p class="text-text-muted text-sm mt-1" x-text="result.message"></p>
                        <template x-if="result.prize">
                            <p class="mt-3 inline-block px-4 py-2 rounded-lg bg-secondary/15 border border-secondary/30 text-secondary font-display font-bold uppercase" x-text="'You won: ' + result.prize"></p>
                        </template>
                        <button @click="result = null; form.code = ''; form.email = ''"
                                class="mt-5 px-5 py-2.5 rounded-lg bg-surface-card border border-border-hairline text-text-muted hover:text-text-primary transition-colors text-sm font-medium cursor-pointer">
                            Try Another Code
                        </button>
                    </div>
                </template>
            @endif
        </div>
    </div>
</section>

@push('scripts')
<script>
function redeemForm() {
    return {
        form: { email: '{{ auth()->check() ? auth()->user()->email : '' }}', code: '' },
        submitting: false,
        result: null,
        async submit() {
            if (this.submitting) return;
            this.submitting = true;
            try {
                const res = await fetch('{{ route("prize.claim") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ code: this.form.code })
                });
                this.result = await res.json();
            } catch (e) {
                this.result = { status: 'error', heading: 'Error', message: 'Something went wrong. Please try again.' };
            } finally {
                this.submitting = false;
            }
        }
    };
}
</script>
@endpush
@endsection
