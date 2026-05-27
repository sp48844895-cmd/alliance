@php
  $categoryLabels = [
      'research' => 'Research',
      'iec' => 'IEC Materials',
      'toolkit' => 'Toolkit',
      'guide' => 'Guide',
      'report' => 'Report',
      'data' => 'Data',
  ];
  $categoryLabel = $categoryLabels[$resource['category']] ?? ucfirst($resource['category']);
@endphp

<article class="kh-card{{ !empty($resource['ribbon']) ? ' kh-card--canva' : '' }}"
         data-kh-card data-category="{{ $resource['category'] }}"
         data-keywords="{{ $resource['keywords'] }}"
         data-kh-id="{{ $resource['id'] }}"
         data-kh-cover="{{ $resource['cover'] }}"
         data-kh-title="{{ $resource['title'] }}"
         data-kh-desc="{{ $resource['description'] ?? $resource['detail']['description'] ?? '' }}"
         data-kh-format="{{ $resource['format'] }}"
         data-kh-size="{{ $resource['size'] }}"
         data-kh-pages="{{ $resource['pages'] }}"
         data-kh-langs="{{ $resource['langs'] }}"
         data-kh-published="{{ $resource['published'] }}"
         data-kh-downloads="{{ $resource['downloads'] }}"
         data-kh-canva="{{ !empty($resource['canva']) ? 'true' : 'false' }}"
         @if (!empty($resource['canva_url'])) data-kh-canva-url="{{ $resource['canva_url'] }}" @endif>
  @if (!empty($resource['ribbon']))
    <span class="kh-canva-ribbon">Canva editable</span>
  @endif
  <div class="kh-card-body">
    <header>
      <span class="kh-tag" data-cat="{{ $resource['category'] }}">{{ $categoryLabel }}</span>
      <span class="kh-fmt" data-fmt="{{ $resource['format'] }}">{{ $resource['format'] }}</span>
    </header>
    <h3>{{ $resource['title'] }}</h3>
    <p>{{ $resource['description'] }}</p>
    <ul class="kh-card-meta">
      @foreach ($resource['meta'] ?? [] as $metaLine)
        <li>
          @if (is_array($metaLine))
            <b>{{ $metaLine['value'] ?? '' }}</b> {{ $metaLine['label'] ?? '' }}
          @else
            {{ $metaLine }}
          @endif
        </li>
      @endforeach
    </ul>
    <footer>
      <button type="button" class="btn-link" data-kh-open="{{ $resource['id'] }}">Preview →</button>
      <a href="{{ $resource['download_url'] ?? '#' }}" class="kh-dl" aria-label="{{ !empty($resource['canva']) ? 'Open ' . $resource['title'] . ' on Canva' : 'Download ' . $resource['title'] }}" @if (!empty($resource['download_url']) && ($resource['download_url'] ?? '#') !== '#') target="_blank" rel="noopener" @endif>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          @if (!empty($resource['canva']))
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/>
          @else
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/>
          @endif
        </svg>
      </a>
    </footer>
  </div>
</article>
