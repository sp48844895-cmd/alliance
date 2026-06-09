@extends('layouts.app')

@section('title', $pageContent['meta_title'] ?? 'ChhattisgarhABC · Alliance for Behaviour Change Chhattisgarh')
@section('meta_description', $pageSections['meta']['meta_description'] ?? 'ChhattisgarhABC is a community platform where youth, professionals, civil society and government come together to share experiences and advance Social and Behaviour Change Communication (SBC) across Chhattisgarh.')

@section('content')
<main id="main" class="home-page">

{{-- Section: hero --}}
@php
    $s = $pageSections['hero'] ?? [];
    $banners = $homeBanners ?? collect();
    $hasBanners = $banners->isNotEmpty();
    $tag = $s['panel_tag'] ?? ['kicker' => 'District coverage', 'value' => '33/33', 'small' => 'Districts engaged'];
    $stat = $s['panel_stat'] ?? ['kicker' => 'Beneficiaries reached', 'value' => '1.4M', 'small' => 'Across households, schools and panchayats'];
@endphp
<section class="hero has-video{{ $hasBanners ? ' hero-has-banners' : '' }}">
  @if ($hasBanners)
  <div id="home-banner-carousel" class="hero-banner-swiper hero-banner-swiper--bg swiper" aria-label="Featured banners">
    <div class="swiper-wrapper">
      @foreach ($banners as $banner)
      <div class="swiper-slide">
        @if ($banner->href)
        <a href="{{ $banner->href }}" class="hero-banner-slide"@if ($banner->is_external) target="_blank" rel="noopener noreferrer"@endif>
        @else
        <div class="hero-banner-slide">
        @endif
          <picture>
            <source media="(max-width: 767px)" srcset="{{ $banner->mobile_url }}">
            <img src="{{ $banner->desktop_url }}" alt="" width="1920" height="822" loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async">
          </picture>
        @if ($banner->href)
        </a>
        @else
        </div>
        @endif
      </div>
      @endforeach
    </div>
  </div>
  @if ($banners->count() > 1)
  <div class="hero-banner-controls hero-banner-controls--bg">
    <button type="button" class="swiper-arrow hero-banner-prev" aria-label="Previous banner">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </button>
    <button type="button" class="swiper-arrow hero-banner-next" aria-label="Next banner">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </button>
    <div class="hero-banner-pagination swiper-pagination"></div>
  </div>
  @endif
  @else
  <video class="hero-video" autoplay muted loop playsinline preload="auto"
         poster="{{ $s["video_poster"] ?? "https://www.chhattisgarhabc.org/stories/uploads/banner/1714379236.png" }}"
         aria-hidden="true">
    <source src="{{ asset($s["video_src"] ?? "assets/videos/hero.mp4") }}" type="video/mp4" />
    <source src="{{ $s["video_fallback"] ?? "https://videos.pexels.com/video-files/3045163/3045163-hd_1920_1080_30fps.mp4" }}" type="video/mp4" />
  </video>
  @endif
  <div class="hero-overlay" aria-hidden="true"></div>

  <div class="hero-deco-1" aria-hidden="true">
    <svg viewBox="0 0 220 220" xmlns="http://www.w3.org/2000/svg">
      <g fill="none" stroke="#5d2cb5" stroke-width="1.5" opacity="0.7">
        <circle cx="110" cy="110" r="100"/>
        <circle cx="110" cy="110" r="72"/>
        <circle cx="110" cy="110" r="46"/>
        <circle cx="110" cy="110" r="22"/>
      </g>
      <g fill="#ff6b35">
        <circle cx="110" cy="10" r="4"/>
        <circle cx="210" cy="110" r="4"/>
        <circle cx="110" cy="210" r="4"/>
        <circle cx="10" cy="110" r="4"/>
      </g>
    </svg>
  </div>
  <div class="hero-deco-2" aria-hidden="true">
    <svg viewBox="0 0 280 200" xmlns="http://www.w3.org/2000/svg">
      <g fill="#3237f0" opacity="0.4">
        <circle cx="20"  cy="100" r="3"/><circle cx="50"  cy="100" r="3"/>
        <circle cx="80"  cy="100" r="3"/><circle cx="110" cy="100" r="3"/>
        <circle cx="140" cy="100" r="3"/><circle cx="170" cy="100" r="3"/>
        <circle cx="200" cy="100" r="3"/><circle cx="230" cy="100" r="3"/>
        <circle cx="260" cy="100" r="3"/>
      </g>
      <g fill="none" stroke="#3237f0" stroke-width="1.2" opacity="0.45">
        <path d="M20 100 L40 70 L60 100 L80 70 L100 100 L120 70 L140 100 L160 70 L180 100 L200 70 L220 100 L240 70 L260 100"/>
        <path d="M20 130 L40 160 L60 130 L80 160 L100 130 L120 160 L140 130 L160 160 L180 130 L200 160 L220 130 L240 160 L260 130"/>
      </g>
    </svg>
  </div>

  <div class="container-x">
    <div class="hero-grid">
      <div>
        <span class="chapter fade-up" data-delay="1"><b>{{ $s["chapter_num"] ?? "01" }}</b> · {{ $s["chapter_label"] ?? "Welcome" }}</span>

        <h1 class="fade-up" data-delay="2">{!! $s["headline_html"] ?? "<span class=\"line\">Social &amp;</span><span class=\"line line-nowrap\"><span class=\"underline\"><em>Behaviour Change</em></span></span><span class=\"line\">Communication for all.</span>" !!}</h1>

        <p class="hero-lede type-lede fade-up" data-delay="3">{!! $s['lede_html'] ?? 'ChhattisgarhABC is a community platform where <b>youth, professionals, civil society and government</b> come together to share experiences and understand <em>Social &amp; Behaviour Change Communication</em> across Chhattisgarh.' !!}</p>
      </div>

      <div class="hero-visual fade-up{{ $hasBanners ? ' hero-visual--stats-only' : '' }}" data-delay="3">
        @unless ($hasBanners)
        @foreach ($s['panels'] ?? [['class' => 'panel-1', 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/banner/1714379236.png'], ['class' => 'panel-2', 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/banner/1714379202.png']] as $panel)
        <div class="panel {{ $panel['class'] }}" style="background-image:url('{{ $panel['image'] }}');" aria-hidden="true"></div>
        @endforeach
        @endunless
        <div class="panel-tag">
          <span class="kicker">{{ $tag['kicker'] }}</span>
          <strong>{{ $tag['value'] }}</strong>
          <small>{{ $tag['small'] }}</small>
        </div>
        <div class="panel-stat">
          <span class="kicker">{{ $stat['kicker'] }}</span>
          <div class="big">{{ $stat['value'] }}</div>
          <small>{{ $stat['small'] }}</small>
        </div>
      </div>
    </div>
  </div>
