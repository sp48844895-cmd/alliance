@php
    $chapterNum = $s['chapter_num'] ?? '06';
    $chapterLabel = $s['chapter_label'] ?? 'Knowledge Hub';
    $headingHtml = $s['heading_html'] ?? 'Knowledge assets around <em>behaviour change</em>.';
    $sideText = $s['side_text'] ?? 'Learning materials, evidence, toolkits and practitioner support — organised for teams across the alliance.';
    $resources = $s['resources'] ?? [
        [
            'link' => ['type' => 'route', 'name' => 'learning-corner'],
            'type' => 'Learning',
            'title' => 'Learning Corner',
            'meta' => 'Modules, videos, posters and training material',
            'aos_delay' => null,
            'icon' => 'book',
        ],
        [
            'link' => ['type' => 'route', 'name' => 'reports'],
            'type' => 'Evidence',
            'title' => 'Reports & Insights',
            'meta' => 'Reports, newsletters and success stories',
            'aos_delay' => 100,
            'icon' => 'chart',
        ],
        [
            'link' => ['type' => 'route', 'name' => 'programs'],
            'type' => 'Programs',
            'title' => 'Programs and Initiatives',
            'meta' => 'Flagship SBC initiatives across districts',
            'aos_delay' => 200,
            'icon' => 'target',
        ],
        [
            'link' => ['type' => 'route', 'name' => 'resources'],
            'type' => 'Practitioners',
            'title' => 'SBC Resource Pool',
            'meta' => 'Connect with trainers and field communicators',
            'aos_delay' => 300,
            'icon' => 'users',
        ],
    ];
    $libraryLink = $s['library_link'] ?? ['type' => 'route', 'name' => 'learning-corner'];
    $libraryLabel = $s['library_label'] ?? 'Open Learning Corner →';
    $libraryHref = match ($libraryLink['type'] ?? 'route') {
        'route' => route($libraryLink['name'] ?? 'learning-corner', $libraryLink['params'] ?? []),
        default => $libraryLink['url'] ?? route('learning-corner'),
    };
@endphp
<div class="champions-head">
  <div>
    <span class="chapter"><b>{{ $chapterNum }}</b> · {{ $chapterLabel }}</span>
    <h2 id="hub-h">{!! $headingHtml !!}</h2>
  </div>
  <p class="head-side">{{ $sideText }}</p>
</div>

<div class="hub-grid hub-grid-4" role="list">
  @foreach ($resources as $resource)
    @include('partials.home-hub-resource-card', ['resource' => $resource])
  @endforeach
</div>

<div class="hub-library-cta">
  <a class="btn btn-ghost" href="{{ $libraryHref }}">{{ $libraryLabel }}</a>
</div>
