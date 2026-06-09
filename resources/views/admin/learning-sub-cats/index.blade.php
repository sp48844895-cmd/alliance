@extends('layouts.admin')

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script src="{{ asset('assets/js/lucide-icons.js') }}?v={{ filemtime(public_path('assets/js/lucide-icons.js')) }}"></script>
<script>
(function () {
  var input = document.getElementById('cat_icon');
  var preview = document.getElementById('lc-icon-preview');
  if (!input || !preview) return;
  function updatePreview() {
    var iconClass = input.value.trim() || 'icon-folder';
    preview.innerHTML = '<div class="' + iconClass + ' lc-lucide text-[var(--color-clay-700)]" aria-hidden="true"></div>';
    if (typeof window.mountLucideIcons === 'function') window.mountLucideIcons(preview);
  }
  input.addEventListener('input', updatePreview);
  updatePreview();
})();
</script>
@endpush

@section('title', 'Subcategories')
@section('breadcrumb')
    <span class="text-[var(--color-ink-2)]">Subcategories</span>
@endsection
@section('page_title', 'Learning — subcategories')

@section('content')
    <p class="text-sm text-[var(--color-mute)] mb-5">Step 2: Add subcategories under a main category. Resources are linked to subcategories only.</p>

    @if ($mainCategories->isEmpty())
        <div class="card p-5 mb-5 border-amber-200 bg-amber-50 text-amber-900 text-sm">
            Create at least one <a href="{{ route('admin.learning-main-cats.index') }}" class="font-semibold underline">main category</a> before adding subcategories.
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="lg:col-span-2">
            <x-admin.datatable-card
                :empty="$categories->isEmpty()"
                empty-icon="bi-inbox"
                empty-title="No subcategories yet"
                empty-text="Add a subcategory using the form.">
                <table data-admin-datatable class="table w-full">
                    <thead>
                        <tr>
                            <th class="no-sort w-12">Icon</th>
                            <th>Subcategory</th>
                            <th>Main category</th>
                            <th>Resources</th>
                            <th>Status</th>
                            <th class="no-sort col-actions text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $cat)
                            <tr>
                                <td>
                                    <div class="w-9 h-9 rounded-lg bg-[var(--color-clay-50)] text-[var(--color-clay-700)] flex items-center justify-center [&_svg]:w-4 [&_svg]:h-4">
                                        @include('partials.lucide-icon', ['class' => $cat->cat_icon])
                                    </div>
                                </td>
                                <td class="font-medium text-[var(--color-ink-2)]">{{ $cat->cat_name }}</td>
                                <td class="text-sm text-[var(--color-mute)]">{{ $cat->main_name }}</td>
                                <td class="text-sm text-[var(--color-mute)]">{{ $resourceCounts[$cat->id] ?? 0 }}</td>
                                <td data-status-cell>
                                    <x-admin.status-pill :active="(bool) $cat->status" />
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.learning-sub-cats.edit', $cat->id) }}" class="btn btn-ghost btn-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <x-admin.toggle-form
                                            :action="route('admin.learning-sub-cats.toggle', $cat->id)"
                                            :active="(bool) $cat->status" />
                                        <x-admin.delete-form
                                            :action="route('admin.learning-sub-cats.destroy', $cat->id)"
                                            message="Delete this subcategory?" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-admin.datatable-card>
        </div>

        <div class="lg:col-span-1">
            <form method="POST" action="{{ route('admin.learning-sub-cats.store') }}" class="card p-5 lg:sticky lg:top-20">
                @csrf
                <h3 class="font-display text-lg text-[var(--color-ink-2)] mb-4">New subcategory</h3>

                <div class="mb-4">
                    <label class="label" for="parent_id">Main category</label>
                    <select name="parent_id" id="parent_id" class="select" required {{ $mainCategories->isEmpty() ? 'disabled' : '' }}>
                        <option value="">Select main category</option>
                        @foreach ($mainCategories as $main)
                            <option value="{{ $main->id }}" {{ (string) old('parent_id') === (string) $main->id ? 'selected' : '' }}>
                                {{ $main->cat_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="label" for="cat_name">Subcategory name</label>
                    <input type="text" name="cat_name" id="cat_name" class="input" required maxlength="255"
                           value="{{ old('cat_name') }}" placeholder="e.g. Maternal health" {{ $mainCategories->isEmpty() ? 'disabled' : '' }}>
                    @error('cat_name') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="label" for="description">Description</label>
                    <textarea name="description" id="description" rows="2" class="textarea" maxlength="500"
                              placeholder="Optional">{{ old('description') }}</textarea>
                    @error('description') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="label" for="cat_icon">Icon class</label>
                    <input type="text" name="cat_icon" id="cat_icon" class="input" required maxlength="255"
                           value="{{ old('cat_icon', 'icon-folder') }}" {{ $mainCategories->isEmpty() ? 'disabled' : '' }}>
                    @error('cat_icon') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="label" for="sort_order">Sort order</label>
                    <input type="number" name="sort_order" id="sort_order" class="input" min="0" max="9999"
                           value="{{ old('sort_order', 0) }}" {{ $mainCategories->isEmpty() ? 'disabled' : '' }}>
                    @error('sort_order') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="label" for="status">Status</label>
                    <select name="status" id="status" class="select" required {{ $mainCategories->isEmpty() ? 'disabled' : '' }}>
                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div class="rounded-lg bg-[var(--color-paper)] border border-[var(--color-line)] p-4 flex items-center gap-3 mb-5">
                    <div id="lc-icon-preview" class="w-12 h-12 rounded-lg bg-white border border-[var(--color-line)] flex items-center justify-center [&_svg]:w-6 [&_svg]:h-6">
                        @include('partials.lucide-icon', ['class' => old('cat_icon', 'icon-folder')])
                    </div>
                    <div class="text-xs text-[var(--color-mute)]">Icon preview</div>
                </div>

                <button type="submit" class="btn btn-primary w-full" {{ $mainCategories->isEmpty() ? 'disabled' : '' }}>
                    <i class="bi bi-plus-lg"></i>
                    <span>Add subcategory</span>
                </button>
            </form>
        </div>
    </div>
@endsection
