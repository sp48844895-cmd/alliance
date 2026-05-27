@props([
    'section' => [],
    'stories' => [],
])

@php
    $s = $section;
    $storyItems = !empty($stories) ? $stories : ($s['items'] ?? []);
    $storiesUrl = route('stories');
    $storiesLinkLabel = $s['stories_link_label'] ?? 'Read all stories →';
@endphp

@if (!empty($storyItems))
    <div class="container-x">
        <x-section.heading
            :chapter-num="$s['chapter_num'] ?? '04'"
            :chapter-label="$s['chapter_label'] ?? 'Recent Stories'"
            :heading-html="$s['heading_html'] ?? 'Voices from the <em>field</em>.'"
            :side-text="$s['side_text'] ?? 'A rolling chronicle of behaviour-change work — campaigns, milestones, and human stories from across Chhattisgarh.'"
            heading-id="ch-h"
        />

        <div id="home-stories-carousel" class="champions-swiper swiper" data-aos="fade-up">
            <div class="swiper-wrapper">
                @foreach ($storyItems as $item)
                    @php
                        $url = $item['url'] ?? route('stories');
                        $image = $item['image'] ?? '';
                    @endphp
                    <article class="swiper-slide champion">
                        <div class="champion-photo" style="background-image:url('{{ $image }}');">
                            <div class="champion-meta">
                                @if (!empty($item['location']) || !empty($item['pill']))
                                    <span class="role-pill story-location-pill"@if (!empty($item['pill_style'])) style="{{ $item['pill_style'] }}"@endif>{{ $item['location'] ?? $item['pill'] }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="champion-body">
                            @if (!empty($item['where']))
                                <div class="champion-where">{{ $item['where'] }}</div>
                            @endif
                            <h4 class="champion-name">
                                @if ($url)
                                    <a href="{{ $url }}">{{ $item['title'] ?? '' }}</a>
                                @else
                                    {{ $item['title'] ?? '' }}
                                @endif
                            </h4>
                            <p class="champion-blurb">{{ $item['blurb'] ?? '' }}</p>
                            @if (!empty($item['author']))
                                <p class="champion-authored">{{ $item['authored_by'] ?? 'Authored by '.$item['author'] }}</p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="champions-controls">
            <div class="nav-arrows">
                <button class="swiper-arrow champ-prev" aria-label="Previous story">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                </button>
                <button class="swiper-arrow champ-next" aria-label="Next story">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>
            </div>
            <div class="swiper-pagination-bar" aria-hidden="true"><span></span></div>
            <a class="btn-link" href="{{ $storiesUrl }}">{{ $storiesLinkLabel }}</a>
        </div>
    </div>
@else
    @include('sections.defaults.home.champions')
@endif