</section>


{{-- Section: marquee --}}
@php $s = $pageSections['marquee'] ?? []; $items = $s['items'] ?? []; @endphp
<div class="marquee" aria-hidden="true">
  <div class="marquee-track">
    @foreach (array_merge($items, $items) as $item)
    <span class="marquee-item"><span class="num">{{ $item['num'] }}</span>&nbsp;{{ $item['label'] }}</span>
    <span class="marquee-item marquee-sep">·</span>
    @endforeach
  </div>
</div>


{{-- Section: intro --}}
<section class="intro container-x" aria-labelledby="intro-h">
@if(!empty($pageSections['intro']['html']))
{!! $pageSections['intro']['html'] !!}
@else
<div class="intro-head">
    <div>
      <span class="chapter"><b>02</b></span>
      <h2 id="intro-h" class="hashtag-heading" data-aos="fade-up"><span class="hashtag-mark">#</span>SBCMatters</h2>
    </div>
    <p data-aos="fade-up" data-aos-delay="120">
      A powerful approach that uses Strategic Communication and Community Engagement to shape behavior into practices. It goes beyond traditional advertisement, coordinating channels to measure and iterate.
    </p>
  </div>

  <div class="pillars">
    <article class="pillar" data-aos="fade-up">
      <div class="pillar-icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M2 12h20"/><circle cx="12" cy="12" r="9"/></svg>
      </div>
      <span class="ch-num">i.</span>
      <h4>Define the issue</h4>
      <p>Identify the specific health or social issue, gather data, and frame the problem before designing.</p>
    </article>

    <article class="pillar" data-aos="fade-up" data-aos-delay="80">
      <div class="pillar-icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12c2-4 6-6 9-6s7 2 9 6c-2 4-6 6-9 6s-7-2-9-6Z"/><circle cx="12" cy="12" r="3"/></svg>
      </div>
      <span class="ch-num">ii.</span>
      <h4>Apply SBC theory</h4>
      <p>Frame the programme using evidence-based behaviour-change theory — not assumptions.</p>
    </article>

    <article class="pillar" data-aos="fade-up" data-aos-delay="160">
      <div class="pillar-icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="7" r="4"/><circle cx="17" cy="11" r="3"/><path d="M2 21c0-3 3-6 7-6s7 3 7 6M14 21c0-2 2-4 4-4s4 2 4 4"/></svg>
      </div>
      <span class="ch-num">iii.</span>
      <h4>Coordinate channels</h4>
      <p>Reach individuals, communities and policymakers — radio, print, video, and field outreach in sync.</p>
    </article>

    <article class="pillar" data-aos="fade-up" data-aos-delay="240">
      <div class="pillar-icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 14l4-4 3 3 5-6"/></svg>
      </div>
      <span class="ch-num">iv.</span>
      <h4>Measure &amp; iterate</h4>
      <p>Track outcomes, share learnings across the alliance, and refine messaging every cycle.</p>
    </article>
  </div>
