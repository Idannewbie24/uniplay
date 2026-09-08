@extends('admin.layouts.app')

@section('admin.content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">
            {{ isset($banner) ? 'Edit Banner' : 'Create Banner' }}
        </h1>
        <p class="mt-1 text-text-muted text-sm">
            {{ isset($banner) ? 'Update banner information.' : 'Add a new banner to the platform.' }}
        </p>
    </div>

    <form action="{{ isset($banner) ? route('admin.banners.update', $banner) : route('admin.banners.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-surface-card border border-border-hairline rounded-xl p-6 space-y-6">
        @csrf
        @if(isset($banner))
            @method('PUT')
        @endif

        {{-- Title --}}
        <div>
            <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $banner->title ?? '') }}" required
                   class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                   placeholder="e.g. Summer Tournament Banner">
            @error('title') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        {{-- Image --}}
        <div>
            <label for="image" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Image</label>
            @if(isset($banner) && $banner->image)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $banner->image) }}" alt="Current banner" class="h-32 w-full rounded-xl object-cover border border-border-hairline">
                </div>
            @endif
            <input type="file" id="image" name="image" accept="image/*"
                   class="w-full text-sm text-text-muted file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 file:cursor-pointer transition-colors">
            @error('image') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Description</label>
            <textarea id="description" name="description" rows="3" maxlength="500"
                      class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all resize-none"
                      placeholder="Short description of this banner...">{{ old('description', $banner->description ?? '') }}</textarea>
            <p class="mt-1 text-[10px] text-text-dim">Optional. Shown on hover. Max 500 characters.</p>
            @error('description') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Start Date --}}
            <div>
                <label for="start_date" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $banner->start_date ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                @error('start_date') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>

            {{-- End Date --}}
            <div>
                <label for="end_date" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $banner->end_date ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                @error('end_date') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Status --}}
        <div>
            <label for="is_active" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Status</label>
            <select id="is_active" name="is_active"
                    class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                <option value="1" {{ old('is_active', $banner->is_active ?? true) ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('is_active', $banner->is_active ?? true) ? '' : 'selected' }}>Inactive</option>
            </select>
            @error('is_active') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4 border-t border-border-hairline">
            <a href="{{ route('admin.banners.index') }}" class="px-4 py-2.5 text-sm font-medium text-text-muted hover:text-text-primary transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
                {{ isset($banner) ? 'Update Banner' : 'Create Banner' }}
            </button>
        </div>
    </form>

</div>
@endsection
