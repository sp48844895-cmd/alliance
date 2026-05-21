@extends('layouts.author')

@section('title', 'My profile')
@section('page_title', 'My profile')

@section('breadcrumb')
    <span>Profile</span>
@endsection

@php
    $hasImage = $user->image && file_exists(public_path('uploads/users/' . $user->image));
@endphp

@section('content')
    <form method="POST" action="{{ route('author.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="card p-6">
                    <h2 class="font-display text-lg text-[var(--color-ink-2)] mb-1">Profile</h2>
                    <p class="text-xs text-[var(--color-mute)] mb-5">Update your name, email and bio.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="label" for="fname">First name</label>
                            <input id="fname" type="text" name="fname" maxlength="100" required value="{{ old('fname', $user->fname) }}" class="input">
                            @error('fname') <p class="err">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label" for="lname">Last name</label>
                            <input id="lname" type="text" name="lname" maxlength="50" value="{{ old('lname', $user->lname) }}" class="input">
                            @error('lname') <p class="err">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label" for="username">Username</label>
                            <input id="username" type="text" name="username" maxlength="50" required value="{{ old('username', $user->username) }}" class="input font-mono">
                            @error('username') <p class="err">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label" for="email">Email</label>
                            <input id="email" type="email" name="email" maxlength="100" required value="{{ old('email', $user->email) }}" class="input">
                            @error('email') <p class="err">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="label" for="bio">Bio</label>
                            <textarea id="bio" name="bio" rows="4" class="textarea">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio') <p class="err">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="card p-6">
                    <h2 class="font-display text-lg text-[var(--color-ink-2)] mb-1">Change password</h2>
                    <p class="text-xs text-[var(--color-mute)] mb-5">Leave blank to keep your current password.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="label" for="password">New password</label>
                            <input id="password" type="password" name="password" minlength="8" class="input" autocomplete="new-password">
                            @error('password') <p class="err">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label" for="password_confirmation">Confirm password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" minlength="8" class="input" autocomplete="new-password">
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card p-6">
                    <h2 class="font-display text-lg text-[var(--color-ink-2)] mb-4">Photo</h2>
                    @if($hasImage)
                        <img src="{{ asset('uploads/users/' . $user->image) }}" alt="" class="w-full h-auto rounded-lg border border-[var(--color-line)] mb-3 object-cover">
                        <label class="flex items-center gap-2 text-xs text-[var(--color-mute)] mb-3">
                            <input type="checkbox" name="delete_image" value="1" class="rounded">
                            <span>Remove photo</span>
                        </label>
                    @endif
                    <label class="label" for="image">Upload photo</label>
                    <input id="image" type="file" name="image" accept="image/*" class="input">
                    @error('image') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div class="card p-4 flex gap-2 justify-end">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-check2"></i>
                        <span>Save profile</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
