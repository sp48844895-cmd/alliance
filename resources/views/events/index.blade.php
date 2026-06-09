@extends('layouts.app')

@section('title', $metaTitle ?? 'Events · ChhattisgarhABC')
@section('meta_description', $metaDescription ?? 'A calendar of upcoming and completed SBC events — workshops, trainings, webinars and conferences across Chhattisgarh. Register, attend, download recap reports.')

@section('content')
<main id="main">

<section class="ev-board" id="ev-calendar" aria-labelledby="ev-board-h">
  <div class="container-x">
    <div class="ev-board-head">
      <span class="chapter"><b>{{ $board['chapter'] ?? '06' }}</b> · Dashboard</span>
      <h2 id="ev-board-h" data-aos="fade-up">{!! $board['title'] ?? '' !!}</h2>
      <p class="ev-board-sub" data-aos="fade-up" data-aos-delay="100">
        {{ $board['subtitle'] ?? 'Browse upcoming and past events. Use the calendar to jump to a date.' }}
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
            <span class="ev-cal-mo" id="ev-cal-mo">{{ $calendarMonthLabel }}</span>
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
          <span id="ev-cal-foot-text">Showing <b id="ev-cal-foot-month">{{ $calendarMonthLabel }}</b> · <b id="ev-cal-foot-count">0</b> events</span>
          <a href="#ev-events-panel" class="btn-link">View events →</a>
        </footer>
      </article>

      <article class="ev-events-panel" id="ev-events-panel" aria-label="Events list">
        <header class="ev-events-head">
          <div class="ev-events-tabs" role="tablist" aria-label="Filter events">
            <button type="button" class="ev-events-tab is-active" data-tab="upcoming" role="tab" aria-selected="true" aria-controls="ev-tab-upcoming" id="ev-tab-btn-upcoming">Upcoming</button>
            <button type="button" class="ev-events-tab" data-tab="past" role="tab" aria-selected="false" aria-controls="ev-tab-past" id="ev-tab-btn-past">Past</button>
          </div>
        </header>

        <div class="ev-events-panels">
          <section class="ev-events-pane is-active" id="ev-tab-upcoming" data-tab-panel="upcoming" role="tabpanel" aria-labelledby="ev-tab-btn-upcoming">
            <div class="ev-events-pane-head">
              <h3 class="ev-events-pane-title">Upcoming Events</h3>
              <span class="ev-badge-coming">Coming Soon</span>
            </div>

            @if ($upcoming)
              <div class="ev-events-list ev-events-list--upcoming">
                @include('events.partials.event-card', ['card' => $upcoming, 'variant' => 'upcoming'])
              </div>
            @else
              <p class="ev-events-empty">No upcoming events are scheduled right now. Check back soon or browse past events.</p>
            @endif
          </section>

          <section class="ev-events-pane" id="ev-tab-past" data-tab-panel="past" role="tabpanel" aria-labelledby="ev-tab-btn-past" hidden>
            <div class="ev-events-pane-head">
              <h3 class="ev-events-pane-title">Past Events</h3>
            </div>

            @if (count($pastCards) > 0)
              <div class="ev-events-list ev-events-list--past">
                @foreach ($pastCards as $card)
                  @include('events.partials.event-card', ['card' => $card, 'variant' => 'past'])
                @endforeach
              </div>
            @else
              <p class="ev-events-empty">No completed events to show yet.</p>
            @endif
          </section>
        </div>
      </article>

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
      <p style="text-align:center; margin-top: var(--s-5); color: var(--ink-soft);">JavaScript is needed to view the lightbox.</p>
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
