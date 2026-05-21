@extends('layouts.app')

@section('title', $pageContent['meta_title'] ?? 'Events · ChhattisgarhABC')
@section('meta_description', $pageContent['meta_description'] ?? 'A calendar of upcoming and completed SBC events — workshops, trainings, webinars and conferences across Chhattisgarh. Register, attend, download recap reports.')

@php
  $board = $eventsBoard ?? ($pageSections['board'] ?? []);
  $past = $pageSections['past'] ?? [];
  $past['cards'] = $eventsPastCards ?? ($past['cards'] ?? []);
  $gallery = $pageSections['gallery'] ?? [];
  $gallery['tiles'] = $eventsGalleryTiles ?? ($gallery['tiles'] ?? []);
  $gallery['total'] = $eventsGalleryTotal ?? count($gallery['tiles']);
@endphp

@section('content')
<main id="main">

<section class="ev-board" id="ev-calendar" aria-labelledby="ev-board-h">
  <div class="container-x">
    <div class="ev-board-head">
      <span class="chapter"><b>{{ $board['chapter'] ?? '06' }}</b> · Dashboard</span>
      <h2 id="ev-board-h" data-aos="fade-up">{!! $board['title'] ?? '' !!}</h2>
      <p class="ev-board-sub" data-aos="fade-up" data-aos-delay="100">
        {{ $board['subtitle'] ?? '' }}
      </p>
    </div>

    <div class="ev-board-grid">

      <article class="ev-cal" id="ev-cal-widget" aria-label="Events calendar"
               data-calendar-api="{{ route('events.calendar-data') }}"
               data-init-month="{{ $latestEventMonth ?? now()->format('Y-m') }}">
        <header class="ev-cal-head">
          <div class="ev-cal-nav-row">
            <button type="button" class="ev-cal-nav-btn" id="ev-cal-prev" aria-label="Previous month">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
            </button>
            <span class="ev-cal-mo" id="ev-cal-mo">{{ isset($latestEventMonth) ? \Carbon\Carbon::createFromFormat('Y-m', $latestEventMonth)->format('F Y') : now()->format('F Y') }}</span>
            <button type="button" class="ev-cal-nav-btn" id="ev-cal-next" aria-label="Next month">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
            </button>
          </div>
          <div class="ev-cal-legend" aria-hidden="true">
            <span><i class="ev-dot ev-dot--workshop"></i>Workshop</span>
            <span><i class="ev-dot ev-dot--conf"></i>Conference</span>
            <span><i class="ev-dot ev-dot--webinar"></i>Webinar</span>
          </div>
        </header>

        <div class="ev-cal-grid" id="ev-cal-grid" role="grid">
          <div class="ev-cal-wd" role="columnheader">Mon</div>
          <div class="ev-cal-wd" role="columnheader">Tue</div>
          <div class="ev-cal-wd" role="columnheader">Wed</div>
          <div class="ev-cal-wd" role="columnheader">Thu</div>
          <div class="ev-cal-wd" role="columnheader">Fri</div>
          <div class="ev-cal-wd" role="columnheader">Sat</div>
          <div class="ev-cal-wd" role="columnheader">Sun</div>
          <div class="ev-cal-loading" id="ev-cal-loading">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="animation:ev-spin .8s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
          </div>
        </div>

        <footer class="ev-cal-foot">
          <span id="ev-cal-foot-text">Showing <b id="ev-cal-foot-month">{{ isset($latestEventMonth) ? \Carbon\Carbon::createFromFormat('Y-m', $latestEventMonth)->format('F Y') : now()->format('F Y') }}</b> · <b id="ev-cal-foot-count">0</b> events</span>
          <a href="#ev-timeline" class="btn-link">Open timeline →</a>
        </footer>
      </article>

      <article class="ev-tl" id="ev-timeline" aria-label="Event timeline">
        <header class="ev-tl-head">
          <span class="ev-tl-title">Timeline</span>
          <div class="ev-tl-filters" role="tablist">
            <button type="button" class="ev-tl-chip is-active" data-filter="all" role="tab" aria-selected="true">All</button>
            <button type="button" class="ev-tl-chip" data-filter="upcoming" role="tab" aria-selected="false">Upcoming</button>
            <button type="button" class="ev-tl-chip" data-filter="past" role="tab" aria-selected="false">Past</button>
          </div>
        </header>

        <ol class="ev-tl-rail" data-tl-list>
          @foreach ($board['timeline'] ?? [] as $item)
            @if (($item['status'] ?? '') === 'today')
              <li class="ev-tl-today" aria-hidden="true">
                <span>{{ $item['label'] }}</span>
              </li>
            @else
              <li class="ev-tl-item ev-tl-item--{{ $item['status'] }}{{ !empty($item['featured']) ? ' ev-tl-item--feature' : '' }}" data-status="{{ $item['status'] }}" data-date="{{ $item['date'] }}">
                <div class="ev-tl-stamp">
                  <b>{{ $item['day'] }}</b><small>{{ $item['month'] }}</small>
                </div>
                <div class="ev-tl-body">
                  <span class="ev-tl-tag" data-type="{{ $item['tag_type'] ?? '' }}">{{ $item['tag'] }}</span>
                  <h3>{{ $item['title'] }}</h3>
                  <p>{{ $item['description'] }}</p>
                  <a href="{{ $item['link'] ?? '#' }}" class="btn-link">{{ $item['link_text'] }}</a>
                </div>
              </li>
            @endif
          @endforeach
        </ol>
      </article>

    
    </div>
  </div>
