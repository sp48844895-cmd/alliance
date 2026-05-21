@extends('layouts.admin')

@section('title', 'Categories')
@section('page_title', 'Categories')

@section('breadcrumb')
    <span>Content</span>
    <i class="bi bi-chevron-right text-[10px]"></i>
    <span>Categories</span>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="card p-5">
                <h2 class="font-display text-lg text-[var(--color-ink-2)]">Add new category</h2>
                <p class="text-xs text-[var(--color-mute)] mt-1 mb-4">Give your content a clear home.</p>

                <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="label" for="category_name">Category name</label>
                        <input id="category_name" type="text" name="category_name" value="{{ old('category_name') }}" class="input" placeholder="e.g. Climate" required>
                        @error('category_name') <p class="err">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="label" for="status">Status</label>
                        <select id="status" name="status" class="select">
                            <option value="1" @selected(old('status', '1') === '1')>Active</option>
                            <option value="0" @selected(old('status') === '0')>Inactive</option>
                        </select>
                        @error('status') <p class="err">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-full">
                        <i class="bi bi-plus-lg"></i>
                        <span>Add category</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            @if($categories->isEmpty())
                <div class="card p-10 text-center">
                    <i class="bi bi-tags text-3xl text-[var(--color-mute-2)]"></i>
                    <div class="font-display text-lg mt-3">No categories yet</div>
                    <div class="text-sm text-[var(--color-mute)] mt-1">Use the form on the left to add your first category.</div>
                </div>
            @else
                <div class="card overflow-hidden">
                    <table class="table w-full table-fixed">
                        <thead>
                            <tr>
                                <th class="w-10 hidden sm:table-cell">#</th>
                                <th>Category</th>
                                <th class="w-20">Stories</th>
                                <th class="w-24">Status</th>
                                <th class="w-28 hidden md:table-cell">Created</th>
                                <th class="w-28 hidden lg:table-cell">Admin</th>
                                <th class="w-28 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $cat)
                                <tr>
                                    <td class="hidden sm:table-cell">
                                        <span class="font-mono text-xs text-[var(--color-mute)]">{{ $cat->id }}</span>
                                    </td>
                                    <td class="max-w-0">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <i class="bi bi-tag text-[var(--color-clay-500)] shrink-0"></i>
                                            <span class="font-semibold text-[var(--color-ink-2)] truncate">{{ $cat->category_name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="pill pill-mute">{{ $cat->blog_count }}</span>
                                    </td>
                                    <td>
                                        <x-admin.status-pill :active="(int) $cat->status === 1" />
                                    </td>
                                    <td class="hidden md:table-cell">
                                        <span class="text-xs text-[var(--color-mute)]">{{ \Illuminate\Support\Carbon::parse($cat->create_time)->format('d M Y') }}</span>
                                    </td>
                                    <td class="hidden lg:table-cell">
                                        <span class="text-xs text-[var(--color-mute)] truncate block">{{ $cat->admin_name ?: '—' }}</span>
                                    </td>
                                    <td>
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn btn-ghost btn-sm" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <x-admin.toggle-form
                                                :action="route('admin.categories.toggle', $cat->id)"
                                                :active="(bool) $cat->status"
                                                variant="publish" />
                                            <x-admin.delete-form
                                                :action="route('admin.categories.destroy', $cat->id)"
                                                message="Delete this category permanently?"
                                                :disabled="$cat->blog_count > 0" />
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
