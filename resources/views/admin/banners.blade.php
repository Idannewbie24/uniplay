@extends('admin.layouts.app')

@section('admin.content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">Banners</h1>
            <p class="mt-1 text-text-muted text-sm">Manage all banners on the platform.</p>
        </div>
        <a href="{{ route('admin.banners.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Banner
        </a>
    </div>

    <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border-hairline">
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">ID</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Image</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Title</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Description</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Start Date</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">End Date</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Status</th>
                        <th class="px-5 py-3 text-right text-[10px] font-semibold uppercase tracking-widest text-text-dim">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-hairline">
                    @forelse($banners as $banner)
                        <tr class="hover:bg-surface-card-hover transition-colors">
                            <td class="px-5 py-3 font-mono text-text-dim">{{ $banner->id }}</td>
                            <td class="px-5 py-3">
                                @if($banner->image)
                                    <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="h-10 w-20 rounded-lg object-cover">
                                @else
                                    <div class="h-10 w-20 rounded-lg bg-surface-elevated flex items-center justify-center text-text-dim text-xs">?</div>
                                @endif
                            </td>
                            <td class="px-5 py-3 font-medium text-text-primary">{{ $banner->title }}</td>
                            <td class="px-5 py-3 text-text-muted text-xs max-w-[200px] truncate">{{ $banner->description ?? '—' }}</td>
                            <td class="px-5 py-3 text-text-muted text-xs">{{ $banner->start_date ? \Carbon\Carbon::parse($banner->start_date)->format('M d, Y') : '—' }}</td>
                            <td class="px-5 py-3 text-text-muted text-xs">{{ $banner->end_date ? \Carbon\Carbon::parse($banner->end_date)->format('M d, Y') : '—' }}</td>
                            <td class="px-5 py-3 text-center">
                                @if($banner->is_active)
                                    <span class="inline-flex px-2 py-0.5 rounded-full bg-green-500/10 text-green-400 text-[10px] font-semibold uppercase tracking-wider">Active</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded-full bg-text-dim/10 text-text-dim text-[10px] font-semibold uppercase tracking-wider">Inactive</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.banners.edit', $banner) }}"
                                       class="p-1.5 rounded-lg text-text-muted hover:text-tertiary hover:bg-tertiary/10 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" x-data
                                          @submit.prevent="if(confirm('Delete this banner?')) $el.submit()">
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
                            <td colspan="8" class="px-5 py-12 text-center text-text-muted text-sm">No banners found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($banners->hasPages())
            <div class="px-5 py-4 border-t border-border-hairline">
                {{ $banners->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
