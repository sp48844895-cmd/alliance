@php
    $isEdit          = isset($resource);
    $imageExisting   = $isEdit && $resource->image && is_file(public_path('uploads/learning/' . $resource->image))
        ? asset('uploads/learning/' . $resource->image)
        : '';
    $types           = ['book', 'posters', 'mobile kunji', 'video'];
    $currentStatus   = old('status', $isEdit ? $resource->status : 1);
    $currentDate     = old('date', $isEdit ? $resource->date : now()->toDateString());
    $selectedMainId  = $selectedMainId ?? old('main_id');
    $selectedCatId   = $selectedCatId ?? old('cat_id', $isEdit ? $resource->cat_id : '');
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Left column: main fields --}}
    <div class="lg:col-span-2 space-y-5">

        <div class="card p-5">
            <label class="label" for="title">Title</label>
            <input type="text" name="title" id="title" class="input" required maxlength="255"
                   value="{{ old('title', $isEdit ? $resource->title : '') }}"
                   placeholder="e.g. Maternal nutrition handbook">
            @error('title') <p class="err">{{ $message }}</p> @enderror
        </div>

        <div class="card p-5">
            <label class="label" for="content">Short description</label>
            <textarea name="content" id="content" rows="4" class="textarea" maxlength="500"
                      placeholder="Brief summary of the resource…">{{ old('content', $isEdit ? $resource->content : '') }}</textarea>
            <p class="help">Up to 500 characters. This appears on the detail page.</p>
            @error('content') <p class="err">{{ $message }}</p> @enderror
        </div>

        <div class="card p-5">
            <label class="label" for="link">Resource link</label>
            <input type="url" name="link" id="link" class="input" required maxlength="500"
                   value="{{ old('link', $isEdit ? $resource->link : '') }}"
                   placeholder="https://drive.google.com/… or https://youtube.com/…">
            <p class="help">Google Drive, YouTube, PDF host, or any direct URL.</p>
            @error('link') <p class="err">{{ $message }}</p> @enderror
        </div>

    </div>

    {{-- Right column: meta + image --}}
    <div class="space-y-5">

        <div class="card p-5">
            <label class="label" for="lc_main_id">Main category</label>
            <select id="lc_main_id" class="select" required>
                <option value="">Select main category</option>
                @foreach ($mainCategories as $main)
                    <option value="{{ $main->id }}" {{ (string) $selectedMainId === (string) $main->id ? 'selected' : '' }}>
                        {{ $main->cat_name }}
                    </option>
                @endforeach
            </select>
            <p class="help">Choose the main topic first.</p>
        </div>

        <div class="card p-5">
            <label class="label" for="cat_id">Subcategory</label>
            <select name="cat_id" id="cat_id" class="select" required disabled>
                <option value="">Select main category first</option>
            </select>
            <p class="help">Then pick the subcategory for this resource.</p>
            @error('cat_id') <p class="err">{{ $message }}</p> @enderror
        </div>

        <div class="card p-5">
            <label class="label" for="m_type">Resource type</label>
            <select name="m_type" id="m_type" class="select" required>
                <option value="">Select type</option>
                @foreach ($types as $t)
                    <option value="{{ $t }}"
                        {{ old('m_type', $isEdit ? $resource->m_type : '') === $t ? 'selected' : '' }}>
                        {{ ucwords($t) }}
                    </option>
                @endforeach
            </select>
            @error('m_type') <p class="err">{{ $message }}</p> @enderror
        </div>

        <div class="card p-5">
            <label class="label" for="date">Date</label>
            <input type="date" name="date" id="date" class="input" required
                   value="{{ $currentDate }}">
            @error('date') <p class="err">{{ $message }}</p> @enderror
        </div>

        <div class="card p-5">
            <label class="label">Status</label>
            <div class="flex items-center gap-4 mt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="status" value="1" class="accent-[var(--color-clay-700)]"
                           {{ (string) $currentStatus === '1' ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-[var(--color-ink-2)]">Published</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="status" value="0" class="accent-[var(--color-clay-700)]"
                           {{ (string) $currentStatus === '0' ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-[var(--color-mute)]">Draft</span>
                </label>
            </div>
            <p class="help mt-2">Draft resources are hidden from the public site.</p>
            @error('status') <p class="err">{{ $message }}</p> @enderror
        </div>

        <div class="card p-5">
            <label class="label" for="image">Thumbnail</label>
            <div class="aspect-video bg-[var(--color-paper)] rounded-lg border border-[var(--color-line)] overflow-hidden mb-3 flex items-center justify-center">
                <img id="lcPreview"
                     src="{{ $imageExisting }}"
                     alt="Thumbnail preview"
                     class="w-full h-full object-cover {{ $imageExisting ? '' : 'hidden' }}">
                @if (!$imageExisting)
                    <i class="bi bi-image text-3xl text-[var(--color-mute-2)]"></i>
                @endif
            </div>
            <input type="file" name="image" id="image" accept="image/*"
                   class="input" data-image-preview="#lcPreview">
            <p class="help">Optional. Shown as card thumbnail.</p>
            @error('image') <p class="err">{{ $message }}</p> @enderror
        </div>

    </div>
</div>

<div class="flex items-center justify-end gap-2 mt-5">
    <a href="{{ route('admin.learning-corner.index') }}" class="btn btn-ghost">Cancel</a>
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check2"></i>
        <span>{{ $isEdit ? 'Update resource' : 'Save resource' }}</span>
    </button>
</div>

@push('scripts')
<script>
(function () {
  var mainSelect = document.getElementById('lc_main_id');
  var subSelect = document.getElementById('cat_id');
  if (!mainSelect || !subSelect) return;

  var subs = @json($subcategories->map(fn ($s) => ['id' => $s->id, 'name' => $s->cat_name, 'main_id' => (int) $s->main_id])->values());
  var selectedMain = @json($selectedMainId ? (int) $selectedMainId : null);
  var selectedSub = @json($selectedCatId ? (int) $selectedCatId : null);

  function fillSubs(mainId, keepSubId) {
    var options = '<option value="">Select subcategory</option>';
    var list = mainId ? subs.filter(function (s) { return s.main_id === mainId; }) : [];

    list.forEach(function (s) {
      var sel = keepSubId && keepSubId === s.id ? ' selected' : '';
      options += '<option value="' + s.id + '"' + sel + '>' + s.name + '</option>';
    });

    subSelect.innerHTML = options;
    subSelect.disabled = !mainId || list.length === 0;

    if (mainId && list.length === 0) {
      subSelect.innerHTML = '<option value="">No subcategories for this main category</option>';
    }
  }

  mainSelect.addEventListener('change', function () {
    var mainId = parseInt(mainSelect.value, 10) || null;
    fillSubs(mainId, null);
  });

  if (selectedMain) {
    mainSelect.value = String(selectedMain);
    fillSubs(selectedMain, selectedSub);
  }
})();
</script>
@endpush
