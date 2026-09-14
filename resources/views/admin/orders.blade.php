@extends('admin.layouts.app')

@section('admin.content')
<div class="space-y-6">

    <div>
        <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">Orders</h1>
        <p class="mt-1 text-text-muted text-sm">Manage topup and ticket orders.</p>
    </div>

    {{-- Topup Orders --}}
    <div>
        <h2 class="font-display text-lg font-bold uppercase tracking-wider text-text-primary mb-4">Topup Orders</h2>
        @if($topupOrders)
            {{-- Filter Bar --}}
            <form method="GET" action="{{ route('admin.orders.topup') }}" class="mb-4">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="relative flex-1 min-w-[200px] max-w-sm">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by IGN, user ID, name..."
                               class="w-full pl-10 pr-4 py-2.5 bg-surface-card border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                    </div>
                    <select name="status"
                            class="px-3 py-2.5 bg-surface-card border border-border-hairline rounded-lg text-sm text-text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                    <button type="submit" class="px-4 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.orders.topup') }}" class="px-4 py-2.5 text-sm text-text-muted hover:text-text-primary transition-colors">
                            Clear
                        </a>
                    @endif
                </div>
            </form>

            <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-border-hairline">
                                <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">ID</th>
                                <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">User</th>
                                <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Product</th>
                                <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">IGN</th>
                                <th class="px-5 py-3 text-right text-[10px] font-semibold uppercase tracking-widest text-text-dim">Subtotal</th>
                                <th class="px-5 py-3 text-right text-[10px] font-semibold uppercase tracking-widest text-text-dim">Fee</th>
                                <th class="px-5 py-3 text-right text-[10px] font-semibold uppercase tracking-widest text-text-dim">Total</th>
                                <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Status</th>
                                <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-hairline">
                            @forelse($topupOrders as $order)
                                <tr class="hover:bg-surface-card-hover transition-colors">
                                    <td class="px-5 py-3 font-mono text-text-dim">{{ $order->id }}</td>
                                    <td class="px-5 py-3">
                                        <span class="font-medium text-text-primary">{{ $order->user->name ?? '—' }}</span>
                                        <span class="block text-text-dim text-xs">{{ $order->user->email ?? '' }}</span>
                                    </td>
                                    <td class="px-5 py-3 text-text-muted text-xs">{{ $order->product->name ?? '—' }}</td>
                                    <td class="px-5 py-3 font-mono text-text-muted text-xs">{{ $order->game_ign ?? '—' }}</td>
                                    <td class="px-5 py-3 text-right font-mono text-text-muted text-xs">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                    <td class="px-5 py-3 text-right font-mono text-text-muted text-xs">Rp {{ number_format($order->fee, 0, ',', '.') }}</td>
                                    <td class="px-5 py-3 text-right font-mono font-semibold text-text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                    <td class="px-5 py-3 text-center">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-secondary/10 text-secondary',
                                                'paid' => 'bg-green-500/10 text-green-400',
                                                'delivered' => 'bg-tertiary/10 text-tertiary',
                                                'failed' => 'bg-primary/10 text-primary',
                                            ];
                                        @endphp
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider {{ $statusColors[$order->status] ?? '' }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3">
                                        <form action="{{ route('admin.orders.topup.status', $order) }}" method="POST" class="flex items-center gap-1">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status"
                                                    class="px-2 py-1 bg-surface-elevated border border-border-hairline rounded text-[11px] text-text-primary focus:outline-none focus:border-primary transition-all">
                                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                <option value="failed" {{ $order->status === 'failed' ? 'selected' : '' }}>Failed</option>
                                            </select>
                                            <button type="submit" class="p-1 rounded text-text-muted hover:text-secondary transition-colors" title="Save">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-5 py-12 text-center text-text-muted text-sm">No topup orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($topupOrders->hasPages())
                    <div class="px-5 py-4 border-t border-border-hairline">
                        {{ $topupOrders->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- Ticket Orders --}}
    <div>
        <h2 class="font-display text-lg font-bold uppercase tracking-wider text-text-primary mb-4">Ticket Orders</h2>
        @if($ticketOrders)
            {{-- Filter Bar --}}
            <form method="GET" action="{{ route('admin.orders.tickets') }}" class="mb-4">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="relative flex-1 min-w-[200px] max-w-sm">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-text-dim" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by buyer name, phone, token..."
                               class="w-full pl-10 pr-4 py-2.5 bg-surface-card border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                    </div>
                    <select name="status"
                            class="px-3 py-2.5 bg-surface-card border border-border-hairline rounded-lg text-sm text-text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="checked_in" {{ request('status') === 'checked_in' ? 'selected' : '' }}>Checked In</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="px-4 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.orders.tickets') }}" class="px-4 py-2.5 text-sm text-text-muted hover:text-text-primary transition-colors">
                            Clear
                        </a>
                    @endif
                </div>
            </form>

            <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-border-hairline">
                                <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">ID</th>
                                <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Buyer</th>
                                <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Phone</th>
                                <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Match</th>
                                <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Qty</th>
                                <th class="px-5 py-3 text-right text-[10px] font-semibold uppercase tracking-widest text-text-dim">Total</th>
                                <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Status</th>
                                <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-hairline">
                            @forelse($ticketOrders as $order)
                                <tr class="hover:bg-surface-card-hover transition-colors">
                                    <td class="px-5 py-3 font-mono text-text-dim">{{ $order->id }}</td>
                                    <td class="px-5 py-3">
                                        <span class="font-medium text-text-primary">{{ $order->buyer_name ?? $order->user->name ?? '—' }}</span>
                                        <span class="block text-text-dim text-xs">{{ $order->user->email ?? '' }}</span>
                                    </td>
                                    <td class="px-5 py-3 font-mono text-text-muted text-xs">{{ $order->buyer_phone ?? '—' }}</td>
                                    <td class="px-5 py-3 text-xs text-text-muted">
                                        @if($order->batch && $order->batch->match)
                                            <span class="font-mono font-medium text-text-primary">{{ $order->batch->match->teamA->tag ?? '?' }}</span>
                                            <span class="text-text-dim">vs</span>
                                            <span class="font-mono font-medium text-text-primary">{{ $order->batch->match->teamB->tag ?? '?' }}</span>
                                            <span class="block text-text-dim">{{ $order->batch->venueZone->name ?? '' }}</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-center font-mono text-text-primary">{{ $order->quantity }}</td>
                                    <td class="px-5 py-3 text-right font-mono font-semibold text-text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td class="px-5 py-3 text-center">
                                        @php
                                            $ticketStatusColors = [
                                                'pending' => 'bg-secondary/10 text-secondary',
                                                'paid' => 'bg-green-500/10 text-green-400',
                                                'checked_in' => 'bg-tertiary/10 text-tertiary',
                                                'cancelled' => 'bg-primary/10 text-primary',
                                            ];
                                        @endphp
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider {{ $ticketStatusColors[$order->status] ?? '' }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3">
                                        <form action="{{ route('admin.orders.tickets.status', $order) }}" method="POST" class="flex items-center gap-1">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status"
                                                    class="px-2 py-1 bg-surface-elevated border border-border-hairline rounded text-[11px] text-text-primary focus:outline-none focus:border-primary transition-all">
                                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                                <option value="checked_in" {{ $order->status === 'checked_in' ? 'selected' : '' }}>Checked In</option>
                                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                            <button type="submit" class="p-1 rounded text-text-muted hover:text-secondary transition-colors" title="Save">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-12 text-center text-text-muted text-sm">No ticket orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($ticketOrders->hasPages())
                    <div class="px-5 py-4 border-t border-border-hairline">
                        {{ $ticketOrders->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>

</div>
@endsection
