@extends('admin.layouts.app')

@section('admin.content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">Top-Up Products</h1>
            <p class="mt-1 text-text-muted text-sm">Manage all top-up products on the platform.</p>
        </div>
        <a href="{{ route('admin.topup-products.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Product
        </a>
    </div>

    <div class="bg-surface-card border border-border-hairline rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border-hairline">
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">ID</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Thumbnail</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Name</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Game</th>
                        <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-text-dim">Regions</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Partner</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Rating</th>
                        <th class="px-5 py-3 text-center text-[10px] font-semibold uppercase tracking-widest text-text-dim">Orders</th>
                        <th class="px-5 py-3 text-right text-[10px] font-semibold uppercase tracking-widest text-text-dim">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-hairline">
                    @forelse($products as $product)
                        <tr class="hover:bg-surface-card-hover transition-colors">
                            <td class="px-5 py-3 font-mono text-text-dim">{{ $product->id }}</td>
                            <td class="px-5 py-3">
                                @if($product->thumbnail)
                                    <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="h-8 w-8 rounded-lg object-cover">
                                @else
                                    <div class="h-8 w-8 rounded-lg bg-surface-elevated flex items-center justify-center text-text-dim text-xs">?</div>
                                @endif
                            </td>
                            <td class="px-5 py-3 font-medium text-text-primary">{{ $product->name }}</td>
                            <td class="px-5 py-3 text-text-muted text-xs">{{ $product->game->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-text-muted text-xs">{{ $product->server_region ?? '—' }}</td>
                            <td class="px-5 py-3 text-center">
                                @if($product->is_official_partner)
                                    <span class="inline-flex px-2 py-0.5 rounded bg-green-500/10 text-green-400 text-[10px] font-semibold uppercase tracking-wider">Official</span>
                                @else
                                    <span class="text-text-dim">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center font-mono text-text-primary">{{ $product->rating ?? '—' }}</td>
                            <td class="px-5 py-3 text-center font-mono text-text-primary">{{ $product->orders_count }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.topup-products.edit', $product) }}"
                                       class="p-1.5 rounded-lg text-text-muted hover:text-tertiary hover:bg-tertiary/10 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.topup-products.destroy', $product) }}" method="POST" x-data
                                          @submit.prevent="if(confirm('Delete this product?')) $el.submit()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-text-muted hover:text-primary hover:bg-primary/10 transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-12 text-center text-text-muted text-sm">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="px-5 py-4 border-t border-border-hairline">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
