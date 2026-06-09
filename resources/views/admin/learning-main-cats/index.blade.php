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
    if (iconClass.startsWith('bi ') || iconClass.startsWith('fa-')) {
      preview.innerHTML = '<i class="' + iconClass + ' text-2xl text-[var(--color-clay-700)]" aria-hidden="true"></i>';
      return;
    }
    preview.innerHTML = '<div class="' + iconClass + ' lc-lucide text-[var(--color-clay-700)]" aria-hidden="true"></div>';
    if (typeof window.mountLucideIcons === 'function') {
      window.mountLucideIcons(preview);
    }
  }

  input.addEventListener('input', updatePreview);
  updatePreview();
})();
</script>
@endpush

@section('title', 'Main categories')
@section('breadcrumb')
    <span class="text-[var(--color-ink-2)]">Main categories</span>
@endsection
@section('page_title', 'Learning — main categories')

@section('content')
    <p class="text-sm text-[var(--color-mute)] mb-5">Step 1: Create main categories. Then add subcategories and resources.</p>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="lg:col-span-2">
            <x-admin.datatable-card
                :empty="$categories->isEmpty()"
                empty-icon="bi-inbox"
                empty-title="No main categories yet"
                empty-text="Add a main category using the form.">
                <table data-admin-datatable class="table w-full">
                    <thead>
                        <tr>
                            <th class="no-sort w-12">Icon</th>
                            <th>Category</th>
                            <th>Subcategories</th>
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
                                <td>
                                    <div class="font-medium text-[var(--color-ink-2)]">{{ $cat->cat_name }}</div>
                                    @if (!empty($cat->description))
                                        <p class="text-xs text-[var(--color-mute)] mt-1 line-clamp-2">{{ $cat->description }}</p>
                                    @endif
                                </td>
                                <td class="text-sm text-[var(--color-mute)]">{{ $subCounts[$cat->id] ?? 0 }}</td>
                                <td data-status-cell>
                                    <x-admin.status-pill :active="(bool) $cat->status" />
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.learning-main-cats.edit', $cat->id) }}" class="btn btn-ghost btn-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <x-admin.toggle-form
                                            :action="route('admin.learning-main-cats.toggle', $cat->id)"
                                            :active="(bool) $cat->status" />
                                        <x-admin.delete-form
                                            :action="route('admin.learning-main-cats.destroy', $cat->id)"
                                            message="Delete this main category?" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-admin.datatable-card>
        </div>

        <div class="lg:col-span-1">
            <form method="POST" action="{{ route('admin.learning-main-cats.store') }}" class="card p-5 lg:sticky lg:top-20">
                @csrf
                <h3 class="font-display text-lg text-[var(--color-ink-2)] mb-4">New main category</h3>

                <div class="mb-4">
                    <label class="label" for="cat_name">Name</label>
                    <input type="text" name="cat_name" id="cat_name" class="input" required maxlength="255"
                           value="{{ old('cat_name') }}" placeholder="e.g. Nutrition">
                    @error('cat_name') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="label" for="description">Description</label>
                    <textarea name="description" id="description" rows="2" class="textarea" maxlength="500"
                              placeholder="Short summary for the public site">{{ old('description') }}</textarea>
                    @error('description') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="label" for="cat_icon">Icon class</label>
                    <input type="text" name="cat_icon" id="cat_icon" class="input" required maxlength="255"
                           value="{{ old('cat_icon', 'icon-folder') }}" placeholder="icon-salad">
                    @error('cat_icon') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="label" for="sort_order">Sort order</label>
                    <input type="number" name="sort_order" id="sort_order" class="input" min="0" max="9999"
                           value="{{ old('sort_order', 0) }}">
                    @error('sort_order') <p class="err">{{ $message }}</p> @enderror
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
                    <div id="lc-icon-preview" class="w-12 h-12 rounded-lg bg-white border border-[var(--color-line)] flex items-center justify-center [&_svg]:w-6 [&_svg]:h-6">
                        @include('partials.lucide-icon', ['class' => old('cat_icon', 'icon-folder')])
                    </div>
                    <div class="text-xs text-[var(--color-mute)]">Icon preview</div>
                </div>

                <button type="submit" class="btn btn-primary w-full">
                    <i class="bi bi-plus-lg"></i>
                    <span>Add main category</span>
                </button>
            </form>
        </div>
    </div>
@endsection
