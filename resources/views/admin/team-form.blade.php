@extends('admin.layouts.app')

@section('admin.content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">
            {{ isset($team) ? 'Edit Team' : 'Create Team' }}
        </h1>
        <p class="mt-1 text-text-muted text-sm">
            {{ isset($team) ? 'Update team information.' : 'Add a new team to the platform.' }}
        </p>
    </div>

    <form action="{{ isset($team) ? route('admin.teams.update', $team) : route('admin.teams.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-surface-card border border-border-hairline rounded-xl p-6 space-y-6">
        @csrf
        @if(isset($team))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Name --}}
            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $team->name ?? '') }}" required
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. Sentinels">
                @error('name') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>

            {{-- Tag --}}
            <div>
                <label for="tag" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Tag</label>
                <input type="text" id="tag" name="tag" value="{{ old('tag', $team->tag ?? '') }}" required
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary font-mono placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. SEN">
                @error('tag') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Logo --}}
            <div>
                <label for="logo" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Logo</label>
                @if(isset($team) && $team->logo)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $team->logo) }}" alt="Current logo" class="h-16 w-16 rounded-xl object-cover border border-border-hairline">
                    </div>
                @endif
                <input type="file" id="logo" name="logo" accept="image/*"
                       class="w-full text-sm text-text-muted file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 file:cursor-pointer transition-colors">
                @error('logo') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>

            {{-- Seed Rank --}}
            <div>
                <label for="seed_rank" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Seed Rank</label>
                <input type="number" id="seed_rank" name="seed_rank" value="{{ old('seed_rank', $team->seed_rank ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. 1">
                @error('seed_rank') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4 border-t border-border-hairline">
            <a href="{{ route('admin.teams.index') }}" class="px-4 py-2.5 text-sm font-medium text-text-muted hover:text-text-primary transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
                {{ isset($team) ? 'Update Team' : 'Create Team' }}
            </button>
        </div>
    </form>

</div>
@endsection