@extends('admin.layouts.app')

@section('admin.content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">
            {{ isset($venue) ? 'Edit Venue' : 'Create Venue' }}
        </h1>
        <p class="mt-1 text-text-muted text-sm">
            {{ isset($venue) ? 'Update venue information.' : 'Add a new venue to the platform.' }}
        </p>
    </div>

    <form action="{{ isset($venue) ? route('admin.venues.update', $venue) : route('admin.venues.store') }}"
          method="POST"
          class="bg-surface-card border border-border-hairline rounded-xl p-6 space-y-6">
        @csrf
        @if(isset($venue))
            @method('PUT')
        @endif

        {{-- Name --}}
        <div>
            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $venue->name ?? '') }}" required
                   class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                   placeholder="e.g. LA Convention Center">
            @error('name') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        {{-- Address --}}
        <div>
            <label for="address" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Address</label>
            <input type="text" id="address" name="address" value="{{ old('address', $venue->address ?? '') }}"
                   class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                   placeholder="e.g. 1201 S Figueroa St">
            @error('address') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- City --}}
            <div>
                <label for="city" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">City</label>
                <input type="text" id="city" name="city" value="{{ old('city', $venue->city ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. Los Angeles">
                @error('city') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>

            {{-- Capacity --}}
            <div>
                <label for="capacity" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Capacity</label>
                <input type="number" id="capacity" name="capacity" value="{{ old('capacity', $venue->capacity ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. 5000">
                @error('capacity') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Map Embed URL --}}
        <div>
            <label for="map_embed_url" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Map Embed URL</label>
            <input type="url" id="map_embed_url" name="map_embed_url" value="{{ old('map_embed_url', $venue->map_embed_url ?? '') }}"
                   class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                   placeholder="https://maps.google.com/?embed=...">
            @error('map_embed_url') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4 border-t border-border-hairline">
            <a href="{{ route('admin.venues.index') }}" class="px-4 py-2.5 text-sm font-medium text-text-muted hover:text-text-primary transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
                {{ isset($venue) ? 'Update Venue' : 'Create Venue' }}
            </button>
        </div>
    </form>

</div>
@endsection