@endif
</section>

{{-- Section: programs --}}
<section class="programs-section" id="programs-initiatives" aria-labelledby="programs-h">

@if ($programsUseSlider)
    {{-- Slider layout — jab database ya CMS se cards milte hain --}}
    <div class="container-x">
        <div class="programs-head programs-head--center-slider">
            <div>
                <span class="chapter"><b>{{ $programsHeader['chapter_num'] }}</b> · {{ $programsHeader['chapter_label'] }}</span>
                <h2 id="programs-h">{!! $programsHeader['heading_html'] !!}</h2>
            </div>
            <div class="programs-center-controls">
                <button id="programs-center-prev" class="programs-center-nav-btn" type="button" aria-label="Previous program">‹</button>
                <button id="programs-center-next" class="programs-center-nav-btn" type="button" aria-label="Next program">›</button>
            </div>
        </div>

        <p class="programs-center-lede">{{ $programsHeader['lede_text'] }}</p>

        <div class="programs-center-slider">
            <div class="programs-center-track" id="programs-center-track">
                @foreach ($programsCards as $card)
                    <article class="programs-center-card" @if ($card['is_active']) active @endif data-program-card>
                        @if ($card['image_url'] !== '')
                            <img class="programs-center-card__bg" src="{{ $card['image_url'] }}" alt="{{ $card['title'] }}" loading="lazy" decoding="async">
                        @endif
                        <div class="programs-center-card__content">
                            @if ($card['image_url'] !== '')
                                <img class="programs-center-card__thumb" src="{{ $card['image_url'] }}" alt="{{ $card['title'] }}" loading="lazy" decoding="async">
                            @endif
                            <div class="programs-center-card__copy">
                                <h3 class="programs-center-card__title">{{ $card['title'] }}</h3>
                                <p class="programs-center-card__desc">{{ $card['description'] }}</p>
                                <a href="{{ $programsDetailsUrl }}" class="programs-center-card__btn">Details</a>
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

    @push('scripts')
        <script src="{{ asset('assets/js/programs-center-slider.js') }}?v={{ filemtime(public_path('assets/js/programs-center-slider.js')) }}"></script>
    @endpush

