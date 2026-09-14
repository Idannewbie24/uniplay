@extends('admin.layouts.app')

@section('admin.content')
<div class="max-w-4xl mx-auto space-y-6">

    <div>
        <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">
            {{ isset($ticketBatch) ? 'Edit Ticket Batch' : 'Create Ticket Batch' }}
        </h1>
        <p class="mt-1 text-text-muted text-sm">
            {{ isset($ticketBatch) ? 'Update this ticket tier.' : 'Add a seating tier (price + inventory) for an upcoming match.' }}
        </p>
    </div>

    <form action="{{ isset($ticketBatch) ? route('admin.ticket-batches.update', $ticketBatch) : route('admin.ticket-batches.store') }}"
          method="POST"
          class="bg-surface-card border border-border-hairline rounded-xl p-6 space-y-6">
        @csrf
        @if(isset($ticketBatch))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="sm:col-span-2">
                <label for="match_id" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Match</label>
                <select id="match_id" name="match_id" required
                        class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary font-mono focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                    <option value="">Select upcoming match...</option>
                    @foreach($matches as $match)
                        <option value="{{ $match->id }}" {{ old('match_id', $ticketBatch->match_id ?? '') == $match->id ? 'selected' : '' }}>
                            {{ $match->tournament->name ?? '' }} — {{ $match->teamA->tag ?? '?' }} vs {{ $match->teamB->tag ?? '?' }} ({{ $match->scheduled_at?->format('d M Y H:i') }})
                        </option>
                    @endforeach
                </select>
                @error('match_id') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="venue_zone_id" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Venue Zone</label>
                <select id="venue_zone_id" name="venue_zone_id"
                        class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                    <option value="">None</option>
                    @foreach($zones as $zone)
                        <option value="{{ $zone->id }}" {{ old('venue_zone_id', $ticketBatch->venue_zone_id ?? '') == $zone->id ? 'selected' : '' }}>
                            {{ $zone->name }} ({{ $zone->venue->name ?? '' }})
                        </option>
                    @endforeach
                </select>
                @error('venue_zone_id') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div>
                <label for="price" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Price (Rp)</label>
                <input type="number" id="price" name="price" min="0" step="1000" value="{{ old('price', $ticketBatch->price ?? '') }}" required
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. 150000">
                @error('price') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="seats_total" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Total Seats</label>
                <input type="number" id="seats_total" name="seats_total" min="1" value="{{ old('seats_total', $ticketBatch->seats_total ?? '') }}" required
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. 200">
                @error('seats_total') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="status_badge" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Status Badge</label>
                <select id="status_badge" name="status_badge"
                        class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                    <option value="available" {{ old('status_badge', $ticketBatch->status_badge ?? '') === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="limited_seats" {{ old('status_badge', $ticketBatch->status_badge ?? '') === 'limited_seats' ? 'selected' : '' }}>Limited Seats</option>
                    <option value="selling_fast" {{ old('status_badge', $ticketBatch->status_badge ?? '') === 'selling_fast' ? 'selected' : '' }}>Selling Fast</option>
                </select>
                @error('status_badge') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        @if(isset($ticketBatch) && $ticketBatch->seats_remaining != $ticketBatch->seats_total)
            <div>
                <label for="seats_remaining" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Seats Remaining (optional override)</label>
                <input type="number" id="seats_remaining" name="seats_remaining" min="0" value="{{ old('seats_remaining', $ticketBatch->seats_remaining) }}"
                       class="w-full sm:w-64 px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. 0">
                <p class="mt-1 text-text-dim text-xs">Leave the seats_remaining field to auto-sync with total seats. This tier already has {{ $ticketBatch->seats_total - $ticketBatch->seats_remaining }} sold.</p>
            </div>
        @else
            <p class="text-text-dim text-xs">Seats remaining is initialized to total seats; it decreases automatically as tickets are sold.</p>
        @endif

        <div class="flex items-center justify-between pt-4 border-t border-border-hairline">
            <a href="{{ route('admin.ticket-batches.index') }}" class="px-4 py-2.5 text-sm font-medium text-text-muted hover:text-text-primary transition-colors">Cancel</a>
            <button type="submit"
                    class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
                {{ isset($ticketBatch) ? 'Update Batch' : 'Create Batch' }}
            </button>
        </div>
    </form>

</div>
@endsection