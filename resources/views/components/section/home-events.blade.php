@props([
    'section' => [],
    'events' => [],
])

@php
    $s = $section;
    $eventItems = $events;
@endphp

@if (!empty($eventItems))
    <x-section.heading
        :chapter-num="$s['chapter_num'] ?? '05'"
        :chapter-label="$s['chapter_label'] ?? 'Events'"
        :heading-html="$s['heading_html'] ?? 'Where the alliance <em>meets</em>.'"
        :side-text="$s['side_text'] ?? 'Upcoming webinars, workshops and convenings from across Chhattisgarh, all in one quick events preview.'"
        heading-id="events-home-h"
    />

    <div class="initiatives-grid">
        @foreach ($eventItems as $item)
            <a href="{{ $item['url'] }}" class="{{ $item['tile_class'] }}" data-aos="fade-up"@if (!empty($item['aos_delay'])) data-aos-delay="{{ $item['aos_delay'] }}"@endif style="background-image:url('{{ $item['image'] }}');">
                <span class="tile-tag">{{ $item['tag'] }}</span>
                <span class="arrow-circle" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
                </span>
                <h4>{{ $item['title'] }}</h4>
                <p>{{ $item['description'] }}</p>
            </a>
        @endforeach
    </div>
@elseif (!empty($s['tiles']))
    <x-section.heading
        :chapter-num="$s['chapter_num'] ?? '05'"
        :chapter-label="$s['chapter_label'] ?? 'Events'"
        :heading-html="$s['heading_html'] ?? 'Where the alliance <em>meets</em>.'"
        :side-text="$s['side_text'] ?? ''"
        heading-id="events-home-h"
    />

    <div class="initiatives-grid">
        @foreach ($s['tiles'] as $tile)
            <a href="{{ route($s['events_link']['name'] ?? 'events') }}" class="tile {{ $tile['class'] ?? 'tile-1' }}" data-aos="fade-up"@if (!empty($tile['aos_delay'])) data-aos-delay="{{ $tile['aos_delay'] }}"@endif style="background-image:url('{{ $tile['image'] ?? '' }}');">
                <span class="tile-tag">{{ $tile['tag'] ?? '' }}</span>
                <span class="arrow-circle" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
                </span>
                <h4>{{ $tile['title'] ?? '' }}</h4>
                <p>{{ $tile['text'] ?? '' }}</p>
            </a>
        @endforeach
    </div>
@else
    @include('sections.defaults.home.events')
@endif
