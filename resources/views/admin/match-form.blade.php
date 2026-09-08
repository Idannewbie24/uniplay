@extends('admin.layouts.app')

@section('admin.content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">
            {{ isset($match) ? 'Edit Match' : 'Create Match' }}
        </h1>
        <p class="mt-1 text-text-muted text-sm">
            {{ isset($match) ? 'Update match information.' : 'Add a new match to the platform.' }}
        </p>
    </div>

    <form action="{{ isset($match) ? route('admin.matches.update', $match) : route('admin.matches.store') }}"
          method="POST"
          class="bg-surface-card border border-border-hairline rounded-xl p-6 space-y-6">
        @csrf
        @if(isset($match))
            @method('PUT')
        @endif

        {{-- Tournament --}}
        <div>
            <label for="tournament_id" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Tournament</label>
            <select id="tournament_id" name="tournament_id" required
                    class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                <option value="">Select a tournament</option>
                @foreach($tournaments as $tournament)
                    <option value="{{ $tournament->id }}" {{ old('tournament_id', $match->tournament_id ?? '') == $tournament->id ? 'selected' : '' }}>
                        {{ $tournament->name }}
                    </option>
                @endforeach
            </select>
            @error('tournament_id') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Team A --}}
            <div>
                <label for="team_a_id" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Team A</label>
                <select id="team_a_id" name="team_a_id" required
                        class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                    <option value="">Select team</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ old('team_a_id', $match->team_a_id ?? '') == $team->id ? 'selected' : '' }}>
                            {{ $team->name }}
                        </option>
                    @endforeach
                </select>
                @error('team_a_id') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>

            {{-- Team B --}}
            <div>
                <label for="team_b_id" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Team B</label>
                <select id="team_b_id" name="team_b_id" required
                        class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                    <option value="">Select team</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ old('team_b_id', $match->team_b_id ?? '') == $team->id ? 'selected' : '' }}>
                            {{ $team->name }}
                        </option>
                    @endforeach
                </select>
                @error('team_b_id') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Venue --}}
            <div>
                <label for="venue_id" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Venue</label>
                <select id="venue_id" name="venue_id"
                        class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                    <option value="">Select a venue (optional)</option>
                    @foreach($venues as $venue)
                        <option value="{{ $venue->id }}" {{ old('venue_id', $match->venue_id ?? '') == $venue->id ? 'selected' : '' }}>
                            {{ $venue->name }}
                        </option>
                    @endforeach
                </select>
                @error('venue_id') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Status</label>
                <select id="status" name="status"
                        class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                    <option value="">Select status</option>
                    @foreach(['upcoming', 'live', 'finished', 'cancelled'] as $status)
                        <option value="{{ $status }}" {{ old('status', $match->status ?? '') === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
                @error('status') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Scheduled At --}}
        <div>
            <label for="scheduled_at" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Scheduled At</label>
            <input type="datetime-local" id="scheduled_at" name="scheduled_at"
                   value="{{ old('scheduled_at', isset($match) && $match->scheduled_at ? $match->scheduled_at->format('Y-m-d\TH:i') : '') }}" required
                   class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
            @error('scheduled_at') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Score A --}}
            <div>
                <label for="score_a" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Score A</label>
                <input type="number" id="score_a" name="score_a" value="{{ old('score_a', $match->score_a ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="0">
                @error('score_a') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>

            {{-- Score B --}}
            <div>
                <label for="score_b" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Score B</label>
                <input type="number" id="score_b" name="score_b" value="{{ old('score_b', $match->score_b ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="0">
                @error('score_b') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Stream URL --}}
            <div>
                <label for="stream_url" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Stream URL</label>
                <input type="url" id="stream_url" name="stream_url" value="{{ old('stream_url', $match->stream_url ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="https://twitch.tv/...">
                @error('stream_url') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>

            {{-- Current Map --}}
            <div>
                <label for="current_map" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Current Map</label>
                <input type="text" id="current_map" name="current_map" value="{{ old('current_map', $match->current_map ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. Bind, Ascent">
                @error('current_map') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Is Featured --}}
        <div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1"
                       {{ old('is_featured', $match->is_featured ?? false) ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-border-hairline bg-surface-elevated text-primary focus:ring-primary/30 transition-colors">
                <span class="text-xs font-semibold uppercase tracking-wider text-text-muted">Featured Match</span>
            </label>
            @error('is_featured') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4 border-t border-border-hairline">
            <a href="{{ route('admin.matches.index') }}" class="px-4 py-2.5 text-sm font-medium text-text-muted hover:text-text-primary transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
                {{ isset($match) ? 'Update Match' : 'Create Match' }}
            </button>
        </div>
    </form>

</div>
@endsection