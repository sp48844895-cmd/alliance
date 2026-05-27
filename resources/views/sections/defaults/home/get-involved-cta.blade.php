@php
    $chapterNum = $s['chapter_num'] ?? '07';
    $chapterLabel = $s['chapter_label'] ?? 'Get Involved';
    $headingHtml = $s['heading_html'] ?? 'Join the <em>Alliance</em> for behaviour change.';
    $ledeHtml = $s['lede_html'] ?? 'Volunteer, partner as an NGO or organisation, or contribute as a professional — four clear pathways to strengthen community-led SBC across Chhattisgarh.';
    $buttonLabel = $s['button_label'] ?? 'Explore ways to join';
    $buttonLink = $s['button_link'] ?? ['type' => 'route', 'name' => 'get-involved'];
    $buttonHref = match ($buttonLink['type'] ?? 'route') {
        'route' => route($buttonLink['name'] ?? 'get-involved', $buttonLink['params'] ?? []),
        default => $buttonLink['url'] ?? route('get-involved'),
    };
@endphp
<div class="container-x">
  <div class="home-get-involved-grid">
    <div>
      <span class="chapter" style="color:rgba(250,248,255,0.7);"><b style="color:var(--ochre);">{{ $chapterNum }}</b> · {{ $chapterLabel }}</span>
      <h2 id="get-involved-h">{!! $headingHtml !!}</h2>
      <p class="cta-lede">{!! $ledeHtml !!}</p>
      <a class="btn btn-primary home-get-involved-btn" href="{{ $buttonHref }}">
        {{ $buttonLabel }}
        <svg class="arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
      </a>
    </div>
    <div class="home-get-involved-paths">
      <a href="{{ route('get-involved') }}#gi-volunteer" class="cta-path">
        <b>Volunteer</b>
        <span>Support campaigns and field circles</span>
        <svg class="arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
      </a>
      <a href="{{ route('get-involved') }}#gi-intern" class="cta-path">
        <b>Intern</b>
        <span>Communications, research and programme support</span>
        <svg class="arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
      </a>
      <a href="{{ route('get-involved') }}#gi-fellow" class="cta-path">
        <b>Fellowship</b>
        <span>Structured learning and district-level impact</span>
        <svg class="arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
      </a>
      <a href="{{ route('get-involved') }}#gi-partner" class="cta-path">
        <b>NGO / organisation</b>
        <span>Co-design campaigns and scale behaviour change</span>
        <svg class="arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
      </a>
    </div>
  </div>
</div>
