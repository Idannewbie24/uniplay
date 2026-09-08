@extends('admin.layouts.app')

@section('admin.content')
<div class="space-y-6" x-data="{ scoreModal: null }">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">Matches</h1>
            <p class="mt-1 text-text-muted text-sm">Manage matches and update live scores.</p>
        </div>
        <a href="{{ route('admin.matches.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Match
        </a>
    </div>

    <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border-hairline">
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">ID</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Tournament</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Team A</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Score</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Team B</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Map</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Status</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Featured</th>
                        <th class="px-5 py-3 text-right text-[10px] font-semibold uppercase tracking-widest text-text-dim">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-hairline">
                    @forelse($matches as $match)
                        <tr class="hover:bg-surface-card-hover transition-colors">
                            <td class="px-5 py-3 font-mono text-text-dim">{{ $match->id }}</td>
                            <td class="px-5 py-3 text-text-muted text-xs">{{ $match->tournament->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-center">
                                <span class="font-mono font-semibold text-text-primary text-xs">{{ $match->teamA->tag ?? '?' }}</span>
                                <span class="block text-text-dim text-[10px]">{{ $match->teamA->name ?? '' }}</span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="font-mono text-lg font-bold text-text-primary">{{ $match->score_a ?? 0 }}</span>
                                <span class="text-text-dim mx-1">-</span>
                                <span class="font-mono text-lg font-bold text-text-primary">{{ $match->score_b ?? 0 }}</span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="font-mono font-semibold text-text-primary text-xs">{{ $match->teamB->tag ?? '?' }}</span>
                                <span class="block text-text-dim text-[10px]">{{ $match->teamB->name ?? '' }}</span>
                            </td>
                            <td class="px-5 py-3 text-center font-mono text-xs text-text-muted">{{ $match->current_map ?? '—' }}</td>
                            <td class="px-5 py-3 text-center">
                                @php
                                    $statusColors = [
                                        'upcoming' => 'bg-secondary/10 text-secondary',
                                        'live' => 'bg-primary/10 text-primary',
                                        'finished' => 'bg-green-500/10 text-green-400',
                                        'cancelled' => 'bg-text-dim/10 text-text-dim',
                                    ];
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider {{ $statusColors[$match->status] ?? '' }}">
                                    @if($match->status === 'live')
                                        <span class="relative flex h-1.5 w-1.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-current"></span>
                                        </span>
                                    @endif
                                    {{ $match->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if($match->is_featured)
                                    <svg class="h-4 w-4 text-secondary inline" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <span class="text-text-dim">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    {{-- Score Update Button --}}
                                    <button @click="scoreModal = scoreModal === {{ $match->id }} ? null : {{ $match->id }}"
                                            class="p-1.5 rounded-lg text-text-muted hover:text-secondary hover:bg-secondary/10 transition-colors"
                                            title="Update Score">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    </button>

                                    <a href="{{ route('admin.matches.edit', $match) }}"
                                       class="p-1.5 rounded-lg text-text-muted hover:text-tertiary hover:bg-tertiary/10 transition-colors"
                                       title="Edit Match">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <form action="{{ route('admin.matches.destroy', $match) }}" method="POST" x-data
                                          @submit.prevent="if(confirm('Delete this match?')) $el.submit()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-text-muted hover:text-primary hover:bg-primary/10 transition-colors" title="Delete Match">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Inline Score Update Form --}}
                        <tr x-show="scoreModal === {{ $match->id }}" x-transition x-cloak>
                            <td colspan="9" class="px-5 py-4 bg-surface-elevated">
                                <form action="{{ route('admin.matches.score', $match) }}" method="POST" class="flex flex-wrap items-end gap-4">
                                    @csrf
                                    @method('PATCH')

                                    <div class="text-center min-w-[120px]">
                                        <span class="block text-[10px] font-semibold uppercase tracking-widest text-text-dim mb-1">{{ $match->teamA->tag ?? 'Team A' }}</span>
                                        <input type="number" name="score_a" value="{{ $match->score_a ?? 0 }}" min="0" required
                                               class="w-20 px-3 py-2 bg-surface-card border border-border-hairline rounded-lg text-center font-mono text-lg font-bold text-text-primary focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary/30 transition-all">
                                    </div>

                                    <div class="flex flex-col items-center gap-1 pb-1">
                                        <span class="text-text-dim font-bold">VS</span>
                                    </div>

                                    <div class="text-center min-w-[120px]">
                                        <span class="block text-[10px] font-semibold uppercase tracking-widest text-text-dim mb-1">{{ $match->teamB->tag ?? 'Team B' }}</span>
                                        <input type="number" name="score_b" value="{{ $match->score_b ?? 0 }}" min="0" required
                                               class="w-20 px-3 py-2 bg-surface-card border border-border-hairline rounded-lg text-center font-mono text-lg font-bold text-text-primary focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary/30 transition-all">
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-semibold uppercase tracking-widest text-text-dim mb-1">Map</label>
                                        <input type="text" name="current_map" value="{{ $match->current_map ?? '' }}"
                                               placeholder="e.g. Ascent"
                                               class="w-32 px-3 py-2 bg-surface-card border border-border-hairline rounded-lg text-sm text-text-primary font-mono placeholder-text-dim focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary/30 transition-all">
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-semibold uppercase tracking-widest text-text-dim mb-1">Status</label>
                                        <select name="status"
                                                class="px-3 py-2 bg-surface-card border border-border-hairline rounded-lg text-sm text-text-primary focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary/30 transition-all">
                                            <option value="live" {{ ($match->status ?? '') === 'live' ? 'selected' : '' }}>Live</option>
                                            <option value="finished" {{ ($match->status ?? '') === 'finished' ? 'selected' : '' }}>Finished</option>
                                        </select>
                                    </div>

                                    <button type="submit"
                                            class="px-5 py-2 bg-secondary text-black rounded-lg text-sm font-bold hover:bg-secondary/90 transition-colors">
                                        Update Score
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-12 text-center text-text-muted text-sm">No matches found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($matches->hasPages())
            <div class="px-5 py-4 border-t border-border-hairline">
                {{ $matches->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
