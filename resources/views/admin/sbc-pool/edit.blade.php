@extends('layouts.admin')

@section('title', 'Edit SBC member')
@section('page_title', 'Edit SBC member')
@section('breadcrumb')
    <a href="{{ route('admin.sbc-pool.index') }}">SBC Resource Pool</a>
    <i class="bi bi-chevron-right text-xs"></i>
    <span class="text-[var(--color-ink-2)]">Edit #{{ $member->id }}</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.sbc-pool.index') }}" class="btn btn-ghost">
        <i class="bi bi-arrow-left"></i>
        <span>Back</span>
    </a>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.sbc-pool.update', $member->id) }}" class="card p-6 max-w-4xl" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="label" for="name">Name</label>
                <input type="text" name="name" id="name" class="input" required maxlength="255" value="{{ old('name', $member->name) }}">
                @error('name') <p class="err">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label" for="email">Email</label>
                <input type="email" name="email" id="email" class="input" maxlength="255" value="{{ old('email', $member->email) }}">
                @error('email') <p class="err">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label" for="photo">Photo</label>
                <input type="file" name="photo" id="photo" class="input" accept="image/*">
                @error('photo') <p class="err">{{ $message }}</p> @enderror
                <p class="help">Leave blank to keep existing photo.</p>
            </div>

            <div class="flex items-end gap-3">
                <x-admin-image
                    :filename="$member->photo"
                    folder="uploads/sbc-pool"
                    class="w-16 h-16 rounded-full object-contain border border-[var(--color-line)]"
                    icon="bi-person" />
                <span class="text-xs text-[var(--color-mute)]">{{ $member->photo ?: 'No photo' }}</span>
            </div>

            <div>
                <label class="label" for="sort_order">Display order</label>
                <input type="number" name="sort_order" id="sort_order" class="input" min="0" value="{{ old('sort_order', (int) $member->sort_order) }}">
                @error('sort_order') <p class="err">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label" for="status">Status</label>
                <select name="status" id="status" class="select" required>
                    <option value="1" @selected((string) old('status', (string) $member->status) === '1')>Active</option>
                    <option value="0" @selected((string) old('status', (string) $member->status) === '0')>Inactive</option>
                </select>
                @error('status') <p class="err">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2 mt-2">
                <h3 class="font-display text-lg text-[var(--color-ink-2)]">Social media (optional)</h3>
            </div>

            <div>
                <label class="label" for="facebook">Facebook</label>
                <input type="text" name="facebook" id="facebook" class="input" maxlength="500" value="{{ old('facebook', $member->facebook) }}">
                @error('facebook') <p class="err">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label" for="twitter">Twitter/X</label>
                <input type="text" name="twitter" id="twitter" class="input" maxlength="500" value="{{ old('twitter', $member->twitter) }}">
                @error('twitter') <p class="err">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label" for="linkedin">LinkedIn</label>
                <input type="text" name="linkedin" id="linkedin" class="input" maxlength="500" value="{{ old('linkedin', $member->linkedin) }}">
                @error('linkedin') <p class="err">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label" for="instagram">Instagram</label>
                <input type="text" name="instagram" id="instagram" class="input" maxlength="500" value="{{ old('instagram', $member->instagram) }}">
                @error('instagram') <p class="err">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 mt-6">
            <a href="{{ route('admin.sbc-pool.index') }}" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check2"></i>
                <span>Update member</span>
            </button>
        </div>
    </form>
@endsection
