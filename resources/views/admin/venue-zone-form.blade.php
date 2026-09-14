@extends('admin.layouts.app')

@section('admin.content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">
            {{ isset($venueZone) ? 'Edit Venue Zone' : 'Create Venue Zone' }}
        </h1>
        <p class="mt-1 text-text-muted text-sm">
            {{ isset($venueZone) ? 'Update this seating zone.' : 'Add a seating zone to a venue (e.g. Zone Alpha, Zone Bravo).' }}
        </p>
    </div>

    <form action="{{ isset($venueZone) ? route('admin.venue-zones.update', $venueZone) : route('admin.venue-zones.store') }}"
          method="POST"
          class="bg-surface-card border border-border-hairline rounded-xl p-6 space-y-6">
        @csrf
        @if(isset($venueZone))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="venue_id" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Venue</label>
                <select id="venue_id" name="venue_id" required
                        class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                    <option value="">Select venue...</option>
                    @foreach($venues as $venue)
                        <option value="{{ $venue->id }}" {{ old('venue_id', $venueZone->venue_id ?? '') == $venue->id ? 'selected' : '' }}>
                            {{ $venue->name }}
                        </option>
                    @endforeach
                </select>
                @error('venue_id') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Zone Name</label>
                <select id="name" name="name" required
                        class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                    <option value="vip" @selected((old('name', $venueZone->name ?? 'vip')) === 'vip')>VIP</option>
                    <option value="front" @selected((old('name', $venueZone->name ?? 'vip')) === 'front')>Front</option>
                    <option value="middle" @selected((old('name', $venueZone->name ?? 'vip')) === 'middle')>Middle</option>
                    <option value="upper" @selected((old('name', $venueZone->name ?? 'vip')) === 'upper')>Upper</option>
                </select>
                @error('name') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Description</label>
            <textarea id="description" name="description" rows="3"
                      class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all resize-y"
                      placeholder="e.g. VIP Section · Front rows · Premium seating with unobstructed stage view.">{{ old('description', $venueZone->description ?? '') }}</textarea>
            @error('description') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-border-hairline">
            <a href="{{ route('admin.venue-zones.index') }}" class="px-4 py-2.5 text-sm font-medium text-text-muted hover:text-text-primary transition-colors">Cancel</a>
            <button type="submit"
                    class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
                {{ isset($venueZone) ? 'Update Zone' : 'Create Zone' }}
            </button>
        </div>
    </form>

</div>
@endsection