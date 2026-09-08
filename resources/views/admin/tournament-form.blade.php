@extends('admin.layouts.app')

@section('admin.content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">
            {{ isset($tournament) ? 'Edit Tournament' : 'Create Tournament' }}
        </h1>
        <p class="mt-1 text-text-muted text-sm">
            {{ isset($tournament) ? 'Update tournament information.' : 'Add a new tournament to the platform.' }}
        </p>
    </div>

    <form action="{{ isset($tournament) ? route('admin.tournaments.update', $tournament) : route('admin.tournaments.store') }}"
          method="POST"
          class="bg-surface-card border border-border-hairline rounded-xl p-6 space-y-6">
        @csrf
        @if(isset($tournament))
            @method('PUT')
        @endif

        {{-- Name --}}
        <div>
            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $tournament->name ?? '') }}" required
                   class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                   placeholder="e.g. Valorant Champions 2026">
            @error('name') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Game --}}
            <div>
                <label for="game_id" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Game</label>
                <select id="game_id" name="game_id" required
                        class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                    <option value="">Select a game</option>
                    @foreach($games as $game)
                        <option value="{{ $game->id }}" {{ old('game_id', $tournament->game_id ?? '') == $game->id ? 'selected' : '' }}>
                            {{ $game->name }}
                        </option>
                    @endforeach
                </select>
                @error('game_id') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Status</label>
                <select id="status" name="status"
                        class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                    <option value="">Select status</option>
                    @foreach(['upcoming', 'ongoing', 'completed'] as $status)
                        <option value="{{ $status }}" {{ old('status', $tournament->status ?? '') === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
                @error('status') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Stage --}}
            <div>
                <label for="stage" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Stage</label>
                <input type="text" id="stage" name="stage" value="{{ old('stage', $tournament->stage ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. Group Stage, Playoffs">
                @error('stage') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>

            {{-- Format --}}
            <div>
                <label for="format" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Format</label>
                <input type="text" id="format" name="format" value="{{ old('format', $tournament->format ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. BO3, BO5, Double Elimination">
                @error('format') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Prize Pool --}}
        <div>
            <label for="prize_pool" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Prize Pool</label>
            <input type="number" step="0.01" id="prize_pool" name="prize_pool" value="{{ old('prize_pool', $tournament->prize_pool ?? '') }}"
                   class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                   placeholder="e.g. 100000">
            @error('prize_pool') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Start Date --}}
            <div>
                <label for="start_date" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $tournament->start_date ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                @error('start_date') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>

            {{-- End Date --}}
            <div>
                <label for="end_date" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $tournament->end_date ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                @error('end_date') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4 border-t border-border-hairline">
            <a href="{{ route('admin.tournaments.index') }}" class="px-4 py-2.5 text-sm font-medium text-text-muted hover:text-text-primary transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
                {{ isset($tournament) ? 'Update Tournament' : 'Create Tournament' }}
            </button>
        </div>
    </form>

</div>
@endsection