@extends('admin.layouts.app')

@section('admin.content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">Tournaments</h1>
            <p class="mt-1 text-text-muted text-sm">Manage all tournaments on the platform.</p>
        </div>
        <a href="{{ route('admin.tournaments.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Tournament
        </a>
    </div>

    <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border-hairline">
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">ID</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Game</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Name</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Stage</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Format</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Status</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Matches</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Dates</th>
                        <th class="px-5 py-3 text-right text-[10px] font-semibold uppercase tracking-widest text-text-dim">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-hairline">
                    @forelse($tournaments as $tournament)
                        <tr class="hover:bg-surface-card-hover transition-colors">
                            <td class="px-5 py-3 font-mono text-text-dim">{{ $tournament->id }}</td>
                            <td class="px-5 py-3 font-medium text-text-primary">{{ $tournament->game->name ?? '—' }}</td>
                            <td class="px-5 py-3 font-medium text-text-primary">{{ $tournament->name }}</td>
                            <td class="px-5 py-3 text-text-muted">{{ $tournament->stage ?? '—' }}</td>
                            <td class="px-5 py-3 text-text-muted">{{ $tournament->format ?? '—' }}</td>
                            <td class="px-5 py-3 text-center">
                                @if($tournament->status === 'ongoing')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-green-500/10 text-green-400">Ongoing</span>
                                @elseif($tournament->status === 'completed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-text-muted/10 text-text-muted">Completed</span>
                                @elseif($tournament->status === 'upcoming')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-blue-500/10 text-blue-400">Upcoming</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-yellow-500/10 text-yellow-400">{{ $tournament->status }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center font-mono text-text-primary">{{ $tournament->matches_count }}</td>
                            <td class="px-5 py-3 text-text-muted text-xs">
                                @if($tournament->start_date && $tournament->end_date)
                                    {{ $tournament->start_date->format('M d') }} — {{ $tournament->end_date->format('M d, Y') }}
                                @elseif($tournament->start_date)
                                    {{ $tournament->start_date->format('M d, Y') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.tournaments.edit', $tournament) }}"
                                       class="p-1.5 rounded-lg text-text-muted hover:text-tertiary hover:bg-tertiary/10 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.tournaments.destroy', $tournament) }}" method="POST" x-data
                                          @submit.prevent="if(confirm('Delete this tournament?')) $el.submit()">
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
                            <td colspan="9" class="px-5 py-12 text-center text-text-muted text-sm">No tournaments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tournaments->hasPages())
            <div class="px-5 py-4 border-t border-border-hairline">
                {{ $tournaments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection