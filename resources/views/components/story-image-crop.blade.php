@props([
    'inputId' => 'image',
    'inputName' => 'image',
    'existingUrl' => null,
])

<div data-story-image-crop data-aspect-ratio="1.7777777778" data-max-size-mb="0.1" data-max-dimension="1024">
    @if($existingUrl)
        <img src="{{ $existingUrl }}" alt="" class="mb-3 w-full h-auto object-cover rounded-lg border border-[var(--color-line)]">
        <p class="help mb-3">Current thumbnail. Upload a new image to replace it.</p>
    @endif

    <label class="label" for="{{ $inputId }}">{{ $existingUrl ? 'Replace image' : 'Upload image' }}</label>
    <input
        id="{{ $inputId }}"
        type="file"
        name="{{ $inputName }}"
        accept="image/*"
        data-story-image-input
        class="input"
    >
    @error($inputName) <p class="err">{{ $message }}</p> @enderror
    <p class="help mt-2">JPG, PNG or WebP. Cropped to 16:9 and compressed for faster upload.</p>

    <div data-story-crop-panel class="hidden mt-4 max-w-full overflow-hidden rounded-lg border border-[var(--color-line)] bg-[var(--color-paper-2)]">
        <img
            data-story-image-preview
            src=""
            alt="Crop preview"
            class="block max-w-full h-auto"
            style="max-height: min(360px, 55vh);"
        >
    </div>

    <button
        type="button"
        data-story-crop-apply
        class="btn btn-primary btn-sm hidden mt-3 w-full sm:w-auto"
    >
        <i class="bi bi-crop"></i>
        <span>Crop & use image</span>
    </button>

    <p data-story-crop-status class="help mt-2 min-h-[1.25rem]"></p>

    <div data-story-image-result-wrap class="hidden mt-3">
        <p class="text-xs font-medium text-[var(--color-ink-2)] mb-2">Preview</p>
        <img
            data-story-image-result
            src=""
            alt="Compressed thumbnail preview"
            class="w-full h-auto object-cover rounded-lg border border-[var(--color-line)]"
        >
    </div>
</div>

@once
    @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    @endpush
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/browser-image-compression/2.0.2/browser-image-compression.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{ asset('assets/js/story-image-crop.js') }}" defer></script>
    @endpush
@endonce
