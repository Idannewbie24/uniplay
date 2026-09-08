@extends('admin.layouts.app')

@section('admin.content')
<div class="space-y-8">

    {{-- Header --}}
    <div>
        <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">Dashboard</h1>
        <p class="mt-1 text-text-muted text-sm">Overview of your platform performance.</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <div class="bg-surface-card border border-border-hairline rounded-xl p-5">
            <p class="text-text-muted text-xs font-medium uppercase tracking-wider">Games</p>
            <p class="mt-2 font-mono text-2xl font-bold text-text-primary">{{ number_format($stats['games']) }}</p>
        </div>
        <div class="bg-surface-card border border-border-hairline rounded-xl p-5">
            <p class="text-text-muted text-xs font-medium uppercase tracking-wider">Teams</p>
            <p class="mt-2 font-mono text-2xl font-bold text-text-primary">{{ number_format($stats['teams']) }}</p>
        </div>
        <div class="bg-surface-card border border-border-hairline rounded-xl p-5">
            <p class="text-text-muted text-xs font-medium uppercase tracking-wider">Matches</p>
            <p class="mt-2 font-mono text-2xl font-bold text-text-primary">{{ number_format($stats['matches']) }}</p>
        </div>
        <div class="bg-surface-card border border-border-hairline rounded-xl p-5">
            <p class="text-text-muted text-xs font-medium uppercase tracking-wider">Topup Orders</p>
            <p class="mt-2 font-mono text-2xl font-bold text-text-primary">{{ number_format($stats['topup_orders']) }}</p>
        </div>
        <div class="bg-surface-card border border-border-hairline rounded-xl p-5">
            <p class="text-text-muted text-xs font-medium uppercase tracking-wider">Ticket Orders</p>
            <p class="mt-2 font-mono text-2xl font-bold text-text-primary">{{ number_format($stats['ticket_orders']) }}</p>
        </div>
        <div class="bg-surface-card border border-border-hairline rounded-xl p-5">
            <p class="text-text-muted text-xs font-medium uppercase tracking-wider">Revenue</p>
            <p class="mt-2 font-mono text-2xl font-bold text-secondary">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

        {{-- Recent Topup Orders --}}
        <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-border-hairline">
                <h2 class="font-display text-lg font-semibold uppercase tracking-wider text-text-primary">Recent Topup Orders</h2>
                <a href="{{ route('admin.orders.topup') }}" class="text-xs text-primary hover:text-primary/80 font-medium transition-colors">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border-hairline">
                            <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">User</th>
                            <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Product</th>
                            <th class="px-5 py-3 text-right text-[10px] font-semibold uppercase tracking-widest text-text-dim">Total</th>
                            <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-hairline">
                        @forelse($recentTopupOrders as $order)
                            <tr class="hover:bg-surface-card-hover transition-colors">
                                <td class="px-5 py-3 font-medium text-text-primary">{{ $order->user->name ?? '—' }}</td>
                                <td class="px-5 py-3 text-text-muted">{{ $order->product->name ?? '—' }}</td>
                                <td class="px-5 py-3 text-right font-mono font-medium text-text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-center">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-secondary/10 text-secondary',
                                            'paid' => 'bg-green-500/10 text-green-400',
                                            'delivered' => 'bg-tertiary/10 text-tertiary',
                                            'failed' => 'bg-primary/10 text-primary',
                                        ];
                                    @endphp
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider {{ $statusColors[$order->status] ?? 'bg-surface-elevated text-text-muted' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-text-muted text-sm">No orders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Ticket Orders --}}
        <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-border-hairline">
                <h2 class="font-display text-lg font-semibold uppercase tracking-wider text-text-primary">Recent Ticket Orders</h2>
                <a href="{{ route('admin.orders.tickets') }}" class="text-xs text-primary hover:text-primary/80 font-medium transition-colors">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border-hairline">
                            <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Buyer</th>
                            <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Match</th>
                            <th class="px-5 py-3 text-right text-[10px] font-semibold uppercase tracking-widest text-text-dim">Total</th>
                            <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-hairline">
                        @forelse($recentTicketOrders as $order)
                            <tr class="hover:bg-surface-card-hover transition-colors">
                                <td class="px-5 py-3 font-medium text-text-primary">{{ $order->buyer_name ?? $order->user->name ?? '—' }}</td>
                                <td class="px-5 py-3 text-text-muted text-xs">
                                    @if($order->batch && $order->batch->match)
                                        {{ $order->batch->match->teamA->tag ?? '?' }} vs {{ $order->batch->match->teamB->tag ?? '?' }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right font-mono font-medium text-text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-center">
                                    @php
                                        $ticketStatusColors = [
                                            'pending' => 'bg-secondary/10 text-secondary',
                                            'paid' => 'bg-green-500/10 text-green-400',
                                            'checked_in' => 'bg-tertiary/10 text-tertiary',
                                            'cancelled' => 'bg-primary/10 text-primary',
                                        ];
                                    @endphp
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider {{ $ticketStatusColors[$order->status] ?? 'bg-surface-elevated text-text-muted' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-text-muted text-sm">No orders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Matches --}}
    <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-border-hairline">
            <h2 class="font-display text-lg font-semibold uppercase tracking-wider text-text-primary">Recent Matches</h2>
            <a href="{{ route('admin.matches.index') }}" class="text-xs text-primary hover:text-primary/80 font-medium transition-colors">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border-hairline">
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Tournament</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Teams</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Score</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Status</th>
                        <th class="px-5 py-3 text-right text-[10px] font-semibold uppercase tracking-widest text-text-dim">Scheduled</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-hairline">
                    @forelse($recentMatches as $match)
                        <tr class="hover:bg-surface-card-hover transition-colors">
                            <td class="px-5 py-3 text-text-muted">{{ $match->tournament->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-center">
                                <span class="font-mono font-medium text-text-primary">{{ $match->teamA->tag ?? '?' }}</span>
                                <span class="text-text-dim mx-1">vs</span>
                                <span class="font-mono font-medium text-text-primary">{{ $match->teamB->tag ?? '?' }}</span>
                            </td>
                            <td class="px-5 py-3 text-center font-mono font-bold text-text-primary">{{ $match->score_a ?? 0 }} - {{ $match->score_b ?? 0 }}</td>
                            <td class="px-5 py-3 text-center">
                                @php
                                    $matchStatusColors = [
                                        'upcoming' => 'bg-secondary/10 text-secondary',
                                        'live' => 'bg-primary/10 text-primary',
                                        'finished' => 'bg-green-500/10 text-green-400',
                                        'cancelled' => 'bg-text-dim/10 text-text-dim',
                                    ];
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider {{ $matchStatusColors[$match->status] ?? 'bg-surface-elevated text-text-muted' }}">
                                    @if($match->status === 'live')
                                        <span class="relative flex h-1.5 w-1.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-current"></span>
                                        </span>
                                    @endif
                                    {{ $match->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right font-mono text-xs text-text-muted">{{ $match->scheduled_at?->format('d M Y H:i') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-text-muted text-sm">No matches yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