@else
    {{-- Flip grid layout — jab koi data nahi hai to default cards --}}
    <div class="container-x">
        <div class="programs-head">
            <div>
                <span class="chapter"><b>{{ $programsHeader['chapter_num'] }}</b> · {{ $programsHeader['chapter_label'] }}</span>
                <h2 id="programs-h" data-aos="fade-up">{!! $programsHeader['heading_html'] !!}</h2>
            </div>
            <p data-aos="fade-up" data-aos-delay="120">{{ $programsHeader['lede_text'] }}</p>
        </div>

        <div class="program-flip-grid">
            @foreach ($programsCards as $card)
                <article
                    class="program-flip-card program-flip-card--{{ $card['accent'] }}"
                    data-aos="fade-up"
                    @if ($card['delay']) data-aos-delay="{{ $card['delay'] }}" @endif
                >
                    <div class="program-flip-card__inner" tabindex="0" aria-label="{{ $card['title'] }} — flip for details">
                        <div class="program-flip-card__face program-flip-card__front">
                            @if ($card['image_url'] !== '')
                                <img class="program-flip-card__photo" src="{{ $card['image_url'] }}" alt="" loading="lazy" decoding="async">
                            @else
                                <div class="program-flip-card__photo program-flip-card__photo--placeholder" aria-hidden="true"></div>
                            @endif
                            <div class="program-flip-card__front-overlay">
                                <h3 class="program-flip-card__title">{{ $card['title'] }}</h3>
                            </div>
                        </div>
                        <div class="program-flip-card__face program-flip-card__back">
                            <div class="program-flip-card__back-inner">
                                <h3 class="program-flip-card__back-title">{{ $card['title'] }}</h3>
                                <p class="program-flip-card__description">{{ $card['description'] }}</p>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="programs-explore" data-aos="fade-up">
            <a href="{{ $programsHeader['explore_url'] }}" class="btn btn-primary programs-explore__btn">
                {{ $programsHeader['explore_label'] }}
                <span class="arrow" aria-hidden="true">→</span>
            </a>
        </div>
    </div>
@endif

</section>

{{-- Section: champions --}}
@php
    $s = $pageSections['champions'] ?? [];
    $storyItems = ! empty($homeRecentStories) ? $homeRecentStories : ($s['items'] ?? []);
    $storiesUrl = route('stories');
    $storiesLinkLabel = $s['stories_link_label'] ?? 'Read all stories →';
@endphp
<section class="champions-section" aria-labelledby="ch-h">
@if (! empty($storyItems))
    <div class="container-x">
        <div class="champions-head">
            <div>
                <span class="chapter"><b>{{ $s['chapter_num'] ?? '04' }}</b> · {{ $s['chapter_label'] ?? 'Recent Stories' }}</span>
                <h2 id="ch-h" class="type-section-title">{!! $s['heading_html'] ?? 'Voices from the <em>field</em>.' !!}</h2>
            </div>
            <p class="head-side">{{ $s['side_text'] ?? 'A rolling chronicle of behaviour-change work — campaigns, milestones, and human stories from across Chhattisgarh.' }}</p>
        </div>

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
    <div class="container-x">
        <div class="champions-head">
            <div>
                <span class="chapter"><b>04</b> · Recent Stories</span>
                <h2 id="ch-h">Voices from the <em>field</em>.</h2>
            </div>
            <p class="head-side">A rolling chronicle of behaviour-change work — campaigns, milestones, and human stories from across Chhattisgarh.</p>
        </div>

        <div id="home-stories-carousel" class="champions-swiper swiper" data-aos="fade-up">
            <div class="swiper-wrapper">
                <article class="swiper-slide champion">
                    <div class="champion-photo" style="background-image:url('https://www.chhattisgarhabc.org/stories/uploads/story/69b2a3de375ea.png');">
                        <div class="champion-meta"><span class="role-pill story-location-pill">Raipur</span></div>
                    </div>
                    <div class="champion-body">
                        <div class="champion-where">12 March 2026</div>
                        <h4 class="champion-name">Chhattisgarh launches youth-led dashboard and "Aaj Mauka Hai" campaign</h4>
                        <p class="champion-blurb">A youth-led dashboard tracking behaviour-change action, alongside the "Aaj Mauka Hai" outreach campaign rolling across districts.</p>
                    </div>
                </article>
                <article class="swiper-slide champion">
                    <div class="champion-photo" style="background-image:url('https://www.chhattisgarhabc.org/stories/uploads/story/69a086247a963.png');">
                        <div class="champion-meta"><span class="role-pill story-location-pill">Jashpur</span></div>
                    </div>
                    <div class="champion-body">
                        <div class="champion-where">26 February 2026</div>
                        <h4 class="champion-name">एक सवाल, बड़ा बदलाव — 'आज क्या सीखा?' ने जशपुर में…</h4>
                        <p class="champion-blurb">A simple daily question reshaping classroom conversations across Jashpur — peer-led and teacher-supported.</p>
                    </div>
                </article>
                <article class="swiper-slide champion">
                    <div class="champion-photo" style="background-image:url('https://www.chhattisgarhabc.org/stories/uploads/story/69974fe1da143.png');">
                        <div class="champion-meta"><span class="role-pill story-location-pill">Bilaspur</span></div>
                    </div>
                    <div class="champion-body">
                        <div class="champion-where">19 February 2026</div>
                        <h4 class="champion-name">UNICEF Human-Centered Design Workshop, Bilaspur</h4>
                        <p class="champion-blurb">Tackling disability stigma through participatory HCD methods, co-led with UNICEF and local champions.</p>
                    </div>
                </article>
                <article class="swiper-slide champion">
                    <div class="champion-photo" style="background-image:url('https://www.chhattisgarhabc.org/stories/uploads/story/696641b1e3114.png');">
                        <div class="champion-meta"><span class="role-pill story-location-pill">Balod</span></div>
                    </div>
                    <div class="champion-body">
                        <div class="champion-where">13 January 2026</div>
                        <h4 class="champion-name">Balod becomes India's first Child Marriage–Free District</h4>
                        <p class="champion-blurb">A multi-year SBC effort culminates in a verified district-wide milestone — community-owned, government-backed.</p>
                    </div>
                </article>
                <article class="swiper-slide champion">
                    <div class="champion-photo" style="background-image:url('https://www.chhattisgarhabc.org/stories/uploads/story/67ce9ac3493da.png');">
                        <div class="champion-meta"><span class="role-pill story-location-pill">Kabirdham</span></div>
                    </div>
                    <div class="champion-body">
                        <div class="champion-where">10 March 2025</div>
                        <h4 class="champion-name">Kavir Participatory Action Lab — community-driven change</h4>
                        <p class="champion-blurb">A hub for participatory research and field-tested behaviour-change interventions, run by and for the community.</p>
                    </div>
                </article>
                <article class="swiper-slide champion">
                    <div class="champion-photo" style="background-image:url('https://www.chhattisgarhabc.org/stories/uploads/story/67909992e8e60.png');">
                        <div class="champion-meta"><span class="role-pill story-location-pill">Raipur</span></div>
                    </div>
                    <div class="champion-body">
                        <div class="champion-where">21 January 2025</div>
                        <h4 class="champion-name">Yuvoday Volunteers — Championing Mental Health Inclusion</h4>
                        <p class="champion-blurb">Youth-led groups advancing mental-health inclusion across Chhattisgarh's panchayats — circles, conversations, referrals.</p>
                    </div>
                </article>
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
            <a class="btn-link" href="{{ route('stories') }}">Read all stories →</a>
        </div>
    </div>
