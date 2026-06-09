@php
    $isEdit = isset($banner);
    $desktopExisting = $isEdit && $banner->dbannerimg && is_file(public_path('uploads/banners/' . $banner->dbannerimg))
        ? asset('uploads/banners/' . $banner->dbannerimg)
        : '';
    $mobileExisting = $isEdit && $banner->mbannerimg && is_file(public_path('uploads/banners/' . $banner->mbannerimg))
        ? asset('uploads/banners/' . $banner->mbannerimg)
        : '';
    $frontExisting = $isEdit && ! empty($banner->front_image) && is_file(public_path('uploads/banners/' . $banner->front_image))
        ? asset('uploads/banners/' . $banner->front_image)
        : '';
    $statusVal = old('status', $isEdit ? (string) ($banner->status ?? '1') : '1');
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <div class="lg:col-span-2 space-y-5">
        <div class="card p-5">
            <label class="label" for="small_title">Small title</label>
            <input type="text" name="small_title" id="small_title" class="input" maxlength="100"
                value="{{ old('small_title', $isEdit ? $banner->small_title : '') }}"
                placeholder="e.g. Welcome">
            <p class="help">Short label shown above the main title (chapter label).</p>
            @error('small_title') <p class="err">{{ $message }}</p> @enderror
        </div>

        <div class="card p-5">
            <label class="label" for="title">Title <span class="text-[var(--color-flame)]">*</span></label>
            <input type="text" name="title" id="title" class="input" maxlength="150" required
                value="{{ old('title', $isEdit ? $banner->title : '') }}"
                placeholder="e.g. Social & Behaviour Change Communication for all.">
            @error('title') <p class="err">{{ $message }}</p> @enderror
        </div>

        <div class="card p-5">
            <label class="label" for="description">Description <span class="text-[var(--color-flame)]">*</span></label>
            <textarea name="description" id="description" rows="4" class="textarea" required
                placeholder="Brief summary shown with the banner...">{{ old('description', $isEdit ? $banner->description : '') }}</textarea>
            @error('description') <p class="err">{{ $message }}</p> @enderror
        </div>

        <div class="card p-5">
            <label class="label" for="url">URL <span class="text-[var(--color-mute)] font-normal">(optional)</span></label>
            <input type="text" name="url" id="url" class="input" maxlength="500"
                value="{{ old('url', $isEdit ? $banner->url : '') }}"
                placeholder="https://example.com/page or /programs">
            <p class="help">Visitors are redirected when they click the front image. Use a full URL or internal path.</p>
            @error('url') <p class="err">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="card p-5">
                <span class="label">Background image (desktop) <span class="text-[var(--color-flame)]">*</span></span>
                <label for="dbannerimg" class="block cursor-pointer mb-3">
                    <div class="aspect-video bg-[var(--color-paper)] rounded-lg border border-dashed border-[var(--color-line)] overflow-hidden flex items-center justify-center hover:border-[var(--color-clay-500)] transition">
                        <img id="dPreview"
                            @if ($desktopExisting) src="{{ $desktopExisting }}" @endif
                            alt="Desktop preview"
                            class="w-full h-full object-contain {{ $desktopExisting ? '' : 'hidden' }}">
                        <div id="dPlaceholder" class="flex flex-col items-center gap-2 text-[var(--color-mute-2)] {{ $desktopExisting ? 'hidden' : '' }}">
                            <i class="bi bi-image text-3xl"></i>
                            <span class="text-xs font-semibold">Click to choose desktop image</span>
                        </div>
                    </div>
                </label>
                <input type="file" name="dbannerimg" id="dbannerimg" accept="image/jpeg,image/png,image/webp,image/gif"
                    class="input"
                    data-image-preview="#dPreview"
                    data-image-preview-placeholder="#dPlaceholder"
                    {{ $isEdit ? '' : 'required' }}>
                <p class="help">Recommended 1920×720. Full-width hero background.</p>
                @error('dbannerimg') <p class="err">{{ $message }}</p> @enderror
            </div>

            <div class="card p-5">
                <span class="label">Background image (mobile) <span class="text-[var(--color-flame)]">*</span></span>
                <label for="mbannerimg" class="block cursor-pointer mb-3">
                    <div class="aspect-[9/16] max-h-72 mx-auto bg-[var(--color-paper)] rounded-lg border border-dashed border-[var(--color-line)] overflow-hidden flex items-center justify-center hover:border-[var(--color-clay-500)] transition">
                        <img id="mPreview"
                            @if ($mobileExisting) src="{{ $mobileExisting }}" @endif
                            alt="Mobile preview"
                            class="w-full h-full object-contain {{ $mobileExisting ? '' : 'hidden' }}">
                        <div id="mPlaceholder" class="flex flex-col items-center gap-2 text-[var(--color-mute-2)] {{ $mobileExisting ? 'hidden' : '' }}">
                            <i class="bi bi-phone text-3xl"></i>
                            <span class="text-xs font-semibold">Click to choose mobile image</span>
                        </div>
                    </div>
                </label>
                <input type="file" name="mbannerimg" id="mbannerimg" accept="image/jpeg,image/png,image/webp,image/gif"
                    class="input"
                    data-image-preview="#mPreview"
                    data-image-preview-placeholder="#mPlaceholder"
                    {{ $isEdit ? '' : 'required' }}>
                <p class="help">Recommended 750×1000. Shown on small screens.</p>
                @error('mbannerimg') <p class="err">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="card p-5">
            <span class="label">Front image <span class="text-[var(--color-flame)]">@unless($isEdit)*@endunless</span></span>
            <label for="front_image" class="block cursor-pointer mb-3">
                <div class="max-w-xs aspect-[173/231] bg-[var(--color-paper)] rounded-lg border border-dashed border-[var(--color-line)] overflow-hidden flex items-center justify-center hover:border-[var(--color-clay-500)] transition">
                    <img id="fPreview"
                        @if ($frontExisting) src="{{ $frontExisting }}" @endif
                        alt="Front image preview"
                        class="w-full h-full object-cover {{ $frontExisting ? '' : 'hidden' }}">
                    <div id="fPlaceholder" class="flex flex-col items-center gap-2 text-[var(--color-mute-2)] {{ $frontExisting ? 'hidden' : '' }}">
                        <i class="bi bi-card-image text-3xl"></i>
                        <span class="text-xs font-semibold">Click to choose front image</span>
                    </div>
                </div>
            </label>
            <input type="file" name="front_image" id="front_image" accept="image/jpeg,image/png,image/webp,image/gif"
                class="input"
                data-image-preview="#fPreview"
                data-image-preview-placeholder="#fPlaceholder"
                {{ $isEdit ? '' : 'required' }}>
            <p class="help">Card image on the right side of the hero. Clickable when a URL is set.</p>
            @error('front_image') <p class="err">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="space-y-5">
        <div class="card p-5 space-y-4">
            <div>
                <label class="label" for="sort_order">Sort order</label>
                <input type="number" name="sort_order" id="sort_order" class="input" min="0"
                    value="{{ old('sort_order', $isEdit ? $banner->sort_order : 0) }}">
                <p class="help">Lower number appears first.</p>
                @error('sort_order') <p class="err">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label" for="status">Status</label>
                <select name="status" id="status" class="select" required>
                    <option value="1" @selected($statusVal === '1')>Active</option>
                    <option value="0" @selected($statusVal === '0')>Inactive</option>
                </select>
                @error('status') <p class="err">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
</div>

<div class="flex items-center justify-end gap-2 mt-5">
    <a href="{{ route('admin.banners.index') }}" class="btn btn-ghost">Cancel</a>
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check2"></i>
        <span>{{ $isEdit ? 'Update banner' : 'Save banner' }}</span>
    </button>
</div>
