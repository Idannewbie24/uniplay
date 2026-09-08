@extends('admin.layouts.app')

@section('admin.content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="font-display text-3xl font-bold uppercase tracking-wider text-text-primary">Stream Settings</h1>
        <p class="mt-1 text-text-muted text-sm">Configure video and stream quality settings applied to shorts playback.</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST"
          class="bg-surface-card border border-border-hairline rounded-xl p-6 space-y-6">
        @csrf

        <div class="space-y-1">
            <h2 class="font-display text-sm font-bold uppercase tracking-wider text-tertiary">Video Quality Settings</h2>
            <p class="text-text-dim text-xs">Select the available quality options for video shorts.</p>
        </div>

        @php
            $qualities = ['144p', '240p', '360p', '480p', '720p'];
            $enabledQualities = explode(',', $settings['enabled_qualities'] ?? '144p,240p,360p,480p,720p');
        @endphp

        <div class="space-y-3">
            @foreach($qualities as $quality)
                <label class="flex items-center gap-3 px-4 py-3 bg-surface-elevated border border-border-hairline rounded-lg cursor-pointer hover:border-primary/30 transition-colors">
                    <input type="checkbox" name="enabled_qualities[]" value="{{ $quality }}"
                           {{ in_array($quality, $enabledQualities) ? 'checked' : '' }}
                           class="h-4 w-4 rounded border-border-hairline bg-surface-elevated text-primary focus:ring-primary/30 focus:ring-offset-0 transition-colors">
                    <span class="text-sm text-text-primary font-mono font-semibold">{{ $quality }}</span>
                    <span class="text-xs text-text-muted">
                        @if($quality === '144p') Ultra Low — Minimal data usage
                        @elseif($quality === '240p') Low — Good for slow connections
                        @elseif($quality === '360p') Medium — Standard quality
                        @elseif($quality === '480p') High — Good quality
                        @elseif($quality === '720p') HD — Best quality available
                        @endif
                    </span>
                </label>
            @endforeach
        </div>

        <div class="pt-4 border-t border-border-hairline space-y-1">
            <h2 class="font-display text-sm font-bold uppercase tracking-wider text-tertiary">Stream Info (Homepage)</h2>
            <p class="text-text-dim text-xs">Values shown on the featured match Stream Info panel.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="stream_language" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">Stream Language</label>
                <input type="text" id="stream_language" name="stream_language" value="{{ old('stream_language', $settings['stream_language'] ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. English">
            </div>
            <div>
                <label for="whatsapp_number" class="block text-xs font-semibold uppercase tracking-wider text-text-muted mb-1.5">WhatsApp Number</label>
                <input type="text" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '') }}"
                       class="w-full px-4 py-2.5 bg-surface-elevated border border-border-hairline rounded-lg text-sm text-text-primary font-mono placeholder-text-dim focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-all"
                       placeholder="e.g. 6281318847041">
            </div>
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-border-hairline">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 text-sm font-medium text-text-muted hover:text-text-primary transition-colors">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors cursor-pointer">Save Settings</button>
        </div>
    </form>

</div>
@endsection
