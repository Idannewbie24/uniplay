@extends('admin.layouts.app')

@section('admin.content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">
            {{ isset($denomination) ? 'Edit Denomination' : 'Create Denomination' }}
        </h1>
        <p class="mt-1 text-text-muted text-sm">
            {{ isset($denomination) ? 'Update this price pack.' : 'Add a price pack to a top-up product.' }}
        </p>
    </div>

    <form action="{{ isset($denomination) ? route('admin.denominations.update', $denomination) : route('admin.denominations.store') }}"
          method="POST"
          class="bg-surface-card border border-border-hairline rounded-xl p-6 space-y-6">
        @csrf
        @if(isset($denomination))
            @method('PUT')
        @endif

        <div>
            <label for="topup_product_id" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Product</label>
            <select id="topup_product_id" name="topup_product_id" required
                    class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all">
                <option value="">Select product...</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ old('topup_product_id', $denomination->topup_product_id ?? '') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }} ({{ $product->game->name ?? '' }})
                    </option>
                @endforeach
            </select>
            @error('topup_product_id') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="label" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Label</label>
            <input type="text" id="label" name="label" value="{{ old('label', $denomination->label ?? '') }}" required
                   class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                   placeholder="e.g. 179 Diamonds">
            @error('label') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div>
                <label for="base_amount" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Base Amount</label>
                <input type="number" id="base_amount" name="base_amount" min="0" value="{{ old('base_amount', $denomination->base_amount ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. 179">
            </div>
            <div>
                <label for="bonus_amount" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Bonus Amount</label>
                <input type="number" id="bonus_amount" name="bonus_amount" min="0" value="{{ old('bonus_amount', $denomination->bonus_amount ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. 3">
            </div>
            <div>
                <label for="price" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Price (Rp)</label>
                <input type="number" id="price" name="price" min="0" step="100" value="{{ old('price', $denomination->price ?? '') }}" required
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. 45000">
                @error('price') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="badge" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Badge</label>
                <input type="text" id="badge" name="badge" value="{{ old('badge', $denomination->badge ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. BEST VALUE">
            </div>
            <div>
                <label for="type" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Type</label>
                <input type="text" id="type" name="type" value="{{ old('type', $denomination->type ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. diamonds / weekly_pass / points">
            </div>
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-border-hairline">
            <a href="{{ route('admin.denominations.index') }}" class="px-4 py-2.5 text-sm font-medium text-text-muted hover:text-text-primary transition-colors">Cancel</a>
            <button type="submit"
                    class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
                {{ isset($denomination) ? 'Update Denomination' : 'Create Denomination' }}
            </button>
        </div>
    </form>

</div>
@endsection