@endif
</section>

{{-- Section: events --}}
@php
    $s = $pageSections['events'] ?? [];
    $eventItems = $homeRecentEvents ?? [];
@endphp
<section class="container-x section home-events-section" aria-labelledby="events-home-h">
@if (! empty($eventItems))
    <div class="champions-head">
        <div>
            <span class="chapter"><b>{{ $s['chapter_num'] ?? '05' }}</b> · {{ $s['chapter_label'] ?? 'Events' }}</span>
            <h2 id="events-home-h" class="type-section-title">{!! $s['heading_html'] ?? 'Where the alliance <em>meets</em>.' !!}</h2>
        </div>
        <p class="head-side">{{ $s['side_text'] ?? 'Upcoming webinars, workshops and convenings from across Chhattisgarh, all in one quick events preview.' }}</p>
    </div>

    <div class="initiatives-grid home-events-grid">
        @foreach ($eventItems as $item)
            <a href="{{ $item['url'] }}" class="{{ $item['tile_class'] }}" style="background-image:url('{{ $item['image'] }}');">
                <span class="tile-tag">{{ $item['tag'] }}</span>
                <span class="arrow-circle" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
                </span>
                <h4>{{ $item['title'] }}</h4>
                <p>{{ $item['description'] }}</p>
            </a>
        @endforeach
    </div>
