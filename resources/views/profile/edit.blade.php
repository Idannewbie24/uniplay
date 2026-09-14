@extends('layouts.app')

@section('title', 'UniPlay — Profile')

@section('content')
<section class="py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header: Avatar + Name --}}
        <div class="bg-surface-card border border-border-hairline rounded-2xl p-8 sm:p-10">
            <div class="flex flex-col sm:flex-row sm:items-center gap-8">

                {{-- Avatar (Google style, perfect circle) --}}
                <div class="relative mx-auto sm:mx-0 shrink-0">
                    <div class="h-32 w-32 rounded-full overflow-hidden border-4 border-border-hairline bg-primary flex items-center justify-center shadow-lg">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}?v={{ $user->updated_at?->timestamp }}"
                                 alt="{{ $user->name }}"
                                 class="h-full w-full object-cover">
                        @else
                            <span class="font-display text-5xl font-bold text-white uppercase">{{ substr($user->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <label for="avatar-upload"
                           class="absolute bottom-0 right-0 h-10 w-10 rounded-full bg-primary text-white flex items-center justify-center shadow-lg cursor-pointer hover:bg-primary/90 transition-colors border-[3px] border-surface-card"
                           title="Change profile photo">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </label>
                </div>

                <div class="text-center sm:text-left flex-1">
                    <h2 class="font-display text-3xl font-bold text-text-primary uppercase tracking-wide">{{ $user->name }}</h2>
                    <p class="text-sm text-text-muted mt-1 font-mono break-all">{{ $user->email }}</p>
                    @if($user->role === 'admin')
                        <span class="inline-flex mt-3 items-center gap-1 bg-primary/10 text-primary text-xs font-semibold px-3 py-1 rounded-full">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Administrator
                        </span>
                    @else
                        <span class="inline-flex mt-3 items-center gap-1 bg-secondary/10 text-secondary text-xs font-semibold px-3 py-1 rounded-full">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Member
                        </span>
                    @endif
                    <p class="text-xs text-text-dim mt-3 font-mono">Joined {{ $user->created_at?->format('d M Y') }}</p>
                </div>
            </div>

            {{-- Avatar upload form + status --}}
            <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" id="avatar-form" class="mt-6">
                @csrf
                @method('PATCH')
                <input type="file" id="avatar-upload" name="avatar" accept="image/*" class="hidden"
                       onchange="document.getElementById('avatar-form').submit()">
                <div class="flex flex-wrap items-center gap-3 justify-center sm:justify-start">
                    <span class="text-xs text-text-dim">Change your profile photo</span>
                </div>
                <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
            </form>

            @if(session('status') === 'avatar-updated')
                <p class="mt-4 inline-flex items-center gap-2 text-sm text-secondary bg-secondary/10 px-3 py-1.5 rounded-lg"
                   x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Profile photo updated.
                </p>
            @endif
        </div>

        {{-- Update Profile Information --}}
        <div class="bg-surface-card border border-border-hairline rounded-2xl p-8 mt-6">
            <h3 class="font-display text-lg font-bold text-text-primary uppercase tracking-wider">Account Information</h3>
            <p class="text-sm text-text-muted mt-1">Update your name and email address.</p>

            <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
                @csrf
                @method('patch')

                <div>
                    <label for="name" class="block text-text-muted text-xs font-mono uppercase tracking-wider">Name</label>
                    <input id="name" name="name" type="text" required autofocus autocomplete="name"
                           value="{{ old('name', $user->name) }}"
                           class="mt-1 block w-full px-4 py-3 rounded-xl bg-surface-elevated border border-border-hairline text-text-primary placeholder-text-dim font-mono text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-colors">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <label for="email" class="block text-text-muted text-xs font-mono uppercase tracking-wider">Email</label>
                    <input id="email" name="email" type="email" required autocomplete="username"
                           value="{{ old('email', $user->email) }}"
                           class="mt-1 block w-full px-4 py-3 rounded-xl bg-surface-elevated border border-border-hairline text-text-primary placeholder-text-dim font-mono text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-colors">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end gap-3">
                    @if(session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                           x-transition class="text-sm text-secondary">Saved.</p>
                    @endif
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-primary text-white font-display font-bold text-sm uppercase tracking-wider hover:bg-primary/90 transition-colors cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Update Password --}}
        <div class="bg-surface-card border border-border-hairline rounded-2xl p-8 mt-6">
            <h3 class="font-display text-lg font-bold text-text-primary uppercase tracking-wider">Change Password</h3>
            <p class="text-sm text-text-muted mt-1">Keep your account secure with a long, random password.</p>

            <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
                @csrf
                @method('put')

                <div>
                    <label for="update_password_current_password" class="block text-text-muted text-xs font-mono uppercase tracking-wider">Current Password</label>
                    <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                           class="mt-1 block w-full px-4 py-3 rounded-xl bg-surface-elevated border border-border-hairline text-text-primary placeholder-text-dim font-mono text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-colors">
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>

                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label for="update_password_password" class="block text-text-muted text-xs font-mono uppercase tracking-wider">New Password</label>
                        <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                               class="mt-1 block w-full px-4 py-3 rounded-xl bg-surface-elevated border border-border-hairline text-text-primary placeholder-text-dim font-mono text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-colors">
                        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                    </div>
                    <div>
                        <label for="update_password_password_confirmation" class="block text-text-muted text-xs font-mono uppercase tracking-wider">Confirm Password</label>
                        <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                               class="mt-1 block w-full px-4 py-3 rounded-xl bg-surface-elevated border border-border-hairline text-text-primary placeholder-text-dim font-mono text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition-colors">
                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    @if(session('status') === 'password-updated')
                        <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                           x-transition class="text-sm text-secondary">Saved.</p>
                    @endif
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-primary text-white font-display font-bold text-sm uppercase tracking-wider hover:bg-primary/90 transition-colors cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Update Password
                    </button>
                </div>
            </form>
        </div>

        {{-- Delete Account --}}
        <div class="bg-surface-card border border-primary/20 rounded-2xl p-8 mt-6">
            <h3 class="font-display text-lg font-bold text-text-primary uppercase tracking-wider">Danger Zone</h3>
            <p class="text-sm text-text-muted mt-1">Once your account is deleted, all of its resources and data will be permanently deleted.</p>

            <form method="post" action="{{ route('profile.destroy') }}" class="mt-6">
                @csrf
                @method('delete')

                <div>
                    <label for="password" class="block text-text-muted text-xs font-mono uppercase tracking-wider">Enter your password to confirm</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" placeholder="••••••••"
                           class="mt-1 block w-full max-w-sm px-4 py-3 rounded-xl bg-surface-elevated border border-border-hairline text-text-primary placeholder-text-dim font-mono text-sm focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-colors">
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <button type="submit"
                        class="mt-8 inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-primary/10 text-primary border border-primary/30 font-display font-bold text-sm uppercase tracking-wider hover:bg-primary hover:text-white transition-colors cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Account
                </button>
            </form>
        </div>

    </div>
</section>
@endsection