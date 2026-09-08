@extends('admin.layouts.app')

@section('admin.content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">
            {{ isset($product) ? 'Edit Product' : 'Create Product' }}
        </h1>
        <p class="mt-1 text-text-muted text-sm">
            {{ isset($product) ? 'Update top-up product information.' : 'Add a new top-up product to the platform.' }}
        </p>
    </div>

    <form action="{{ isset($product) ? route('admin.topup-products.update', $product) : route('admin.topup-products.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-surface-card border border-border-hairline rounded-xl p-6 space-y-6">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif

        {{-- Game --}}
        <div>
            <label for="game_id" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Game</label>
            <select id="game_id" name="game_id" required
                    class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                <option value="">Select a game</option>
                @foreach($games as $game)
                    <option value="{{ $game->id }}" {{ old('game_id', $product->game_id ?? '') == $game->id ? 'selected' : '' }}>
                        {{ $game->name }}
                    </option>
                @endforeach
            </select>
            @error('game_id') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        {{-- Name --}}
        <div>
            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required
                   class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                   placeholder="e.g. Diamond Top-Up">
            @error('name') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Description</label>
            <textarea id="description" name="description" rows="3"
                      class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all resize-none"
                      placeholder="Product description...">{{ old('description', $product->description ?? '') }}</textarea>
            @error('description') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        {{-- Thumbnail --}}
        <div>
            <label for="thumbnail" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Thumbnail</label>
            @if(isset($product) && $product->thumbnail)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="Current thumbnail" class="h-16 w-16 rounded-xl object-cover border border-border-hairline">
                </div>
            @endif
            <input type="file" id="thumbnail" name="thumbnail" accept="image/*"
                   class="w-full text-sm text-text-muted file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 file:cursor-pointer transition-colors">
            @error('thumbnail') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Server Region --}}
            <div>
                <label for="server_region" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Server Region</label>
                <input type="text" id="server_region" name="server_region" value="{{ old('server_region', $product->server_region ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. SEA, NA, EU">
                @error('server_region') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>

            {{-- Fulfillment Method --}}
            <div>
                <label for="fulfillment_method" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Fulfillment Method</label>
                <input type="text" id="fulfillment_method" name="fulfillment_method" value="{{ old('fulfillment_method', $product->fulfillment_method ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. manual, auto">
                @error('fulfillment_method') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Support Hours --}}
            <div>
                <label for="support_hours" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Support Hours</label>
                <input type="text" id="support_hours" name="support_hours" value="{{ old('support_hours', $product->support_hours ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. 9AM - 6PM">
                @error('support_hours') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>

            {{-- Rating --}}
            <div>
                <label for="rating" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Rating</label>
                <input type="number" id="rating" name="rating" value="{{ old('rating', $product->rating ?? '') }}" min="0" max="5" step="0.1"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary font-mono placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="0.0 - 5.0">
                @error('rating') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Official Partner --}}
        <div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_official_partner" value="1"
                       {{ old('is_official_partner', $product->is_official_partner ?? false) ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-border-hairline bg-surface-elevated text-primary focus:ring-primary/30 focus:ring-offset-0 transition-colors">
                <span class="text-sm text-text-muted">Official Partner</span>
            </label>
            @error('is_official_partner') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4 border-t border-border-hairline">
            <a href="{{ route('admin.topup-products.index') }}" class="px-4 py-2.5 text-sm font-medium text-text-muted hover:text-text-primary transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
                {{ isset($product) ? 'Update Product' : 'Create Product' }}
            </button>
        </div>
    </form>

</div>
@endsection