@elseif (! empty($s['tiles']))
    <div class="champions-head">
        <div>
            <span class="chapter"><b>{{ $s['chapter_num'] ?? '05' }}</b> · {{ $s['chapter_label'] ?? 'Events' }}</span>
            <h2 id="events-home-h" class="type-section-title">{!! $s['heading_html'] ?? 'Where the alliance <em>meets</em>.' !!}</h2>
        </div>
        @if (! empty($s['side_text']))
            <p class="head-side">{{ $s['side_text'] }}</p>
        @endif
    </div>

    <div class="initiatives-grid home-events-grid">
        @foreach ($s['tiles'] as $tile)
            <a href="{{ route($s['events_link']['name'] ?? 'events') }}" class="tile {{ $tile['class'] ?? 'tile-1' }}" style="background-image:url('{{ $tile['image'] ?? '' }}');">
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
    <div class="champions-head">
        <div>
            <span class="chapter"><b>05</b> · Events</span>
            <h2 id="events-home-h">Where the alliance <em>meets</em>.</h2>
        </div>
        <p class="head-side">Upcoming webinars, workshops and convenings from across Chhattisgarh, all in one quick events preview.</p>
    </div>

    <div class="initiatives-grid home-events-grid">
        <a href="{{ route('events') }}" class="tile tile-1" style="background-image:url('https://www.chhattisgarhabc.org/stories/uploads/event/427748.png');">
            <span class="tile-tag">Featured · Webinar</span>
            <span class="arrow-circle" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
            </span>
            <h4>SBC at scale: counselling 50,000 adolescents</h4>
            <p>A 90-minute live webinar with master trainers from Bilaspur, Bastar and Surguja, followed by an open Q&amp;A.</p>
        </a>
        <a href="{{ route('events') }}" class="tile tile-2" style="background-image:url('https://www.chhattisgarhabc.org/stories/uploads/story/696641b1e3114.png');">
            <span class="tile-tag">Conference · 3-day</span>
            <span class="arrow-circle" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
            </span>
            <h4>State SBC Summit 2026</h4>
            <p>Six tracks, fourteen partners and one state-level showcase of behaviour-change programmes in Raipur.</p>
        </a>
        <a href="{{ route('events') }}" class="tile tile-3" style="background-image:url('https://www.chhattisgarhabc.org/stories/uploads/event/171026.jpeg');">
            <span class="tile-tag">Workshop · 1-day</span>
            <span class="arrow-circle" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
            </span>
            <h4>Adolescent Health Bootcamp</h4>
            <p>A field-day in Bilaspur focused on counselling techniques, role-play and live learning with kishori groups.</p>
        </a>
        <a href="{{ route('events') }}" class="tile tile-4" style="background-image:url('https://www.chhattisgarhabc.org/stories/uploads/event/397224.png');">
            <span class="tile-tag">Webinar · Live</span>
            <span class="arrow-circle" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
            </span>
            <h4>Father engagement: deep-dive</h4>
            <p>Why anganwadi attendance for fathers tripled in Kabirdham, and what did not work in the first phase.</p>
        </a>
        <a href="{{ route('events') }}" class="tile tile-5" style="background-image:url('https://www.chhattisgarhabc.org/stories/uploads/event/427748.png');">
            <span class="tile-tag">Completed · Conclave</span>
            <span class="arrow-circle" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>
            </span>
            <h4>Adolescent Voices Conclave</h4>
            <p>240 adolescents from 18 districts came together for one of the alliance's biggest youth-led gatherings.</p>
        </a>
    </div>
@endif
</section>

