@props(['entries' => ['resources/css/app.css', 'resources/js/app.js']])

@php
    $entries = is_array($entries) ? $entries : [$entries];
    $manifestPath = public_path('build/manifest.json');
    $manifest = file_exists($manifestPath)
        ? json_decode(file_get_contents($manifestPath), true)
        : [];
@endphp

@foreach ($entries as $entry)
    @if (! isset($manifest[$entry]))
        @continue
    @endif
    @php $chunk = $manifest[$entry]; @endphp
    @if (! empty($chunk['css']))
        @foreach ($chunk['css'] as $cssFile)
            <link rel="stylesheet" href="{{ asset('build/'.$cssFile) }}">
        @endforeach
    @endif
    @if (str_ends_with($entry, '.css'))
        <link rel="stylesheet" href="{{ asset('build/'.$chunk['file']) }}">
    @else
        <script type="module" src="{{ asset('build/'.$chunk['file']) }}" defer></script>
    @endif
@endforeach
