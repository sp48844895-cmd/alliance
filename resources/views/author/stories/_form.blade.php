@php
    $isEdit = isset($story);
    $titleVal = old('title', $isEdit ? $story->title : '');
    $catVal = old('category', $isEdit ? $story->category : '');
    $contentVal = old('content', $isEdit ? $story->content : '');
    $tagVal = old('tag', $isEdit ? $story->tag : '');
    $thumb = $isEdit ? ($story->thumbnail_path ?? '') : '';
@endphp

<form method="POST" action="{{ $isEdit ? route('author.stories.update', $story->id) : route('author.stories.store') }}" enctype="multipart/form-data">
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
                    <label class="label" for="story-content">Content</label>
                    <textarea id="story-content" name="content" data-rich-editor rows="12" class="textarea min-h-[280px]" placeholder="Write your story...">{{ $contentVal }}</textarea>
                    <p class="help mt-2">Use headings, lists, and links. Your formatting is kept after admin approval.</p>
                    @error('content') <p class="err">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="card p-5">
                <h3 class="font-display text-base text-[var(--color-ink-2)] mb-4">Details</h3>

                <div class="mb-4">
                    <label class="label" for="category">Category</label>
                    <select id="category" name="category" class="select" required>
                        <option value="">Select category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" @selected($catVal === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label" for="tag">Tags</label>
                    <input id="tag" type="text" name="tag" value="{{ $tagVal }}" class="input" placeholder="health, youth">
                    @error('tag') <p class="err">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="card p-5">
                <h3 class="font-display text-base text-[var(--color-ink-2)] mb-4">Thumbnail</h3>
                @if($isEdit && $thumb && !str_starts_with($thumb, 'http'))
                    <img src="{{ asset($thumb) }}" alt="" class="mb-3 w-full h-auto object-cover rounded-lg border border-[var(--color-line)]">
                @endif
                <input id="image" type="file" name="image" accept="image/*" class="input">
                @error('image') <p class="err">{{ $message }}</p> @enderror
                <p class="help mt-2">JPG, PNG or WebP.</p>
            </div>

            <div class="card p-4 bg-[var(--color-paper-2)] border border-[var(--color-line)]">
                <p class="text-xs text-[var(--color-mute)] leading-relaxed">
                    Your story is sent to the admin for approval. You cannot publish or unpublish it yourself.
                </p>
            </div>
        </div>
    </div>

    <div class="card p-4 mt-5 flex items-center justify-end gap-2">
        <a href="{{ route('author.stories.index') }}" class="btn btn-ghost btn-sm">Cancel</a>
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="bi bi-send"></i>
            <span>{{ $isEdit ? 'Resubmit for review' : 'Submit for review' }}</span>
        </button>
    </div>
</form>
