@extends('layouts.admin')

@section('title', 'New user')
@section('page_title', 'New user')

@section('breadcrumb')
    <a href="{{ route('admin.users.index') }}">Users</a>
    <i class="bi bi-chevron-right text-xs"></i>
    <span>New</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">
        <i class="bi bi-arrow-left"></i>
        <span>Back</span>
    </a>
@endsection

@php
    $types = [
        'admin'     => 'Admin',
        'volunteer' => 'Individual Volunteer',
        'intern'    => 'Intern',
        'fellow'    => 'Fellow',
        'ngo'       => 'CSO / NGO / Firm / Organization',
    ];
@endphp

@section('content')
    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">
                <div class="card p-6">
                    <h2 class="font-display text-lg text-[var(--color-ink-2)] mb-1">Profile</h2>
                    <p class="text-xs text-[var(--color-mute)] mb-5">Basic information about this user.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="label" for="fname">First name <span class="text-[var(--color-flame)]">*</span></label>
                            <input id="fname" type="text" name="fname" maxlength="100" required
                                   value="{{ old('fname') }}" class="input">
                            @error('fname') <p class="err">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label" for="lname">Last name</label>
                            <input id="lname" type="text" name="lname" maxlength="50"
                                   value="{{ old('lname') }}" class="input">
                            @error('lname') <p class="err">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label" for="username">Username <span class="text-[var(--color-flame)]">*</span></label>
                            <input id="username" type="text" name="username" maxlength="50" required
                                   value="{{ old('username') }}" class="input font-mono">
                            <p class="help">Used for sign in. Must be unique.</p>
                            @error('username') <p class="err">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label" for="email">Email <span class="text-[var(--color-flame)]">*</span></label>
                            <input id="email" type="email" name="email" maxlength="100" required
                                   value="{{ old('email') }}" class="input">
                            @error('email') <p class="err">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="label" for="bio">Bio</label>
                            <textarea id="bio" name="bio" rows="4" class="textarea"
                                      placeholder="A short paragraph about this person.">{{ old('bio') }}</textarea>
                            @error('bio') <p class="err">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="card p-6">
                    <h2 class="font-display text-lg text-[var(--color-ink-2)] mb-1">Password</h2>
                    <p class="text-xs text-[var(--color-mute)] mb-5">Minimum 8 characters.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="label" for="password">Password <span class="text-[var(--color-flame)]">*</span></label>
                            <input id="password" type="password" name="password" required minlength="8" class="input" autocomplete="new-password">
                            @error('password') <p class="err">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label" for="password_confirmation">Confirm password <span class="text-[var(--color-flame)]">*</span></label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" class="input" autocomplete="new-password">
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <div class="card p-6">
                    <h2 class="font-display text-lg text-[var(--color-ink-2)] mb-1">Access</h2>
                    <p class="text-xs text-[var(--color-mute)] mb-5">Portal type and role level.</p>

                    <div class="space-y-5">
                        <div>
                            <label class="label" for="type">Portal type <span class="text-[var(--color-flame)]">*</span></label>
                            <select id="type" name="type" required class="select">
                                @foreach ($types as $key => $label)
                                    <option value="{{ $key }}" @selected(old('type', 'volunteer') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type') <p class="err">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label" for="role">Role level <span class="text-[var(--color-flame)]">*</span></label>
                            <select id="role" name="role" required class="select">
                                <option value="1" @selected(old('role') === '1')>1 — Admin role</option>
                                <option value="2" @selected(old('role', '2') === '2')>2 — User role</option>
                            </select>
                            @error('role') <p class="err">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="card p-6">
                    <h2 class="font-display text-lg text-[var(--color-ink-2)] mb-1">Avatar</h2>
                    <p class="text-xs text-[var(--color-mute)] mb-5">Optional profile picture.</p>

                    <div class="aspect-square max-w-[200px] mx-auto bg-[var(--color-paper)] rounded-lg border border-[var(--color-line)] overflow-hidden mb-3 flex items-center justify-center">
                        <img id="avatarPreview" src="" alt="" class="w-full h-full object-cover hidden">
                        <i class="bi bi-person text-4xl text-[var(--color-mute-2)]" id="avatarPlaceholder"></i>
                    </div>
                    <input type="file" name="image" id="image" accept="image/*"
                           class="input"
                           data-image-preview="#avatarPreview">
                    <p class="help">Square image recommended.</p>
                    @error('image') <p class="err">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 mt-6">
            <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check2"></i>
                <span>Create user</span>
            </button>
        </div>
    </form>
@endsection
