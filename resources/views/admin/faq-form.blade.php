@extends('admin.layouts.app')

@section('admin.content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">
            {{ isset($faq) ? 'Edit FAQ' : 'Create FAQ' }}
        </h1>
        <p class="mt-1 text-text-muted text-sm">
            {{ isset($faq) ? 'Update FAQ information.' : 'Add a new frequently asked question.' }}
        </p>
    </div>

    <form action="{{ isset($faq) ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}"
          method="POST"
          class="bg-surface-card border border-border-hairline rounded-xl p-6 space-y-6">
        @csrf
        @if(isset($faq))
            @method('PUT')
        @endif

        {{-- Question --}}
        <div>
            <label for="question" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Question</label>
            <input type="text" id="question" name="question" value="{{ old('question', $faq->question ?? '') }}" required
                   class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                   placeholder="e.g. How do I join a tournament?">
            @error('question') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        {{-- Answer --}}
        <div>
            <label for="answer" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Answer</label>
            <textarea id="answer" name="answer" rows="6" required
                      class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all resize-none"
                      placeholder="Provide a detailed answer...">{{ old('answer', $faq->answer ?? '') }}</textarea>
            @error('answer') <p class="mt-1 text-xs text-primary">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4 border-t border-border-hairline">
            <a href="{{ route('admin.faqs.index') }}" class="px-4 py-2.5 text-sm font-medium text-text-muted hover:text-text-primary transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
                {{ isset($faq) ? 'Update FAQ' : 'Create FAQ' }}
            </button>
        </div>
    </form>

</div>
@endsection
