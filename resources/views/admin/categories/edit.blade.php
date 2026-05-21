@extends('layouts.admin')

@section('title', 'Edit category')
@section('page_title', 'Edit category')

@section('breadcrumb')
    <a href="{{ route('admin.categories.index') }}">Categories</a>
    <i class="bi bi-chevron-right text-[10px]"></i>
    <span>Edit</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost btn-sm">
        <i class="bi bi-arrow-left"></i>
        <span>Back to categories</span>
    </a>
@endsection

@section('content')
    <div class="max-w-xl">
        <div class="card p-5 lg:p-6">
            <form method="POST" action="{{ route('admin.categories.update', $category->id) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="label" for="category_name">Category name</label>
                    <input id="category_name" type="text" name="category_name" value="{{ old('category_name', $category->category_name) }}" class="input" required>
                    @error('category_name') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label" for="status">Status</label>
                    <select id="status" name="status" class="select">
                        <option value="1" @selected((string) old('status', $category->status) === '1')>Active</option>
                        <option value="0" @selected((string) old('status', $category->status) === '0')>Inactive</option>
                    </select>
                    @error('status') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3 pt-2 border-t border-[var(--color-line)]">
                    <div class="text-xs text-[var(--color-mute)] flex-1">
                        Created by <span class="font-semibold text-[var(--color-ink-2)]">{{ $category->admin_name ?: 'Admin' }}</span>
                        · {{ \Illuminate\Support\Carbon::parse($category->create_time)->format('d M Y') }}
                    </div>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost btn-sm">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-check2"></i>
                        <span>Update</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
