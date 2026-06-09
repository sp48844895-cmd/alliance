@extends('layouts.app')

@section('title', $metaTitle ?? 'Reports and Insights · ChhattisgarhABC')
@section('meta_description', $metaDescription ?? 'Reports, newsletters and success stories from ChhattisgarhABC documenting social and behaviour change work across Chhattisgarh.')

@section('content')
<main id="main">
  <section class="reports-section section-tight" aria-labelledby="reports-title">
    <div class="container-x">
      <div class="reports-grid">
        @foreach ($reports as $report)
          @php
            $hasFlipbook = ! empty($report['has_flipbook']);
            $previewTarget = '_self';
          @endphp
          <article class="report-card" data-aos="fade-up">
            <div class="report-card__header">
              <span>{{ $report['type'] }}</span>
              <h3>{{ $report['title'] }}</h3>
            </div>

            <a class="report-card__cover" href="{{ $report['preview_url'] }}" target="{{ $previewTarget }}" rel="{{ $hasFlipbook ? 'noopener' : '' }}" aria-label="Open {{ $report['title'] }} preview">
              @if ($report['cover_exists'])
                <img src="{{ asset($report['cover']) }}" alt="{{ $report['title'] }} cover" loading="lazy" />
              @else
                <span class="report-card__fallback">
                  <small>{{ $report['type'] }}</small>
                  <b>{{ $report['title'] }}</b>
                </span>
              @endif

              @if ($hasFlipbook)
                <span class="report-card__cover-flag">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 5a2 2 0 0 1 2-2h6v18H4a2 2 0 0 1-2-2Z"/><path d="M22 5a2 2 0 0 0-2-2h-6v18h6a2 2 0 0 0 2-2Z"/></svg>
                  Flipbook · {{ $report['flipbook_pages'] ?? 0 }} pages
                </span>
              @endif
            </a>

            <div class="report-card__footer">
              <a href="{{ $report['preview_url'] }}" target="{{ $previewTarget }}" rel="{{ $hasFlipbook ? 'noopener' : '' }}" @unless($report['preview_exists']) aria-disabled="true" @endunless>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                {{ $hasFlipbook ? 'Read Magazine' : 'Live Preview' }}
              </a>
              <a href="{{ $report['download_url'] }}" @if($report['download_exists']) download @endif @unless($report['download_exists']) aria-disabled="true" @endunless>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/></svg>
                Download
              </a>
              <a href="mailto:?subject={{ rawurlencode($report['title']) }}&body={{ rawurlencode($report['preview_url']) }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.6 10.5 6.8-4"/><path d="m8.6 13.5 6.8 4"/></svg>
                Share
              </a>
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>
</main>
@endsection
