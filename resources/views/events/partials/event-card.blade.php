@php
  $isUpcoming = ($variant ?? '') === 'upcoming';
@endphp

<article class="ev-event-card ev-event-card--{{ $variant ?? 'past' }}" data-date="{{ $card['date'] ?? '' }}" data-aos="fade-up" @if (!empty($card['aos_delay'])) data-aos-delay="{{ $card['aos_delay'] }}" @endif>
  <a href="{{ $card['url'] ?? '#' }}" class="ev-event-card-link">
    <div class="ev-event-card-media">
      <img src="{{ $card['image'] }}" alt="" width="400" height="250" loading="lazy" />
    </div>
    <div class="ev-event-card-body">
      <div class="ev-event-card-tags">
        <span class="ev-event-card-date">{{ $card['date_label'] ?? '' }}</span>
        <span class="ev-event-card-mode ev-event-card-mode--{{ strtolower($card['mode'] ?? 'offline') }}">{{ $card['mode'] ?? 'Offline' }}</span>
      </div>
      <h4 class="ev-event-card-title">{{ $card['title'] ?? '' }}</h4>
      @if ($isUpcoming)
        <span class="btn-link ev-event-card-cta">{{ $card['link_text'] ?? 'Register →' }}</span>
      @else
        <span class="btn-link ev-event-card-cta">{{ $card['link_text'] ?? 'View event →' }}</span>
      @endif
    </div>
  </a>
</article>
