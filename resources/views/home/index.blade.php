@extends('layouts.app')

@section('title', 'ChhattisgarhABC · Alliance for Behaviour Change Chhattisgarh')
@section('meta_description', 'ChhattisgarhABC is a community platform where youth, professionals, civil society and
    government come together to advance Social and Behaviour Change Communication across Chhattisgarh.')

@section('content')
    <main id="main" class="home-page">

        <section class="hero has-video{{ $hasBanners ? ' hero-has-banners' : '' }}">
            @if ($hasBanners)
                <div id="home-banner-carousel" class="hero-banner-swiper hero-banner-swiper--bg swiper"
                    aria-label="Featured banners">
                    <div class="swiper-wrapper">
                        @foreach ($heroCarouselSlides as $banner)
                            <div class="swiper-slide"
                                data-title="{{ $banner['title'] }}"
                                data-description="{{ $banner['short_description'] }}"
                                data-small-title="{{ $banner['small_title'] }}"
                                data-card-image="{{ $banner['card_image_url'] }}"
                                data-card-url="{{ $banner['card_url'] ?? '' }}"
                                data-card-external="{{ ! empty($banner['is_external']) ? '1' : '0' }}">
                                <div class="hero-banner-slide">
                                    <picture>
                                        <source media="(max-width: 767px)" srcset="{{ $banner['mobile_url'] }}">
                                        <img src="{{ $banner['desktop_url'] }}" alt="" width="1920" height="822"
                                            loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async">
                                    </picture>
                                </div>
                            </div>
                        @endforeach
            </div>
            </div>
        @else
            <video class="hero-video" autoplay muted loop playsinline preload="auto" poster="{{ $hero['video_poster'] }}"
                aria-hidden="true">
                <source src="{{ asset($hero['video_src']) }}" type="video/mp4" />
            </video>
            @endif
            <div class="hero-overlay" aria-hidden="true"></div>

            <div class="hero-deco-1" aria-hidden="true">
                <svg viewBox="0 0 220 220" xmlns="http://www.w3.org/2000/svg">
                    <g fill="none" stroke="#5d2cb5" stroke-width="1.5" opacity="0.7">
                        <circle cx="110" cy="110" r="100" />
                        <circle cx="110" cy="110" r="72" />
                        <circle cx="110" cy="110" r="46" />
                        <circle cx="110" cy="110" r="22" />
                    </g>
                    <g fill="#ff6b35">
                        <circle cx="110" cy="10" r="4" />
                        <circle cx="210" cy="110" r="4" />
                        <circle cx="110" cy="210" r="4" />
                        <circle cx="10" cy="110" r="4" />
                    </g>
                </svg>
            </div>
            <div class="hero-deco-2" aria-hidden="true">
                <svg viewBox="0 0 280 200" xmlns="http://www.w3.org/2000/svg">
                    <g fill="#3237f0" opacity="0.4">
                        <circle cx="20" cy="100" r="3" />
                        <circle cx="50" cy="100" r="3" />
                        <circle cx="80" cy="100" r="3" />
                        <circle cx="110" cy="100" r="3" />
                        <circle cx="140" cy="100" r="3" />
                        <circle cx="170" cy="100" r="3" />
                        <circle cx="200" cy="100" r="3" />
                        <circle cx="230" cy="100" r="3" />
                        <circle cx="260" cy="100" r="3" />
                    </g>
                    <g fill="none" stroke="#3237f0" stroke-width="1.2" opacity="0.45">
                        <path
                            d="M20 100 L40 70 L60 100 L80 70 L100 100 L120 70 L140 100 L160 70 L180 100 L200 70 L220 100 L240 70 L260 100" />
                        <path
                            d="M20 130 L40 160 L60 130 L80 160 L100 130 L120 160 L140 130 L160 160 L180 130 L200 160 L220 130 L240 160 L260 130" />
                    </g>
                </svg>
            </div>

            <div class="container-x">
                <div class="hero-grid">
                    <div class="hero-banner-copy" id="hero-banner-copy">
                        @if ($hasBanners)
                            @if (($heroCarouselSlides[0]['small_title'] ?? '') !== '')
                                <span class="chapter fade-up" data-delay="1" id="hero-banner-chapter">{{ $heroCarouselSlides[0]['small_title'] }}</span>
                            @endif
                            <h1 class="hero-banner-title fade-up" data-delay="2" id="hero-banner-title">{{ $heroCarouselSlides[0]['title'] }}</h1>
                            <p class="hero-lede type-lede hero-banner-lede fade-up" data-delay="3" id="hero-banner-lede">{{ $heroCarouselSlides[0]['short_description'] }}</p>
                        @else
                            @if (($hero['chapter_label'] ?? '') !== '')
                                <span class="chapter fade-up" data-delay="1">{{ $hero['chapter_label'] }}</span>
                            @endif
                            <h1 class="fade-up" data-delay="2">{!! $hero['headline_html'] !!}</h1>
                            <p class="hero-lede type-lede fade-up" data-delay="3">{!! $hero['lede_html'] !!}</p>
                        @endif
                    </div>
                    <div class="hero-impact-banner fade-up" data-delay="3" id="hero-banner-media">
                        <div class="hero-impact-banner__media" id="hero-banner-card-wrap">
                            @php
                                $firstCardUrl = $hasBanners ? ($heroCarouselSlides[0]['card_url'] ?? null) : null;
                                $firstCardExternal = $hasBanners && ! empty($heroCarouselSlides[0]['is_external']);
                            @endphp
                            @if ($firstCardUrl)
                                <a href="{{ $firstCardUrl }}" id="hero-banner-card-link" class="hero-impact-banner__link"@if ($firstCardExternal) target="_blank" rel="noopener noreferrer" @endif>
                            @endif
                            <img class="hero-impact-banner__bg" id="hero-banner-card-image"
                                src="{{ $hasBanners ? $heroCarouselSlides[0]['card_image_url'] : $hero['impact_banner']['background_url'] }}"
                                alt="" loading="lazy" decoding="async">
                            @if ($firstCardUrl)
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if ($hasBanners && count($heroCarouselSlides) > 1)
                <div class="hero-banner-controls hero-banner-controls--bg">
                    <button type="button" class="swiper-arrow hero-banner-prev" aria-label="Previous banner">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 12H5M12 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button type="button" class="swiper-arrow hero-banner-next" aria-label="Next banner">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </button>
                    <div class="hero-banner-pagination swiper-pagination"></div>
                </div>
            @endif
        </section>

        <section class="intro container-x" aria-labelledby="intro-h">
            <div class="intro-head">
                <div>
                    <h2 id="intro-h" class="hashtag-heading" data-aos="fade-up"><span
                            class="hashtag-mark">#</span>{{ $intro['hashtag'] }}</h2>
                </div>
                <p data-aos="fade-up" data-aos-delay="120">{{ $intro['lede'] }}</p>
            </div>

            <div class="pillars">
                @foreach ($intro['pillars'] as $pillar)
                    <article class="pillar"
                        data-aos="fade-up"@if (($pillar['aos_delay'] ?? null) !== null) data-aos-delay="{{ $pillar['aos_delay'] }}" @endif>
                        <div class="pillar-icon" aria-hidden="true">
                            @if (($pillar['icon'] ?? 'chart') === 'target')
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 2v20M2 12h20" />
                                    <circle cx="12" cy="12" r="9" />
                                </svg>
                            @elseif (($pillar['icon'] ?? 'chart') === 'eye')
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M3 12c2-4 6-6 9-6s7 2 9 6c-2 4-6 6-9 6s-7-2-9-6Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            @elseif (($pillar['icon'] ?? 'chart') === 'users')
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="9" cy="7" r="4" />
                                    <circle cx="17" cy="11" r="3" />
                                    <path d="M2 21c0-3 3-6 7-6s7 3 7 6M14 21c0-2 2-4 4-4s4 2 4 4" />
                                </svg>
                            @else
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M3 3v18h18" />
                                    <path d="M7 14l4-4 3 3 5-6" />
                                </svg>
                            @endif
                        </div>
                        <span class="ch-num">{{ $pillar['num'] }}</span>
                        <h4>{{ $pillar['title'] }}</h4>
                        <p>{{ $pillar['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        @if (count($homeSliderSlides) > 0)
            @include('home.partials.investcg-slider')
        @endif

        @if ($programsUseSlider)
            <section class="programs-section" id="programs-initiatives" aria-labelledby="programs-h">
                <div class="container-x">
                    <div class="programs-head programs-head--center-slider">
                        <div>
                            @if (($programsHeader['chapter_label'] ?? '') !== '')
                            <span class="chapter">{{ $programsHeader['chapter_label'] }}</span>
                            @endif
                            <h2 id="programs-h">{!! $programsHeader['heading_html'] !!}</h2>
                        </div>
                        <div class="programs-center-controls">
                            <button id="programs-center-prev" class="programs-center-nav-btn" type="button"
                                aria-label="Previous program">‹</button>
                            <button id="programs-center-next" class="programs-center-nav-btn" type="button"
                                aria-label="Next program">›</button>
                        </div>
                    </div>

                    <p class="programs-center-lede">{{ $programsHeader['lede_text'] }}</p>

                    <div class="programs-center-slider">
                        <div class="programs-center-track" id="programs-center-track">
                            @foreach ($programsCards as $card)
                                <article class="programs-center-card" @if ($card['is_active']) active @endif
                                    data-program-card>
                                    @if ($card['image_url'] !== '')
                                        <img class="programs-center-card__bg" src="{{ $card['image_url'] }}"
                                            alt="{{ $card['title'] }}" loading="lazy" decoding="async">
                                    @endif
                                    <div class="programs-center-card__content">
                                        @if ($card['image_url'] !== '')
                                            <img class="programs-center-card__thumb" src="{{ $card['image_url'] }}"
                                                alt="{{ $card['title'] }}" loading="lazy" decoding="async">
                                        @endif
                                        <div class="programs-center-card__copy">
                                            <h3 class="programs-center-card__title">{{ $card['title'] }}</h3>
                                            <p class="programs-center-card__desc">{{ $card['description'] }}</p>
                                            <a href="{{ $programsDetailsUrl }}"
                                                class="programs-center-card__btn">Details</a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <div class="programs-center-dots" id="programs-center-dots"></div>

                    <div class="programs-explore" data-aos="fade-up">
                        <a href="{{ $programsHeader['explore_url'] }}" class="btn btn-primary programs-explore__btn">
                            {{ $programsHeader['explore_label'] }}
                            <span class="arrow" aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>
            </section>
        @endif

        @if (count($stories) > 0)
            <section class="champions-section" aria-labelledby="ch-h">
                <div class="container-x">
                    <div class="champions-head">
                        <div>
                            <span class="chapter">Recent Stories</span>
                            <h2 id="ch-h" class="type-section-title">Voices from the <em>field</em>.</h2>
                        </div>
                        <p class="head-side">A rolling chronicle of behaviour-change work from across Chhattisgarh.</p>
                    </div>
                    <div id="home-stories-carousel" class="champions-swiper swiper">
                        <div class="swiper-wrapper">
                            @foreach ($stories as $story)
                                <article class="swiper-slide champion">
                                    <div class="champion-photo" style="background-image:url('{{ $story['image'] }}');">
                                        <div class="champion-meta">
                                            <span class="role-pill story-location-pill">{{ $story['location'] }}</span>
                                        </div>
                                    </div>
                                    <div class="champion-body">
                                        <h4 class="champion-name">
                                            <a href="{{ $story['url'] }}">{{ $story['title'] }}</a>
                                        </h4>
                                        <p class="champion-blurb">{{ $story['description'] }}</p>
                                        <p class="champion-authored">Authored by {{ $story['author'] }}</p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                    <div class="champions-controls">
                        <a class="btn-link" href="{{ $storiesUrl }}">Read all stories →</a>
                    </div>
                </div>
            </section>
        @endif

        @if (count($events) > 0)
            <section class="container-x section home-events-section" aria-labelledby="events-home-h">
                <div class="champions-head">
                    <div>
                        <span class="chapter">Events</span>
                        <h2 id="events-home-h" class="type-section-title">Where the alliance <em>meets</em>.</h2>
                    </div>
                    <p class="head-side">Upcoming webinars, workshops and convenings from across Chhattisgarh.</p>
                </div>
                <div class="initiatives-grid home-events-grid">
                    @foreach ($events as $event)
                        <a href="{{ $event['url'] }}" class="{{ $event['tile_class'] }}"
                            style="background-image:url('{{ $event['image'] }}');">
                            <span class="tile-tag">{{ $event['tag'] }}</span>
                            <h4>{{ $event['title'] }}</h4>
                            <p>{{ $event['description'] }}</p>
                        </a>
                    @endforeach
                </div>
                <div class="programs-explore">
                    <a href="{{ $eventsUrl }}" class="btn btn-primary">View all events →</a>
                </div>
            </section>
        @endif

        <section class="hub container-x" aria-labelledby="hub-h">
            <div class="champions-head">
                <div>
                    @if (($knowledgeHub['chapter_label'] ?? '') !== '')
                    <span class="chapter">{{ $knowledgeHub['chapter_label'] }}</span>
                    @endif
                    <h2 id="hub-h">{!! $knowledgeHub['heading_html'] !!}</h2>
                </div>
                <p class="head-side">{{ $knowledgeHub['side_text'] }}</p>
            </div>

            <div class="hub-grid hub-grid-4" role="list">
                @foreach ($knowledgeHub['resources'] as $resource)
                    <a href="{{ $resource['url'] }}" class="resource"
                        data-aos="fade-up"@if ($resource['aos_delay'] !== null) data-aos-delay="{{ $resource['aos_delay'] }}" @endif>
                        <div class="resource-icon" aria-hidden="true">
                            @if ($resource['icon'] === 'book')
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M4 4h12a4 4 0 0 1 4 4v12H8a4 4 0 0 1-4-4z" />
                                    <path d="M4 4v12a4 4 0 0 0 4 4" />
                                </svg>
                            @elseif ($resource['icon'] === 'chart')
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M3 3v18h18" />
                                    <path d="M7 14l4-4 3 3 5-6" />
                                </svg>
                            @elseif ($resource['icon'] === 'users')
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                            @else
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M2 7l10-4 10 4-10 4z" />
                                    <path d="M2 7v6c0 .5 4 4 10 4s10-3.5 10-4V7" />
                                </svg>
                            @endif
                        </div>
                        <span class="resource-type">{{ $resource['type'] }}</span>
                        <h4>{{ $resource['title'] }}</h4>
                        <div class="resource-meta">
                            <span>{{ $resource['meta'] }}</span>
                            <span aria-hidden="true">→</span>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="hub-library-cta">
                <a class="btn btn-ghost"
                    href="{{ $knowledgeHub['library_url'] }}">{{ $knowledgeHub['library_label'] }}</a>
            </div>
        </section>

        <section class="cta home-get-involved" id="home-get-involved" aria-labelledby="get-involved-h">
            <div class="container-x">
                <div class="home-get-involved-grid">
                    <div>
                        @if (($getInvolved['chapter_label'] ?? '') !== '')
                        <span class="chapter" style="color:rgba(250,248,255,0.7);">{{ $getInvolved['chapter_label'] }}</span>
                        @endif
                        <h2 id="get-involved-h">{!! $getInvolved['heading_html'] !!}</h2>
                        <p class="cta-lede">{!! $getInvolved['lede_html'] !!}</p>
                        <a class="btn btn-primary home-get-involved-btn" href="{{ $getInvolved['button_url'] }}">
                            {{ $getInvolved['button_label'] }}
                            <svg class="arrow" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="M5 12h14M13 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    <div class="home-get-involved-paths">
                        @foreach ($getInvolved['paths'] as $path)
                            <a href="{{ $path['url'] }}" class="cta-path">
                                <b>{{ $path['title'] }}</b>
                                <span>{{ $path['description'] }}</span>
                                <svg class="arrow" width="22" height="22" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 12h14M13 5l7 7-7 7" />
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

    </main>

    @push('scripts')
        @if (count($homeSliderSlides) > 0)
            <script src="{{ asset('assets/js/investcg-slider.js') }}?v={{ filemtime(public_path('assets/js/investcg-slider.js')) }}"></script>
        @endif
        @if ($programsUseSlider)
            <script src="{{ asset('assets/js/programs-center-slider.js') }}?v={{ filemtime(public_path('assets/js/programs-center-slider.js')) }}"></script>
        @endif
    @endpush
@endsection
