@extends('admin.layouts.app')

@section('admin.content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">
            {{ isset($content) ? 'Edit Content' : 'Create Content' }}
        </h1>
        <p class="mt-1 text-text-muted text-sm">
            {{ isset($content) ? 'Update this site content entry.' : 'Add a rulebook entry or legal document.' }}
        </p>
    </div>

    <form action="{{ isset($content) ? route('admin.contents.update', $content) : route('admin.contents.store') }}"
          method="POST"
          class="bg-surface-card border border-border-hairline rounded-xl p-6 space-y-6">
        @csrf
        @if(isset($content))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="sm:col-span-1">
                <label for="type" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Type</label>
                <select id="type" name="type" required
                        class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                    <option value="rule" {{ old('type', $content->type ?? '') === 'rule' ? 'selected' : '' }}>Rulebook entry</option>
                    <option value="legal" {{ old('type', $content->type ?? '') === 'legal' ? 'selected' : '' }}>Legal document</option>
                </select>
                @error('type') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
            <div class="sm:col-span-2">
                <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $content->title ?? '') }}" required
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. Draft & Tournament Format">
                @error('title') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="sm:col-span-2">
                <label for="slug" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Slug</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $content->slug ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary font-mono placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="auto-generated from title (privacy-policy, terms-of-service, anti-cheat-policy...)">
                @error('slug') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="order_index" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Order Index</label>
                <input type="number" id="order_index" name="order_index" min="0" value="{{ old('order_index', $content->order_index ?? 0) }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                @error('order_index') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="body" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Body</label>
            <textarea id="body" name="body" rows="10" required
                      class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all resize-y"
                      placeholder="Full text content. Line breaks are preserved.">{{ old('body', $content->body ?? '') }}</textarea>
            @error('body') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-border-hairline">
            <a href="{{ route('admin.contents.index') }}" class="px-4 py-2.5 text-sm font-medium text-text-muted hover:text-text-primary transition-colors">Cancel</a>
            <button type="submit"
                    class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
                {{ isset($content) ? 'Update Content' : 'Create Content' }}
            </button>
        </div>
    </form>

</div>
@endsection