@extends('admin.layouts.app')

@section('admin.content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">Prize Codes</h1>
            <p class="mt-1 text-text-muted text-sm">All undian codes generated from top-up & ticket orders.</p>
        </div>
        <a href="{{ route('admin.prizes.gacha') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z" />
            </svg>
            Run Gacha
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Total Codes', 'value' => $stats['total'], 'class' => 'text-text-primary'],
            ['label' => 'Pending', 'value' => $stats['pending'], 'class' => 'text-text-muted'],
            ['label' => 'Won', 'value' => $stats['won'], 'class' => 'text-secondary'],
            ['label' => 'Claimed', 'value' => $stats['claimed'], 'class' => 'text-primary'],
        ] as $stat)
            <div class="bg-surface-card border border-border-hairline rounded-xl p-4">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-text-dim">{{ $stat['label'] }}</p>
                <p class="font-display text-2xl font-bold mt-1 {{ $stat['class'] }}">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border-hairline">
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">ID</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Code</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Order</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">User</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Prize</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Status</th>
                        <th class="px-5 py-3 text-right text-[10px] font-semibold uppercase tracking-widest text-text-dim">Created</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-hairline">
                    @forelse($codes as $code)
                        <tr class="hover:bg-surface-card-hover transition-colors">
                            <td class="px-5 py-3 font-mono text-text-dim">{{ $code->id }}</td>
                            <td class="px-5 py-3 font-mono font-bold text-text-primary tracking-widest">{{ $code->code }}</td>
                            <td class="px-5 py-3 text-text-muted text-xs font-mono">#{{ $code->order_id }} ({{ $code->order_type }})</td>
                            <td class="px-5 py-3 text-text-muted text-xs">{{ $code->user->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-text-muted text-xs">{{ $code->prize ?? '—' }}</td>
                            <td class="px-5 py-3 text-center">
                                @php
                                    $statusClass = $code->status === 'won' ? 'bg-secondary/10 text-secondary' : ($code->status === 'claimed' ? 'bg-primary/10 text-primary' : 'bg-text-dim/10 text-text-dim');
                                @endphp
                                <span class="inline-flex px-2 py-0.5 rounded {{ $statusClass }} text-[10px] font-semibold uppercase tracking-wider">{{ $code->status }}</span>
                            </td>
                            <td class="px-5 py-3 text-right text-text-muted text-xs font-mono">{{ $code->created_at?->format('d M H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-text-muted text-sm">No prize codes yet. Codes are generated automatically when members place top-up or ticket orders.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($codes->hasPages())
            <div class="px-5 py-4 border-t border-border-hairline">
                {{ $codes->links() }}
            </div>
        @endif
    </div>

</div>
@endsection