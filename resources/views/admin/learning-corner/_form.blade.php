@php
    $isEdit          = isset($resource);
    $imageExisting   = $isEdit && $resource->image && is_file(public_path('uploads/learning/' . $resource->image))
        ? asset('uploads/learning/' . $resource->image)
        : '';
    $types           = ['book', 'posters', 'mobile kunji', 'video'];
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
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
            <p class="help">Up to 500 characters.</p>
            @error('content') <p class="err">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="space-y-5">
        <div class="card p-5">
            <label class="label" for="cat_id">Category</label>
            <select name="cat_id" id="cat_id" class="select" required>
                <option value="">Select category</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}"
                        {{ (string) old('cat_id', $isEdit ? $resource->cat_id : '') === (string) $c->id ? 'selected' : '' }}>
                        {{ $c->cat_name }}
                    </option>
                @endforeach
            </select>
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
            <label class="label" for="link">External link</label>
            <input type="url" name="link" id="link" class="input" required maxlength="500"
                   value="{{ old('link', $isEdit ? $resource->link : '') }}"
                   placeholder="https://…">
            <p class="help">Google Drive, YouTube, PDF host etc.</p>
            @error('link') <p class="err">{{ $message }}</p> @enderror
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
            <p class="help">Optional. Max 4 MB.</p>
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
