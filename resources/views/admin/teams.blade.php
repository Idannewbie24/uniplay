@extends('admin.layouts.app')

@section('admin.content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">Teams</h1>
            <p class="mt-1 text-text-muted text-sm">Manage all teams on the platform.</p>
        </div>
        <a href="{{ route('admin.teams.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Team
        </a>
    </div>

    <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border-hairline">
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">ID</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Logo</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Name</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Tag</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Seed Rank</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Matches (A)</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Matches (B)</th>
                        <th class="px-5 py-3 text-right text-[10px] font-semibold uppercase tracking-widest text-text-dim">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-hairline">
                    @forelse($teams as $team)
                        <tr class="hover:bg-surface-card-hover transition-colors">
                            <td class="px-5 py-3 font-mono text-text-dim">{{ $team->id }}</td>
                            <td class="px-5 py-3">
                                @if($team->logo)
                                    <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->name }}" class="h-8 w-8 rounded-full object-cover">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-primary/20 flex items-center justify-center text-primary text-xs font-bold">
                                        {{ strtoupper(substr($team->tag, 0, 2)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-3 font-medium text-text-primary">{{ $team->name }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex px-2 py-0.5 rounded bg-primary/10 text-primary text-xs font-mono font-bold">{{ $team->tag }}</span>
                            </td>
                            <td class="px-5 py-3 text-center font-mono text-text-muted">{{ $team->seed_rank ?? '—' }}</td>
                            <td class="px-5 py-3 text-center font-mono text-text-primary">{{ $team->matches_as_team_a_count }}</td>
                            <td class="px-5 py-3 text-center font-mono text-text-primary">{{ $team->matches_as_team_b_count }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.teams.edit', $team) }}"
                                       class="p-1.5 rounded-lg text-text-muted hover:text-tertiary hover:bg-tertiary/10 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.teams.destroy', $team) }}" method="POST" x-data
                                          @submit.prevent="if(confirm('Delete this team?')) $el.submit()">
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
                            <td colspan="8" class="px-5 py-12 text-center text-text-muted text-sm">No teams found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($teams->hasPages())
            <div class="px-5 py-4 border-t border-border-hairline">
                {{ $teams->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
