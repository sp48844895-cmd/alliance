@extends('layouts.admin')

@push('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
@endpush

@section('title', 'Edit category')
@section('breadcrumb')
    <a href="{{ route('admin.learning-cats.index') }}">Learning Categories</a>
    <i class="bi bi-chevron-right text-[10px]"></i>
    <span class="text-[var(--color-ink-2)]">Edit #{{ $category->id }}</span>
@endsection
@section('page_title', 'Edit category')

@section('topbar_actions')
    <a href="{{ route('admin.learning-cats.index') }}" class="btn btn-ghost">
        <i class="bi bi-arrow-left"></i>
        <span>Back</span>
    </a>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.learning-cats.update', $category->id) }}" class="card p-6 max-w-2xl">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="label" for="cat_name">Category name</label>
                <input type="text" name="cat_name" id="cat_name" class="input" required maxlength="255"
                       value="{{ old('cat_name', $category->cat_name) }}">
                @error('cat_name') <p class="err">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label" for="status">Status</label>
                <select name="status" id="status" class="select" required>
                    <option value="1" {{ old('status', $category->status) == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $category->status) == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status') <p class="err">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="label" for="cat_icon">Icon class</label>
                <input type="text" name="cat_icon" id="cat_icon" class="input" required maxlength="255"
                       value="{{ old('cat_icon', $category->cat_icon) }}"
                       placeholder="fa-solid fa-brain">
                <p class="help">Use FontAwesome 6 free class. Preview shown below.</p>
                @error('cat_icon') <p class="err">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <div class="rounded-lg bg-[var(--color-paper)] border border-[var(--color-line)] p-4 flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg bg-white border border-[var(--color-line)] flex items-center justify-center">
                        <i class="{{ old('cat_icon', $category->cat_icon) }} text-2xl text-[var(--color-clay-700)]"></i>
                    </div>
                    <div class="text-xs text-[var(--color-mute)]">Live icon preview</div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 mt-6">
            <a href="{{ route('admin.learning-cats.index') }}" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check2"></i>
                <span>Update category</span>
            </button>
        </div>
    </form>
@endsection