{{-- Section: hub --}}
@php $s = $pageSections['hub'] ?? []; @endphp
<section class="hub container-x" aria-labelledby="hub-h">
@if (!empty($s['html']))
{!! $s['html'] !!}
@else
@php
    $chapterNum = $s['chapter_num'] ?? '06';
    $chapterLabel = $s['chapter_label'] ?? 'Knowledge Hub';
    $headingHtml = $s['heading_html'] ?? 'Knowledge assets around <em>behaviour change</em>.';
    $sideText = $s['side_text'] ?? 'Learning materials, evidence, toolkits and practitioner support — organised for teams across the alliance.';
    $resources = $s['resources'] ?? [
        [
            'link' => ['type' => 'route', 'name' => 'learning-corner'],
            'type' => 'Learning',
            'title' => 'Learning Corner',
            'meta' => 'Modules, videos, posters and training material',
            'aos_delay' => null,
            'icon' => 'book',
        ],
        [
            'link' => ['type' => 'route', 'name' => 'reports'],
            'type' => 'Evidence',
            'title' => 'Reports & Insights',
            'meta' => 'Reports, newsletters and success stories',
            'aos_delay' => 100,
            'icon' => 'chart',
        ],
        [
            'link' => ['type' => 'route', 'name' => 'programs'],
            'type' => 'Programs',
            'title' => 'Programs and Initiatives',
            'meta' => 'Flagship SBC initiatives across districts',
            'aos_delay' => 200,
            'icon' => 'target',
        ],
        [
            'link' => ['type' => 'route', 'name' => 'resources'],
            'type' => 'Practitioners',
            'title' => 'SBC Resource Pool',
            'meta' => 'Connect with trainers and field communicators',
            'aos_delay' => 300,
            'icon' => 'users',
        ],
    ];
    $libraryLink = $s['library_link'] ?? ['type' => 'route', 'name' => 'learning-corner'];
    $libraryLabel = $s['library_label'] ?? 'Open Learning Corner →';
    $libraryHref = match ($libraryLink['type'] ?? 'route') {
        'route' => route($libraryLink['name'] ?? 'learning-corner', $libraryLink['params'] ?? []),
        default => $libraryLink['url'] ?? route('learning-corner'),
    };
@endphp
<div class="champions-head">
  <div>
    <span class="chapter"><b>{{ $chapterNum }}</b> · {{ $chapterLabel }}</span>
    <h2 id="hub-h">{!! $headingHtml !!}</h2>
  </div>
  <p class="head-side">{{ $sideText }}</p>
</div>

<div class="hub-grid hub-grid-4" role="list">
  @foreach ($resources as $resource)
    @php
        $link = $resource['link'] ?? [];
        $href = '#';
        if (($link['type'] ?? 'route') === 'route') {
            $base = route($link['name'] ?? 'home', $link['params'] ?? []);
            $query = $link['query'] ?? '';
            $fragment = $link['fragment'] ?? '';
            $href = $base
                . ($query !== '' ? (str_contains($base, '?') ? '&' : '?') . $query : '')
                . ($fragment !== '' ? '#' . ltrim($fragment, '#') : '');
        } elseif (($link['type'] ?? '') === 'anchor') {
            $href = $link['hash'] ?? '#';
        } else {
            $href = $link['url'] ?? '#';
        }
        $delay = $resource['aos_delay'] ?? null;
        $icon = $resource['icon'] ?? 'layers';
    @endphp
    <a href="{{ $href }}" class="resource" data-aos="fade-up"@if($delay !== null && $delay !== '') data-aos-delay="{{ $delay }}"@endif>
      <div class="resource-icon" aria-hidden="true">
        @if ($icon === 'book')
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h12a4 4 0 0 1 4 4v12H8a4 4 0 0 1-4-4z"/><path d="M4 4v12a4 4 0 0 0 4 4"/></svg>
        @elseif ($icon === 'chart')
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 14l4-4 3 3 5-6"/></svg>
        @elseif ($icon === 'users')
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        @else
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M2 7l10-4 10 4-10 4z"/><path d="M2 7v6c0 .5 4 4 10 4s10-3.5 10-4V7"/></svg>
        @endif
      </div>
      <span class="resource-type">{{ $resource['type'] ?? '' }}</span>
      <h4>{{ $resource['title'] ?? '' }}</h4>
      <div class="resource-meta">
        <span>{{ $resource['meta'] ?? '' }}</span>
        <span aria-hidden="true">→</span>
      </div>
    </a>
  @endforeach
</div>

<div class="hub-library-cta">
  <a class="btn btn-ghost" href="{{ $libraryHref }}">{{ $libraryLabel }}</a>
</div>

@endif
</section>

{{-- Section: get-involved-cta --}}
@php $s = $pageSections['get_involved_cta'] ?? []; @endphp
<section class="cta home-get-involved" id="home-get-involved" aria-labelledby="get-involved-h">
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
      <a href="{{ route('get-involved') }}#gi-guest" class="cta-path">
        <b>Guest</b>
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

</section>
</main>

@if (! empty($programCards))
@push('scripts')
<script src="{{ asset('assets/js/programs-center-slider.js') }}?v={{ filemtime(public_path('assets/js/programs-center-slider.js')) }}"></script>
@endpush
@endif
@endsection
