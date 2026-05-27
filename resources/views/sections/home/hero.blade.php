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
