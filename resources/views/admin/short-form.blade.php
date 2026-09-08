@extends('admin.layouts.app')

@section('admin.content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">
            {{ isset($short) ? 'Edit Short' : 'Create Short' }}
        </h1>
        <p class="mt-1 text-text-muted text-sm">
            {{ isset($short) ? 'Update short video information.' : 'Add a new short video to the platform.' }}
        </p>
    </div>

    <form action="{{ isset($short) ? route('admin.shorts.update', $short) : route('admin.shorts.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-surface-card border border-border-hairline rounded-xl p-6 space-y-6">
        @csrf
        @if(isset($short))
            @method('PUT')
        @endif

        {{-- Title --}}
        <div>
            <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $short->title ?? '') }}" required
                   class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                   placeholder="e.g. Ace Clutch Round 24">
            @error('title') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Description</label>
            <textarea id="description" name="description" rows="3" maxlength="1000"
                      class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all resize-none"
                      placeholder="Brief description of this short clip...">{{ old('description', $short->description ?? '') }}</textarea>
            <p class="mt-1 text-[10px] text-text-dim">Optional. Max 1000 characters.</p>
            @error('description') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        {{-- Video File Upload (admin file explorer) --}}
        <div>
            <label for="video_path" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Video File</label>
            @if(isset($short) && $short->video_path)
                <div class="mb-3 flex items-center gap-3">
                    <video src="{{ asset('storage/' . $short->video_path) }}" class="h-24 rounded-xl border border-border-hairline" controls></video>
                    <span class="text-xs text-text-muted font-mono">{{ basename($short->video_path) }}</span>
                </div>
            @endif
            <input type="file" id="video_path" name="video_path" accept="video/mp4,video/webm,video/quicktime"
                   class="w-full text-sm text-text-muted file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 file:cursor-pointer transition-colors">
            <p class="mt-1 text-[10px] text-text-dim">Upload from your computer (MP4, WebM, or MOV — max 50 MB). Leave empty if using a Video URL instead.</p>
            @error('video_path') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        {{-- Video URL (alternative to file upload) --}}
        <div>
            <label for="video_url" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Video URL (alternative)</label>
            <input type="url" id="video_url" name="video_url" value="{{ old('video_url', $short->video_url ?? '') }}"
                   class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary font-mono placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                   placeholder="https://... (or upload a file above)">
            <p class="mt-1 text-[10px] text-text-dim">External video URL. Provide this OR upload a file — at least one is required.</p>
            @error('video_url') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        {{-- Thumbnail --}}
        <div>
            <label for="thumbnail" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Thumbnail</label>
            @if(isset($short) && $short->thumbnail)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $short->thumbnail) }}" alt="Current thumbnail" class="h-24 w-40 rounded-xl object-cover border border-border-hairline">
                </div>
            @endif
            <input type="file" id="thumbnail" name="thumbnail" accept="image/*"
                   class="w-full text-sm text-text-muted file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 file:cursor-pointer transition-colors">
            @error('thumbnail') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Views Count --}}
            <div>
                <label for="views_count" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Views</label>
                <input type="number" id="views_count" name="views_count" value="{{ old('views_count', $short->views_count ?? '') }}" min="0"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary font-mono placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="0">
                @error('views_count') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Statistic Viewers --}}
        @if(isset($short) && $short->views_count > 0)
        <div class="bg-surface-elevated rounded-lg p-4 space-y-3">
            <h3 class="text-xs font-semibold uppercase tracking-wider text-text-muted">Viewer Statistics</h3>
            @php
                $daily = max(1, round($short->views_count / 30));
                $weekly = $daily * 7;
                $monthly = $daily * 30;
                $maxViews = $monthly;
            @endphp
            <div class="space-y-2">
                <div>
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="text-text-muted">Avg. Per Day</span>
                        <span class="font-mono text-text-primary">{{ number_format($daily) }}</span>
                    </div>
                    <div class="h-3 bg-surface-card rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full" style="width: {{ $maxViews > 0 ? ($daily / $maxViews * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="text-text-muted">Avg. Per Week (4 weeks)</span>
                        <span class="font-mono text-text-primary">{{ number_format($weekly) }}</span>
                    </div>
                    <div class="h-3 bg-surface-card rounded-full overflow-hidden">
                        <div class="h-full bg-secondary rounded-full" style="width: {{ $maxViews > 0 ? ($weekly / $maxViews * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="text-text-muted">Avg. Per Month</span>
                        <span class="font-mono text-text-primary">{{ number_format($monthly) }}</span>
                    </div>
                    <div class="h-3 bg-surface-card rounded-full overflow-hidden">
                        <div class="h-full bg-tertiary rounded-full" style="width: {{ $maxViews > 0 ? ($monthly / $maxViews * 100) : 0 }}%"></div>
                    </div>
                </div>
                <p class="text-[10px] text-text-dim">Total views: {{ number_format($short->views_count) }}</p>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Creator Name --}}
            <div>
                <label for="creator_name" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Creator Name</label>
                <input type="text" id="creator_name" name="creator_name" value="{{ old('creator_name', $short->creator_name ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. UniPlay Admin">
                @error('creator_name') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>

            {{-- Category Tag --}}
            <div>
                <label for="category_tag" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Category Tag</label>
                <input type="text" id="category_tag" name="category_tag" value="{{ old('category_tag', $short->category_tag ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. highlights, clips, interview">
                @error('category_tag') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Match ID --}}
        <div>
            <label for="match_id" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Match ID</label>
            <input type="number" id="match_id" name="match_id" value="{{ old('match_id', $short->match_id ?? '') }}" min="0"
                   class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary font-mono placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                   placeholder="Optional match ID">
            @error('match_id') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4 border-t border-border-hairline">
            <a href="{{ route('admin.shorts.index') }}" class="px-4 py-2.5 text-sm font-medium text-text-muted hover:text-text-primary transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
                {{ isset($short) ? 'Update Short' : 'Create Short' }}
            </button>
        </div>
    </form>

</div>
@endsection
