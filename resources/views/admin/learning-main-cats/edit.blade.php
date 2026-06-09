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

@section('title', 'Edit main category')
@section('breadcrumb')
    <a href="{{ route('admin.learning-main-cats.index') }}">Main categories</a>
    <i class="bi bi-chevron-right text-xs"></i>
    <span class="text-[var(--color-ink-2)]">Edit</span>
@endsection
@section('page_title', 'Edit main category')

@section('topbar_actions')
    <a href="{{ route('admin.learning-main-cats.index') }}" class="btn btn-ghost">
        <i class="bi bi-arrow-left"></i>
        <span>Back</span>
    </a>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.learning-main-cats.update', $category->id) }}" class="card p-6 max-w-2xl">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="label" for="cat_name">Name</label>
                <input type="text" name="cat_name" id="cat_name" class="input" required maxlength="255"
                       value="{{ old('cat_name', $category->cat_name) }}">
                @error('cat_name') <p class="err">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="label" for="description">Description</label>
                <textarea name="description" id="description" rows="3" class="textarea" maxlength="500">{{ old('description', $category->description ?? '') }}</textarea>
                @error('description') <p class="err">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label" for="cat_icon">Icon class</label>
                <input type="text" name="cat_icon" id="cat_icon" class="input" required maxlength="255"
                       value="{{ old('cat_icon', $category->cat_icon) }}">
                @error('cat_icon') <p class="err">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label" for="sort_order">Sort order</label>
                <input type="number" name="sort_order" id="sort_order" class="input" min="0" max="9999"
                       value="{{ old('sort_order', $category->sort_order ?? 0) }}">
                @error('sort_order') <p class="err">{{ $message }}</p> @enderror
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
                <div class="rounded-lg bg-[var(--color-paper)] border border-[var(--color-line)] p-4 flex items-center gap-3">
                    <div id="lc-icon-preview" class="w-12 h-12 rounded-lg bg-white border border-[var(--color-line)] flex items-center justify-center [&_svg]:w-6 [&_svg]:h-6">
                        @include('partials.lucide-icon', ['class' => old('cat_icon', $category->cat_icon)])
                    </div>
                    <div class="text-xs text-[var(--color-mute)]">Icon preview</div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 mt-6">
            <a href="{{ route('admin.learning-main-cats.index') }}" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check2"></i>
                <span>Update</span>
            </button>
        </div>
    </form>
@endsection
