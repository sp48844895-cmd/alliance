@php
    $isEdit         = isset($banner);
    $desktopExisting = $isEdit && $banner->dbannerimg && is_file(public_path('uploads/banners/' . $banner->dbannerimg))
        ? asset('uploads/banners/' . $banner->dbannerimg)
        : '';
    $mobileExisting  = $isEdit && $banner->mbannerimg && is_file(public_path('uploads/banners/' . $banner->mbannerimg))
        ? asset('uploads/banners/' . $banner->mbannerimg)
        : '';
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div class="card p-5">
        <span class="label">Desktop image</span>
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
        <p class="help">Recommended 1920×720. JPG, PNG or WebP.</p>
        @error('dbannerimg') <p class="err">{{ $message }}</p> @enderror
    </div>

    <div class="card p-5">
        <span class="label">Mobile image</span>
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
        <p class="help">Recommended 750×1000. JPG, PNG or WebP. Both images are required.</p>
        @error('mbannerimg') <p class="err">{{ $message }}</p> @enderror
    </div>
</div>

<div class="card p-5 mt-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="label" for="ytlink">YouTube link</label>
            <input type="text" name="ytlink" id="ytlink" class="input"
                   value="{{ old('ytlink', $isEdit ? $banner->ytlink : '') }}"
                   placeholder="https://youtube.com/...">
            <p class="help">Optional YouTube embed/link URL.</p>
            @error('ytlink') <p class="err">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label" for="redirect">Redirect URL</label>
            <input type="text" name="redirect" id="redirect" class="input" required
                   value="{{ old('redirect', $isEdit ? $banner->redirect : '#') }}"
                   placeholder="#">
            <p class="help">Where the banner click should send users.</p>
            @error('redirect') <p class="err">{{ $message }}</p> @enderror
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