</section>

<section class="ev-past" aria-labelledby="ev-past-h">
  <div class="container-x">
    <div class="ev-past-head">
      <span class="chapter"><b>{{ $past['chapter'] ?? '10' }}</b> · Completed events</span>
      <h2 id="ev-past-h" data-aos="fade-up">{!! $past['title'] ?? '' !!}</h2>
      <p class="ev-past-sub" data-aos="fade-up" data-aos-delay="100">
        {{ $past['subtitle'] ?? '' }}
      </p>
    </div>

    <div class="ev-past-grid">
      @foreach ($past['cards'] ?? [] as $card)
        <article class="ev-past-card" data-aos="fade-up" @if (!empty($card['aos_delay'])) data-aos-delay="{{ $card['aos_delay'] }}" @endif>
          <div class="ev-past-card-img" style="background-image:url('{{ $card['image'] }}');"></div>
          <div class="ev-past-card-body">
            <span class="ev-past-card-meta">{{ $card['meta'] }}</span>
            <h3>{{ $card['title'] }}</h3>
            <p>{{ $card['description'] }}</p>
            <ul class="ev-past-card-stats">
              @foreach ($card['stats'] ?? [] as $stat)
                <li><b>{{ $stat['value'] }}@if (!empty($stat['suffix']))<small>{{ $stat['suffix'] }}</small>@endif</b><span>{{ $stat['label'] }}</span></li>
              @endforeach
            </ul>
            <a href="{{ $card['url'] ?? '#ev-reports' }}" class="btn-link">{{ $card['link_text'] ?? 'View event →' }}</a>
          </div>
        </article>
      @endforeach
    </div>
  </div>
</section>

<section class="ev-gal" id="ev-gallery" aria-labelledby="ev-gal-h">
  <div class="container-x">
    <div class="ev-gal-head">
      <span class="chapter"><b>{{ $gallery['chapter'] ?? '11' }}</b> · Gallery</span>
      <h2 id="ev-gal-h" data-aos="fade-up">{!! $gallery['title'] ?? '' !!}</h2>
      <p class="ev-gal-sub" data-aos="fade-up" data-aos-delay="100">
        {{ $gallery['subtitle'] ?? '' }}
      </p>
    </div>

    <div class="ev-gal-grid" data-gal-grid>
      @foreach ($gallery['tiles'] ?? [] as $tile)
        <button type="button" class="ev-gal-tile {{ $tile['class'] }}" data-gal-i="{{ $tile['index'] }}" style="background-image:url('{{ $tile['image'] }}');" aria-label="{{ $tile['label'] }}"></button>
      @endforeach
    </div>

    <noscript>
      <p style="text-align:center; margin-top: var(--s-5); color: var(--ink-soft);">JavaScript is needed to view the lightbox. <a href="#">View all photos →</a></p>
    </noscript>
  </div>
</section>

<div class="ev-lb" data-gal-lb hidden role="dialog" aria-modal="true" aria-labelledby="ev-lb-cap">
  <div class="ev-lb-backdrop" data-gal-close></div>
  <button type="button" class="ev-lb-close" data-gal-close aria-label="Close gallery">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
  </button>
  <button type="button" class="ev-lb-nav ev-lb-nav--prev" data-gal-prev aria-label="Previous photo">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
  </button>
  <button type="button" class="ev-lb-nav ev-lb-nav--next" data-gal-next aria-label="Next photo">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
  </button>
  <figure class="ev-lb-figure">
    <img alt="" data-gal-img />
    <figcaption class="ev-lb-cap">
      <span id="ev-lb-cap" data-gal-cap>Photo</span>
      <span class="ev-lb-counter"><b data-gal-i>1</b> / <span data-gal-total>{{ $gallery['total'] ?? 9 }}</span></span>
    </figcaption>
  </figure>
</div>

</main>
@endsection
