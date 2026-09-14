@extends('admin.layouts.app')

@section('admin.content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">
            {{ isset($game) ? 'Edit Game' : 'Create Game' }}
        </h1>
        <p class="mt-1 text-text-muted text-sm">
            {{ isset($game) ? 'Update game information.' : 'Add a new game to the platform.' }}
        </p>
    </div>

    <form action="{{ isset($game) ? route('admin.games.update', $game) : route('admin.games.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-surface-card border border-border-hairline rounded-xl p-6 space-y-6">
        @csrf
        @if(isset($game))
            @method('PUT')
        @endif

        {{-- Name --}}
        <div>
            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $game->name ?? '') }}" required
                   class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                   placeholder="e.g. Valorant">
            @error('name') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        {{-- Slug --}}
        <div>
            <label for="slug" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Slug</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $game->slug ?? '') }}" required
                   class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary font-mono placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                   placeholder="e.g. valorant">
            @error('slug') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Publisher --}}
            <div>
                <label for="publisher" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Publisher</label>
                <input type="text" id="publisher" name="publisher" value="{{ old('publisher', $game->publisher ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. Riot Games">
                @error('publisher') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>

            {{-- Category --}}
            <div>
                <label for="category" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Category</label>
                <select id="category" name="category"
                        class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                    <option value="mobile" @selected((old('category', $game->category ?? 'mobile')) === 'mobile')>Mobile</option>
                    <option value="pc" @selected((old('category', $game->category ?? 'mobile')) === 'pc')>PC</option>
                    <option value="console" @selected((old('category', $game->category ?? 'mobile')) === 'console')>Console</option>
                </select>
                @error('category') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Icon --}}
        <div>
            <label for="icon" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Icon</label>
            @if(isset($game) && $game->icon)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $game->icon) }}" alt="Current icon" class="h-16 w-16 rounded-xl object-cover border border-border-hairline">
                </div>
            @endif
            <input type="file" id="icon" name="icon" accept="image/*"
                   class="w-full text-sm text-text-muted file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 file:cursor-pointer transition-colors">
            @error('icon') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        {{-- Banner --}}
        <div>
            <label for="banner" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Banner</label>
            @if(isset($game) && $game->banner)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $game->banner) }}" alt="Current banner" class="h-32 w-full rounded-xl object-cover border border-border-hairline">
                </div>
            @endif
            <input type="file" id="banner" name="banner" accept="image/*"
                   class="w-full text-sm text-text-muted file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 file:cursor-pointer transition-colors">
            @error('banner') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4 border-t border-border-hairline">
            <a href="{{ route('admin.games.index') }}" class="px-4 py-2.5 text-sm font-medium text-text-muted hover:text-text-primary transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
                {{ isset($game) ? 'Update Game' : 'Create Game' }}
            </button>
        </div>
    </form>

</div>
@endsection
