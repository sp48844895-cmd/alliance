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
        <label class="label" for="dbannerimg">Desktop image</label>
        <div class="aspect-video bg-[var(--color-paper)] rounded-lg border border-[var(--color-line)] overflow-hidden mb-3 flex items-center justify-center">
            <img id="dPreview"
                 src="{{ $desktopExisting }}"
                 alt="Desktop preview"
                 class="w-full h-full object-contain {{ $desktopExisting ? '' : 'hidden' }}">
            @if (!$desktopExisting)
                <i class="bi bi-image text-3xl text-[var(--color-mute-2)]"></i>
            @endif
        </div>
        <input type="file" name="dbannerimg" id="dbannerimg" accept="image/*"
               class="input"
               data-image-preview="#dPreview"
               {{ $isEdit ? '' : 'required' }}>
        <p class="help">Recommended 1920×720. Max 4 MB.</p>
        @error('dbannerimg') <p class="err">{{ $message }}</p> @enderror
    </div>

    <div class="card p-5">
        <label class="label" for="mbannerimg">Mobile image</label>
        <div class="aspect-[9/16] max-h-72 mx-auto bg-[var(--color-paper)] rounded-lg border border-[var(--color-line)] overflow-hidden mb-3 flex items-center justify-center">
            <img id="mPreview"
                 src="{{ $mobileExisting }}"
                 alt="Mobile preview"
                 class="w-full h-full object-contain {{ $mobileExisting ? '' : 'hidden' }}">
            @if (!$mobileExisting)
                <i class="bi bi-phone text-3xl text-[var(--color-mute-2)]"></i>
            @endif
        </div>
        <input type="file" name="mbannerimg" id="mbannerimg" accept="image/*"
               class="input"
               data-image-preview="#mPreview"
               {{ $isEdit ? '' : 'required' }}>
        <p class="help">Recommended 750×1000. Max 4 MB.</p>
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
