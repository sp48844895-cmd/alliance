@props([
    'filename' => '',
    'folder'   => 'uploads',
    'src'      => null,
    'class'    => 'w-12 h-12 object-cover rounded-lg',
    'icon'     => 'bi-image',
    'alt'      => '',
])

@php
    $imageSrc = $src;
    if ($imageSrc === null) {
        $rel = trim((string) $filename) !== '' ? trim($folder, '/') . '/' . trim((string) $filename) : '';
        $path = $rel !== '' ? public_path($rel) : '';
        $imageSrc = $rel !== '' && is_file($path) ? asset($rel) : '';
    }
@endphp

@if ($imageSrc !== '')
    <img src="{{ $imageSrc }}" alt="{{ $alt }}" class="{{ $class }}">
@else
    <div class="{{ $class }} flex items-center justify-center bg-[var(--color-paper-2)] text-[var(--color-mute-2)]">
        <i class="bi {{ $icon }}"></i>
    </div>
@endif
