@extends('admin.layouts.app')

@section('admin.content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">Prize Gacha</h1>
            <p class="mt-1 text-text-muted text-sm">Draw random winners from pending undian codes. Winners get a random prize and can then redeem it via the footer Prize Claim popup.</p>
        </div>
        <a href="{{ route('admin.prizes.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-surface-card border border-border-hairline rounded-lg text-sm font-semibold text-text-muted hover:text-text-primary transition-colors">
            Back to Codes
        </a>
    </div>

    @if(session('gacha'))
        <div class="px-4 py-3 rounded-lg bg-secondary/10 border border-secondary/20 text-secondary text-sm font-medium">
            <p class="font-semibold mb-1">Winners drawn:</p>
            <p class="font-mono text-xs break-words">{{ session('gacha') }}</p>
        </div>
    @endif

    {{-- Code Generator --}}
    <div class="bg-surface-card border border-border-hairline rounded-xl p-6">
        <h2 class="font-display text-sm font-bold uppercase tracking-wider text-primary mb-3">Generate Prize Code</h2>
        <p class="text-text-dim text-xs mb-4">Generate a random prize code that will be given to users after transactions.</p>
        <div x-data="{ generatedCode: '' }" class="space-y-3">
            <div class="flex items-center gap-2">
                <input type="text" x-model="generatedCode" readonly
                       class="flex-1 px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary font-mono tracking-widest focus:outline-none"
                       placeholder="Click generate...">
                <button type="button" @click="generatedCode = '{{ \App\Models\PrizeCode::generate(8) }}'"
                        class="px-4 py-2.5 bg-secondary text-surface rounded-lg text-sm font-semibold hover:bg-secondary/90 transition-colors cursor-pointer">
                    Generate
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Draw Form --}}
        <div class="bg-surface-card border border-border-hairline rounded-xl p-6 space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="font-display text-sm font-bold uppercase tracking-wider text-primary">Run Draw</h2>
                <span class="inline-flex px-2.5 py-1 rounded-full bg-primary/10 text-primary text-xs font-mono font-bold">{{ $pending }} pending</span>
            </div>

            <form action="{{ route('admin.prizes.gacha.run') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="winners" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Number of Winners</label>
                    <input type="number" id="winners" name="winners" min="1" max="50" value="{{ old('winners', min(5, $pending)) }}" required
                           class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                    @error('winners') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
                </div>
                <button type="submit"
                        class="w-full py-3 bg-primary text-white rounded-lg text-sm font-display font-bold uppercase tracking-wider hover:bg-primary/90 transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
                        {{ $pending === 0 ? 'disabled' : '' }}>
                    Draw Winners
                </button>
                <p class="text-text-dim text-xs">Each drawn code moves from <span class="font-mono">pending</span> to <span class="font-mono">won</span> with a random prize assigned.</p>
            </form>
        </div>

        {{-- Recent Winners --}}
        <div class="space-y-6">
            <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-border-hairline">
                    <h2 class="font-display text-sm font-bold uppercase tracking-wider text-secondary">Recent Winners</h2>
                </div>
                <div class="divide-y divide-border-hairline">
                    @forelse($winners as $winner)
                        <div class="flex items-center justify-between px-5 py-3">
                            <div>
                                <p class="font-mono font-bold text-text-primary tracking-widest text-sm">{{ $winner->code }}</p>
                                <p class="text-text-dim text-xs">{{ $winner->user->name ?? 'Guest' }} · {{ $winner->prize ?? '—' }}</p>
                            </div>
                            <span class="inline-flex px-2 py-0.5 rounded bg-secondary/10 text-secondary text-[10px] font-semibold uppercase">won</span>
                        </div>
                    @empty
                        <p class="px-5 py-8 text-center text-text-muted text-sm">No winners drawn yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-border-hairline">
                    <h2 class="font-display text-sm font-bold uppercase tracking-wider text-primary">Claimed Prizes</h2>
                </div>
                <div class="divide-y divide-border-hairline">
                    @forelse($previous as $prev)
                        <div class="flex items-center justify-between px-5 py-3">
                            <div>
                                <p class="font-mono font-bold text-text-primary tracking-widest text-sm">{{ $prev->code }}</p>
                                <p class="text-text-dim text-xs">{{ $prev->user->name ?? 'Guest' }} · {{ $prev->prize ?? '—' }}</p>
                            </div>
                            <span class="inline-flex px-2 py-0.5 rounded bg-primary/10 text-primary text-[10px] font-semibold uppercase">claimed</span>
                        </div>
                    @empty
                        <p class="px-5 py-8 text-center text-text-muted text-sm">No prizes claimed yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection