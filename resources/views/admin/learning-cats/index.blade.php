@extends('layouts.admin')

@push('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
@endpush

@section('title', 'Learning Categories')
@section('breadcrumb')
    <span class="text-[var(--color-ink-2)]">Learning Categories</span>
@endsection
@section('page_title', 'Learning Categories')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="lg:col-span-2 order-2 lg:order-1">
            @if ($categories->isEmpty())
                <div class="card p-10 text-center">
                    <div class="w-14 h-14 rounded-full bg-[var(--color-paper-2)] flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-inbox text-2xl text-[var(--color-mute-2)]"></i>
                    </div>
                    <h3 class="font-display text-lg text-[var(--color-ink-2)] mb-1">No categories yet</h3>
                    <p class="text-sm text-[var(--color-mute)]">Use the form to add the first one.</p>
                </div>
            @else
                <div class="card overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="w-10">#</th>
                                    <th class="w-12">Icon</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Admin</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $cat)
                                    <tr>
                                        <td class="text-[var(--color-mute)]">{{ $cat->id }}</td>
                                        <td>
                                            <div class="w-9 h-9 rounded-lg bg-[var(--color-clay-50)] text-[var(--color-clay-700)] flex items-center justify-center">
                                                <i class="{{ $cat->cat_icon }} text-base"></i>
                                            </div>
                                        </td>
                                        <td class="font-medium text-[var(--color-ink-2)]">{{ $cat->cat_name }}</td>
                                        <td>
                                            <x-admin.status-pill :active="(bool) $cat->status" />
                                        </td>
                                        <td class="text-[var(--color-mute)] text-xs">
                                            {{ $cat->created_at ? \Illuminate\Support\Carbon::parse($cat->created_at)->format('d M Y') : '—' }}
                                        </td>
                                        <td class="text-[var(--color-mute)] text-xs">{{ $cat->admin_name ?? '—' }}</td>
                                        <td>
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.learning-cats.edit', $cat->id) }}" class="btn btn-ghost btn-sm" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <x-admin.toggle-form
                                                    :action="route('admin.learning-cats.toggle', $cat->id)"
                                                    :active="(bool) $cat->status" />
                                                <x-admin.delete-form
                                                    :action="route('admin.learning-cats.destroy', $cat->id)"
                                                    message="Delete this category?" />
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <div class="lg:col-span-1 order-1 lg:order-2">
            <form method="POST" action="{{ route('admin.learning-cats.store') }}" class="card p-5 lg:sticky lg:top-20">
                @csrf
                <h3 class="font-display text-lg text-[var(--color-ink-2)] mb-4">New category</h3>

                <div class="mb-4">
                    <label class="label" for="cat_name">Category name</label>
                    <input type="text" name="cat_name" id="cat_name" class="input" required maxlength="255"
                           value="{{ old('cat_name') }}" placeholder="e.g. Books">
                    @error('cat_name') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="label" for="cat_icon">Icon class</label>
                    <input type="text" name="cat_icon" id="cat_icon" class="input" required maxlength="255"
                           value="{{ old('cat_icon', 'fa-solid fa-book') }}"
                           placeholder="fa-solid fa-brain">
                    <p class="help">Use FontAwesome 6 free class. Preview shown below.</p>
                    @error('cat_icon') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="label" for="status">Status</label>
                    <select name="status" id="status" class="select" required>
                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div class="rounded-lg bg-[var(--color-paper)] border border-[var(--color-line)] p-4 flex items-center gap-3 mb-5">
                    <div class="w-12 h-12 rounded-lg bg-white border border-[var(--color-line)] flex items-center justify-center">
                        <i class="{{ old('cat_icon', 'fa-solid fa-book') }} text-2xl text-[var(--color-clay-700)]"></i>
                    </div>
                    <div class="text-xs text-[var(--color-mute)]">Live icon preview</div>
                </div>

                <button type="submit" class="btn btn-primary w-full">
                    <i class="bi bi-plus-lg"></i>
                    <span>Add category</span>
                </button>
            </form>
        </div>
    </div>
@endsection
