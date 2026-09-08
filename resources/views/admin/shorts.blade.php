@extends('admin.layouts.app')

@section('admin.content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">Shorts</h1>
            <p class="mt-1 text-text-muted text-sm">Manage all short videos on the platform.</p>
        </div>
        <a href="{{ route('admin.shorts.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Short
        </a>
    </div>

    <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border-hairline">
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">ID</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Thumbnail</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Title</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Match</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Duration</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Views</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Creator</th>
                        <th class="px-5 py-3 text-right text-[10px] font-semibold uppercase tracking-widest text-text-dim">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-hairline">
                    @forelse($shorts as $short)
                        <tr class="hover:bg-surface-card-hover transition-colors">
                            <td class="px-5 py-3 font-mono text-text-dim">{{ $short->id }}</td>
                            <td class="px-5 py-3">
                                @if($short->thumbnail)
                                    <img src="{{ asset('storage/' . $short->thumbnail) }}" alt="{{ $short->title }}" class="h-8 w-14 rounded-lg object-cover">
                                @else
                                    <div class="h-8 w-14 rounded-lg bg-surface-elevated flex items-center justify-center text-text-dim text-xs">?</div>
                                @endif
                            </td>
                            <td class="px-5 py-3 font-medium text-text-primary">{{ $short->title }}</td>
                            <td class="px-5 py-3 text-text-muted text-xs">
                                @if($short->match)
                                    {{ $short->match->teamA->tag ?? '?' }} vs {{ $short->match->teamB->tag ?? '?' }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center font-mono text-text-muted">{{ $short->duration_seconds ? gmdate('i:s', $short->duration_seconds) : '—' }}</td>
                            <td class="px-5 py-3 text-center font-mono text-text-primary">{{ number_format($short->views_count ?? 0) }}</td>
                            <td class="px-5 py-3 text-text-muted text-xs">{{ $short->creator_name ?? '—' }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.shorts.edit', $short) }}"
                                       class="p-1.5 rounded-lg text-text-muted hover:text-tertiary hover:bg-tertiary/10 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.shorts.destroy', $short) }}" method="POST" x-data
                                          @submit.prevent="if(confirm('Delete this short?')) $el.submit()">
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
                            <td colspan="8" class="px-5 py-12 text-center text-text-muted text-sm">No shorts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($shorts->hasPages())
            <div class="px-5 py-4 border-t border-border-hairline">
                {{ $shorts->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
