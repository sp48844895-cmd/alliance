@extends('layouts.app')

@section('title', $event['title'].' · Events · ChhattisgarhABC')
@section('meta_description', $event['summary'])

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/event-details.css') }}?v={{ filemtime(public_path('assets/css/event-details.css')) }}">
@endpush

@section('content')
<main id="main" class="ed-page">
  <section class="ed-hero" aria-labelledby="ed-title">
    <div class="container-x">
      <a href="{{ route('events') }}" class="ed-back">← Back to events</a>

      <div class="ed-hero-grid">
        <div class="ed-copy">
          <span class="chapter"><b>05</b> · Event details</span>

          <div class="ed-chips">
            <span class="ed-chip ed-chip--status">{{ $event['status'] }}</span>
            @if (!empty($event['tag']))
              <span class="ed-chip">{{ $event['tag'] }}</span>
            @endif
            @if (!empty($event['location']))
              <span class="ed-chip">{{ $event['location'] }}</span>
            @endif
          </div>

          <h1 id="ed-title" class="ed-title">{{ $event['title'] }}</h1>

          <p class="ed-lede">{{ $event['summary'] }}</p>

          <div class="ed-cta">
            <a href="{{ $event['cta_link'] }}" class="btn btn-primary" target="{{ $event['cta_link'] === route(\App\Support\PageRoute::named('contact')) ? '_self' : '_blank' }}" rel="noreferrer">
              {{ $event['cta_label'] }}
              <svg class="arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('contact') }}" class="btn btn-ghost">Host with the alliance</a>
          </div>

          <div class="ed-meta">
            <div class="ed-meta-item">
              <span>Date</span>
              <strong>{{ $event['date_label'] }}</strong>
            </div>
            <div class="ed-meta-item">
              <span>Time</span>
              <strong>{{ $event['time_label'] }}</strong>
            </div>
            <div class="ed-meta-item">
              <span>Location</span>
              <strong>{{ $event['location'] }}</strong>
            </div>
          </div>
        </div>

        <figure class="ed-poster-wrap">
          <div class="ed-poster">
            <div class="ed-stamp" aria-hidden="true">
              <span class="ed-stamp-day">{{ $event['day_label'] }}</span>
              <span class="ed-stamp-month">{{ $event['month_label'] }}</span>
              <span class="ed-stamp-year">{{ $event['year_label'] }}</span>
            </div>
            <div class="ed-poster-image">
              @if (!empty($event['image_url']))
                <img src="{{ $event['image_url'] }}" alt="{{ $event['title'] }} event poster">
              @endif
            </div>
          </div>
          <figcaption class="ed-poster-caption">{{ $event['date_label'] }} · {{ $event['time_label'] }}</figcaption>
        </figure>
      </div>
    </div>
  </section>

  <section class="ed-body">
    <div class="container-x">
      <div class="ed-body-grid">
        <article class="ed-article">
          <div class="ed-section-head">
            <span class="chapter"><b>06</b> · Overview</span>
            <h2>One event page, everything <em>in one place.</em></h2>
          </div>

          <div class="ed-richtext">
            {!! $event['content'] !!}
          </div>

          <div class="ed-highlight-grid">
            <article class="ed-highlight-card">
              <span class="ed-highlight-no">01</span>
              <h3>Who it is for</h3>
              <p>Volunteers, field teams, partner organisations, educators and anyone following the alliance's work on behaviour change.</p>
            </article>
            <article class="ed-highlight-card">
              <span class="ed-highlight-no">02</span>
              <h3>What to expect</h3>
              <p>A focused session with field-grounded discussion, practical takeaways and documentation that can travel back into community work.</p>
            </article>
            <article class="ed-highlight-card">
              <span class="ed-highlight-no">03</span>
              <h3>What comes after</h3>
              <p>Follow-up resources, recap reporting and future event pathways that connect this gathering to the wider alliance calendar.</p>
            </article>
          </div>
        </article>

        <aside class="ed-sidebar">
          <div class="ed-panel">
            <span class="chapter"><b>07</b> · Snapshot</span>
            <div class="ed-facts">
              <div class="ed-fact">
                <span>Event status</span>
                <strong>{{ $event['status'] }}</strong>
              </div>
              @if (!empty($event['tag']))
                <div class="ed-fact">
                  <span>Theme</span>
                  <strong>{{ $event['tag'] }}</strong>
                </div>
              @endif
              <div class="ed-fact">
                <span>Scheduled for</span>
                <strong>{{ $event['date_label'] }}</strong>
              </div>
              <div class="ed-fact">
                <span>Starts at</span>
                <strong>{{ $event['time_label'] }}</strong>
              </div>
            </div>
          </div>

          <div class="ed-panel ed-panel--accent">
            <span class="chapter"><b>08</b> · Quick actions</span>
            <div class="ed-action-stack">
              <a href="{{ $event['cta_link'] }}" class="ed-action-link" target="{{ $event['cta_link'] === route(\App\Support\PageRoute::named('contact')) ? '_self' : '_blank' }}" rel="noreferrer">
                <b>{{ $event['cta_label'] }}</b>
                <span>Open the primary event action for this session.</span>
              </a>
              <a href="{{ route('events') }}" class="ed-action-link">
                <b>Browse more events</b>
                <span>Return to the full event calendar and archive.</span>
              </a>
              <a href="{{ route('contact') }}" class="ed-action-link">
                <b>Ask the alliance</b>
                <span>Need support, details or a district-level collaboration?</span>
              </a>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </section>

  @if (!empty($relatedEvents))
    <section class="ed-related" aria-labelledby="ed-related-title">
      <div class="container-x">
        <div class="ed-related-head">
          <span class="chapter"><b>09</b> · More from the calendar</span>
          <h2 id="ed-related-title">Related events</h2>
        </div>

        <div class="ed-related-grid">
          @foreach ($relatedEvents as $relatedEvent)
            <article class="ed-related-card">
              <a href="{{ route(\App\Support\PageRoute::named('events.show'), $relatedEvent['slug']) }}" class="ed-related-link">
                <div class="ed-related-media">
                  @if (!empty($relatedEvent['image_url']))
                  <img src="{{ $relatedEvent['image_url'] }}" alt="{{ $relatedEvent['title'] }}">
                  @endif
                </div>
                <div class="ed-related-copy">
                  <span class="ed-related-meta">{{ $relatedEvent['date_label'] }} · {{ $relatedEvent['location'] }}</span>
                  <h3>{{ $relatedEvent['title'] }}</h3>
                  <p>{{ $relatedEvent['summary'] }}</p>
                  <span class="ed-related-cta">Open event page →</span>
                </div>
              </a>
            </article>
          @endforeach
        </div>
      </div>
    </section>
  @endif
</main>
@endsection
