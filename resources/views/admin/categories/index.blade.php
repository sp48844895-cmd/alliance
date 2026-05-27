@extends('layouts.admin')

@section('title', 'Categories')
@section('page_title', 'Categories')

@section('breadcrumb')
    <span>Content</span>
    <i class="bi bi-chevron-right text-xs"></i>
    <span>Categories</span>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="card p-5">
                <h2 class="font-display text-lg text-[var(--color-ink-2)]">Add category</h2>
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
            <x-admin.datatable-card
                :empty="$categories->isEmpty()"
                empty-icon="bi-tags"
                empty-title="No categories yet"
                empty-text="Use the form on the left to add your first category.">
                <table data-admin-datatable class="table w-full">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Stories</th>
                            <th>Status</th>
                            <th>Meta</th>
                            <th class="no-sort col-actions text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $cat)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-2 min-w-0">
                                        <i class="bi bi-tag text-[var(--color-clay-500)] shrink-0"></i>
                                        <span class="font-semibold text-[var(--color-ink-2)]">{{ $cat->category_name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="pill pill-mute">{{ $cat->blog_count }}</span>
                                </td>
                                <td data-status-cell>
                                    <x-admin.status-pill :active="(int) $cat->status === 1" />
                                </td>
                                <td>
                                    <div class="text-xs text-[var(--color-mute)] space-y-1">
                                        <div>{{ \Illuminate\Support\Carbon::parse($cat->create_time)->format('d M Y') }}</div>
                                        <div>{{ $cat->admin_name ?: '—' }}</div>
                                    </div>
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
            </x-admin.datatable-card>
        </div>
    </div>
@endsection
