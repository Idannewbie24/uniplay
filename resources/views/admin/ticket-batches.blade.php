@extends('admin.layouts.app')

@section('admin.content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">Ticket Batches</h1>
            <p class="mt-1 text-text-muted text-sm">Define seating tiers (price + seat inventory) for each upcoming match.</p>
        </div>
        <a href="{{ route('admin.ticket-batches.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Batch
        </a>
    </div>

    <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border-hairline">
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">ID</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Match</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Zone</th>
                        <th class="px-5 py-3 text-right text-[10px] font-semibold uppercase tracking-widest text-text-dim">Price</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Seats</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Status</th>
                        <th class="px-5 py-3 text-right text-[10px] font-semibold uppercase tracking-widest text-text-dim">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-hairline">
                    @forelse($batches as $batch)
                        <tr class="hover:bg-surface-card-hover transition-colors">
                            <td class="px-5 py-3 font-mono text-text-dim">{{ $batch->id }}</td>
                            <td class="px-5 py-3">
                                <p class="font-medium text-text-primary text-xs">
                                    {{ $batch->match->teamA->tag ?? '?' }} vs {{ $batch->match->teamB->tag ?? '?' }}
                                </p>
                                <p class="text-text-dim text-[10px] font-mono">{{ $batch->match->tournament->name ?? '' }}</p>
                            </td>
                            <td class="px-5 py-3 text-text-muted text-xs">{{ $batch->venueZone->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-right font-mono font-semibold text-primary">Rp {{ number_format($batch->price, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-center">
                                <span class="text-text-secondary font-mono">{{ $batch->seats_remaining }} / {{ $batch->seats_total }}</span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                @php
                                    $badge = $batch->status_badge;
                                    $badgeClass = $badge === 'selling_fast' ? 'bg-primary/10 text-primary' : ($badge === 'limited_seats' ? 'bg-secondary/10 text-secondary' : 'bg-tertiary/10 text-tertiary');
                                @endphp
                                <span class="inline-flex px-2 py-0.5 rounded {{ $badgeClass }} text-[10px] font-semibold uppercase tracking-wider">{{ $badge ?? 'available' }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.ticket-batches.edit', $batch) }}"
                                       class="p-1.5 rounded-lg text-text-muted hover:text-tertiary hover:bg-tertiary/10 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.ticket-batches.destroy', $batch) }}" method="POST" x-data
                                          @submit.prevent="if(confirm('Delete this ticket batch?')) $el.submit()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-text-muted hover:text-primary hover:bg-primary/10 transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-text-muted text-sm">No ticket batches found. Create one for an upcoming match.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($batches->hasPages())
            <div class="px-5 py-4 border-t border-border-hairline">
                {{ $batches->links() }}
            </div>
        @endif
    </div>

</div>
@endsection