@php
    $isEdit       = isset($blog);
    $titleVal     = old('title',   $isEdit ? $blog->title   : '');
    $catVal       = old('cat_id',  $isEdit ? $blog->cat_id  : '');
    $contentVal   = old('content', $isEdit ? $blog->content : '');
    $tagVal       = old('tag',     $isEdit ? $blog->tag     : '');
    $locationVal  = old('location', $isEdit ? ($blog->location ?? '') : '');
    $statusVal    = (string) old('status', $isEdit ? $blog->status : '0');
    $rateVal      = old('rate',    $isEdit ? $blog->rate    : 0);
    $currentImage = $isEdit ? $blog->image : '';
    $currentImageRel = null;
    if ($isEdit && $currentImage) {
        if (file_exists(public_path('storage/story/' . $currentImage))) {
            $currentImageRel = 'storage/story/' . $currentImage;
        } elseif (file_exists(public_path('uploads/blogs/' . $currentImage))) {
            $currentImageRel = 'uploads/blogs/' . $currentImage;
        }
    }
@endphp

<form method="POST" action="{{ $isEdit ? route('admin.blogs.update', $blog->id) : route('admin.blogs.store') }}" enctype="multipart/form-data">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-5">
            <div class="card p-5 lg:p-6">
                <div class="mb-4">
                    <label class="label" for="title">Title</label>
                    <input id="title" type="text" name="title" value="{{ $titleVal }}" class="input" placeholder="Enter story title" required>
                    @error('title') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label" for="content">Content</label>
                    <textarea id="content" name="content" rows="12" class="textarea" placeholder="Write the story content...">{{ $contentVal }}</textarea>
                    @error('content') <p class="err">{{ $message }}</p> @enderror
                    <p class="help">Supports plain text and basic HTML.</p>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="card p-5">
                <h3 class="font-display text-base text-[var(--color-ink-2)] mb-4">Publish</h3>

                <div class="mb-4">
                    <label class="label" for="status">Status</label>
                    <select id="status" name="status" class="select">
                        <option value="0" @selected($statusVal === '0')>Draft</option>
                        <option value="1" @selected($statusVal === '1')>Published</option>
                    </select>
                    @error('status') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="label" for="cat_id">Category</label>
                    <select id="cat_id" name="cat_id" class="select" required>
                        <option value="">Select category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected((string) $catVal === (string) $cat->id)>{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                    @error('cat_id') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label" for="rate">Rating</label>
                    <input id="rate" type="number" name="rate" value="{{ $rateVal }}" min="0" max="5" class="input">
                    @error('rate') <p class="err">{{ $message }}</p> @enderror
                    <p class="help">Between 0 and 5.</p>
                </div>
            </div>

            <div class="card p-5">
                <h3 class="font-display text-base text-[var(--color-ink-2)] mb-4">Tags & location</h3>
                <div class="mb-4">
                    <label class="label" for="tag">Tags</label>
                    <input id="tag" type="text" name="tag" value="{{ $tagVal }}" class="input" placeholder="climate, health, women">
                    @error('tag') <p class="err">{{ $message }}</p> @enderror
                    <p class="help">Comma-separated list of tags.</p>
                </div>
                <div>
                    <label class="label" for="location">Filed location</label>
                    <select id="location" name="location" class="select">
                        <option value="">Auto-detect from title/content</option>
                        @foreach ($districts ?? [] as $district)
                            <option value="{{ $district->district_name }}" @selected($locationVal === $district->district_name)>
                                {{ $district->district_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('location') <p class="err">{{ $message }}</p> @enderror
                    <p class="help">Shown on the homepage as “Filed: …”.</p>
                </div>
            </div>

            <div class="card p-5">
                <h3 class="font-display text-base text-[var(--color-ink-2)] mb-4">Featured Image</h3>

                @if($currentImageRel)
                    <div class="mb-3">
                        <img src="{{ asset($currentImageRel) }}" alt="" class="w-full h-auto object-cover rounded-lg border border-[var(--color-line)]">
                        <label class="flex items-center gap-2 mt-2 text-xs text-[var(--color-mute)]">
                            <input type="checkbox" name="delete_image" value="1" class="rounded">
                            <span>Remove current image</span>
                        </label>
                    </div>
                @endif

                <div>
                    <label class="label" for="image">{{ $isEdit && $currentImage ? 'Replace image' : 'Upload image' }}</label>
                    <input id="image" type="file" name="image" accept="image/*" data-image-preview="#blogPreview" class="input">
                    @error('image') <p class="err">{{ $message }}</p> @enderror
                    <p class="help">JPG, PNG or WebP, max 4 MB.</p>
                    <img id="blogPreview" src="" alt="" class="hidden mt-3 w-full h-auto object-cover rounded-lg border border-[var(--color-line)]">
                </div>
            </div>
        </div>
    </div>

    <div class="card p-4 mt-5 flex items-center justify-end gap-2">
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-ghost btn-sm">Cancel</a>
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="bi bi-check2"></i>
            <span>{{ $isEdit ? 'Update story' : 'Save story' }}</span>
        </button>
    </div>
</form>
