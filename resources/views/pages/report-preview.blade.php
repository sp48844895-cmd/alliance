<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $reportTitle }} · Report Preview · ChhattisgarhABC</title>
  <meta name="description" content="{{ $reportTitle }} — live report preview." />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="{{ asset('assets/css/tokens.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/magazine.css') }}?v={{ filemtime(public_path('assets/css/magazine.css')) }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/report-preview.css') }}?v={{ filemtime(public_path('assets/css/report-preview.css')) }}" />
</head>
<body class="magazine-body report-preview-body">

<header class="magazine-bar" role="banner">
  <button type="button" class="magazine-bar__back js-report-viewer-back" data-fallback="{{ route('reports') }}" aria-label="Back to Reports and Insights">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M15 18l-6-6 6-6"/>
    </svg>
    <span>Reports</span>
  </button>

  <div class="magazine-bar__title">
    <span class="magazine-bar__type">{{ $reportType }}</span>
    <h1>{{ $reportTitle }}</h1>
  </div>

  <div class="magazine-bar__actions" aria-hidden="true"></div>
</header>

<main class="report-preview-stage">
  <iframe class="report-preview-frame" src="{{ $previewSrc }}" title="{{ $reportTitle }} preview"></iframe>
</main>

<script src="{{ asset('assets/js/report-viewer.js') }}?v={{ filemtime(public_path('assets/js/report-viewer.js')) }}"></script>

</body>
</html>
