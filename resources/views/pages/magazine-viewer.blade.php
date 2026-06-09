<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $magazineTitle }} · ChhattisgarhABC</title>
  <meta name="description" content="{{ $magazineTitle }} — interactive flipbook viewer." />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,400;1,500;1,700&family=Roboto+Mono:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="{{ asset('assets/css/tokens.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/magazine.css') }}?v={{ filemtime(public_path('assets/css/magazine.css')) }}" />
</head>
<body class="magazine-body">

<header class="magazine-bar" role="banner">
  <button type="button" class="magazine-bar__back js-report-viewer-back" data-fallback="{{ route('reports') }}" aria-label="Back to Reports and Insights">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M15 18l-6-6 6-6"/>
    </svg>
    <span>Reports</span>
  </button>

  <div class="magazine-bar__title">
    <span class="magazine-bar__type">{{ $magazineType }}</span>
    <h1>{{ $magazineTitle }}</h1>
  </div>

  <div class="magazine-bar__actions">
    @if (! empty($magazineDownload))
      <a class="magazine-bar__action" href="{{ $magazineDownload }}" download>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/>
        </svg>
        <span>Download PDF</span>
      </a>
    @endif
    <button type="button" class="magazine-bar__action magazine-bar__action--fs" id="magazineFullscreen" aria-label="Toggle fullscreen">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M3 9V3h6"/><path d="M21 9V3h-6"/><path d="M3 15v6h6"/><path d="M21 15v6h-6"/>
      </svg>
    </button>
  </div>
</header>

<main class="magazine-stage" id="magazineStage">
  <button type="button" class="magazine-nav magazine-nav--prev" id="magazinePrev" aria-label="Previous page">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M15 18l-6-6 6-6"/>
    </svg>
  </button>

  <div class="magazine-book-wrap">
    <div id="magazineBook" class="magazine-book" aria-label="{{ $magazineTitle }} flipbook">
      @foreach ($magazinePages as $index => $src)
        <div class="magazine-page">
          <img src="{{ $src }}" alt="Page {{ $index + 1 }}" loading="{{ $index < 4 ? 'eager' : 'lazy' }}" draggable="false" />
        </div>
      @endforeach
    </div>
  </div>

  <button type="button" class="magazine-nav magazine-nav--next" id="magazineNext" aria-label="Next page">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M9 18l6-6-6-6"/>
    </svg>
  </button>
</main>

<footer class="magazine-footer" role="contentinfo">
  <div class="magazine-pager">
    <span id="magazinePagerCurrent">1</span>
    <span class="magazine-pager__sep">/</span>
    <span id="magazinePagerTotal">{{ count($magazinePages) }}</span>
  </div>

  <div class="magazine-slider-wrap">
    <input
      type="range"
      class="magazine-slider"
      id="magazineSlider"
      min="1"
      max="{{ count($magazinePages) }}"
      value="1"
      step="1"
      aria-label="Jump to page"
    />
  </div>

  <div class="magazine-pager magazine-pager--ghost" aria-hidden="true">
    <span>{{ count($magazinePages) }}</span>
    <span class="magazine-pager__sep">/</span>
    <span>{{ count($magazinePages) }}</span>
  </div>
</footer>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('assets/js/lib/turn.min.js') }}"></script>
<script>
  window.MAGAZINE_TOTAL = {{ count($magazinePages) }};
</script>
<script src="{{ asset('assets/js/magazine.js') }}?v={{ filemtime(public_path('assets/js/magazine.js')) }}"></script>
<script src="{{ asset('assets/js/report-viewer.js') }}?v={{ filemtime(public_path('assets/js/report-viewer.js')) }}"></script>

</body>
</html>
