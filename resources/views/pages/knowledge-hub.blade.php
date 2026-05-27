@extends('layouts.app')

@section('title', $pageContent['meta_title'] ?? 'Knowledge Hub · ChhattisgarhABC')
@section('meta_description', $pageContent['meta_description'] ?? 'A working library of SBC research, IEC materials, toolkits, guides, reports and data resources — open-licensed, downloadable, and many remixable on Canva.')

@php
  $hero = $pageSections['hero'] ?? [];
  $searchBar = $pageSections['search_bar'] ?? [];
  $featured = $knowledgeHubFeatured ?? ($pageSections['featured'] ?? []);
  $grid = $pageSections['grid'] ?? [];
  $gridResources = $knowledgeHubGridResources ?? ($grid['resources'] ?? []);
  $resourceTotal = $knowledgeHubTotal ?? ($searchBar['total'] ?? count($gridResources));
  $canva = $pageSections['canva'] ?? [];
@endphp

@section('content')
<main id="main">

<section class="kh-hero" aria-labelledby="kh-hero-h">
  <div class="container-x">
    <div class="kh-hero-grid">
      <div class="kh-hero-text">
        <span class="chapter fade-up" data-delay="1"><b>{{ $hero['chapter'] ?? '06' }}</b> · Library</span>

        <h2 id="kh-hero-h" class="kh-hero-title type-hero fade-up" data-delay="2">
          {!! $hero['title'] ?? '' !!}
        </h2>

        <p class="kh-hero-lede type-lede fade-up" data-delay="3">
          {!! $hero['lede'] ?? '' !!}
        </p>

        <div class="kh-hero-cta fade-up" data-delay="4">
          <a class="btn btn-primary" href="{{ $hero['cta_primary']['href'] ?? '#kh-search-input' }}">
            {{ $hero['cta_primary']['label'] ?? 'Search the library' }}
            <svg class="arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
          </a>
          <a class="btn btn-ghost" href="{{ $hero['cta_secondary']['href'] ?? '#kh-canva' }}">{{ $hero['cta_secondary']['label'] ?? 'View Canva editables' }}</a>
        </div>
      </div>

      <aside class="kh-hero-stats fade-up" data-delay="3" aria-label="Library statistics">
        @foreach ($hero['stats'] ?? [] as $stat)
          <div>
            <b data-countup="{{ $stat['value'] }}" @if (!empty($stat['suffix'])) data-suffix="{{ $stat['suffix'] }}" @endif>0</b>
            <span>{{ $stat['label'] }}</span>
          </div>
        @endforeach
      </aside>
    </div>
  </div>
</section>

<section class="kh-bar" id="kh-search" aria-label="Search and filter the library">
  <div class="container-x">
    <div class="kh-bar-inner">
      <label class="kh-search" for="kh-search-input">
        <svg class="kh-search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
        <input
          type="search"
          id="kh-search-input"
          class="kh-search-input"
          placeholder="{{ $searchBar['placeholder'] ?? 'Search by title, author, district, keyword…' }}"
          aria-label="Search the library"
          autocomplete="off"
          data-kh-search />
        <button type="button" class="kh-search-clear" aria-label="Clear search" data-kh-clear hidden>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
      </label>

      <div class="kh-filters" role="group" aria-label="Filter by category">
        <button type="button" class="kh-chip is-active" data-filter="all">All</button>
        <button type="button" class="kh-chip" data-filter="research">
          <i class="kh-chip-dot" style="background:var(--terracotta);"></i>Research
        </button>
        <button type="button" class="kh-chip" data-filter="iec">
          <i class="kh-chip-dot" style="background:var(--ochre);"></i>IEC Materials
        </button>
        <button type="button" class="kh-chip" data-filter="toolkit">
          <i class="kh-chip-dot" style="background:var(--teal);"></i>Toolkits
        </button>
        <button type="button" class="kh-chip" data-filter="guide">
          <i class="kh-chip-dot" style="background:var(--leaf);"></i>Guides
        </button>
        <button type="button" class="kh-chip" data-filter="report">
          <i class="kh-chip-dot" style="background:var(--indigo);"></i>Reports
        </button>
        <button type="button" class="kh-chip" data-filter="data">
          <i class="kh-chip-dot" style="background:var(--saffron);"></i>Data Resources
        </button>
      </div>
    </div>

    <div class="kh-bar-meta">
      <span class="kh-count">
        Showing <b data-kh-count>{{ $resourceTotal }}</b> of <b>{{ $resourceTotal }}</b> resources
      </span>
      <button type="button" class="btn-link kh-reset" data-kh-reset>Reset filters</button>
    </div>
  </div>
</section>

<section class="kh-feature-wrap" aria-labelledby="kh-feature-h">
  <div class="container-x">
    <article class="kh-feature" data-aos="fade-up"
             data-kh-card data-category="{{ $featured['category'] ?? '' }}"
             data-keywords="{{ $featured['keywords'] ?? '' }}"
             data-kh-id="{{ $featured['id'] ?? '' }}"
             data-kh-cover="{{ $featured['cover'] ?? '' }}"
             data-kh-title="{{ $featured['detail']['title'] ?? $featured['title'] ?? '' }}"
             data-kh-desc="{{ $featured['detail']['description'] ?? '' }}"
             data-kh-format="{{ $featured['format'] ?? '' }}"
             data-kh-size="{{ $featured['size'] ?? '' }}"
             data-kh-pages="{{ $featured['pages'] ?? '' }}"
             data-kh-langs="{{ $featured['detail']['langs'] ?? '' }}"
             data-kh-published="{{ $featured['detail']['published'] ?? '' }}"
             data-kh-downloads="{{ $featured['detail']['downloads'] ?? '' }}"
             data-kh-canva="{{ !empty($featured['detail']['canva']) ? 'true' : 'false' }}">
      <div class="kh-feature-cover" style="background-image:url('{{ $featured['cover'] ?? '' }}');" aria-hidden="true">
        <span class="kh-feature-flag">{{ $featured['flag'] ?? '' }}</span>
      </div>
      <div class="kh-feature-body">
        <header class="kh-feature-head">
          @php
            $featuredCategoryLabels = ['research' => 'Research', 'iec' => 'IEC Materials', 'toolkit' => 'Toolkit', 'guide' => 'Guide', 'report' => 'Report', 'data' => 'Data'];
          @endphp
          <span class="kh-tag" data-cat="{{ $featured['category'] ?? '' }}">{{ $featuredCategoryLabels[$featured['category'] ?? ''] ?? '' }}</span>
          <span class="kh-fmt" data-fmt="{{ $featured['format'] ?? '' }}">{{ $featured['format'] ?? '' }}</span>
        </header>
        <h2 id="kh-feature-h" class="kh-feature-title">{!! $featured['title'] ?? '' !!}</h2>
        <p class="kh-feature-desc">{{ $featured['description'] ?? '' }}</p>
        <ul class="kh-feature-meta">
          @foreach ($featured['meta'] ?? [] as $meta)
            <li>
              @if (is_array($meta))
                <b>{{ $meta['value'] ?? '' }}</b><span>{{ $meta['label'] ?? '' }}</span>
              @else
                {{ $meta }}
              @endif
            </li>
          @endforeach
        </ul>
        <div class="kh-feature-cta">
          <button type="button" class="btn btn-primary" data-kh-open="{{ $featured['id'] ?? '' }}">
            Preview
            <svg class="arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
          </button>
          <a href="{{ $featured['download_url'] ?? '#' }}" class="btn btn-ghost" @if (!empty($featured['download_url']) && ($featured['download_url'] ?? '#') !== '#') target="_blank" rel="noopener" @endif>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
            Download PDF
          </a>
        </div>
      </div>
    </article>
  </div>
</section>

<section class="kh-grid-wrap" aria-labelledby="kh-grid-h">
  <div class="container-x">
    <div class="kh-grid-head">
      <span class="chapter"><b>{{ $grid['chapter'] ?? '07' }}</b> · The shelves</span>
      <h2 id="kh-grid-h" data-aos="fade-up">{!! $grid['title'] ?? '' !!}</h2>
      <p class="kh-grid-sub" data-aos="fade-up" data-aos-delay="100">
        {{ $grid['subtitle'] ?? '' }}
      </p>
    </div>

    <div class="kh-grid" data-kh-grid>
      @foreach ($gridResources as $resource)
        @include('partials.knowledge-hub-resource-card', ['resource' => $resource])
      @endforeach

    <div class="kh-empty" data-kh-empty hidden>
      <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <circle cx="11" cy="11" r="7"/>
        <path d="m20 20-3.5-3.5"/>
        <path d="M8 11h6"/>
      </svg>
      <h3>No matches on these shelves.</h3>
      <p>Try a different keyword or <button type="button" class="btn-link" data-kh-reset>reset the filters</button>.</p>
    </div>
    </div>
  </div>
</section>

<section class="kh-canva" id="kh-canva" aria-labelledby="kh-canva-h">
  <div class="container-x">
    <div class="kh-canva-grid">
      <div class="kh-canva-text" data-aos="fade-up">
        <span class="chapter" style="color:rgba(250,248,255,0.7);"><b style="color:var(--ochre);">{{ $canva['chapter'] ?? '08' }}</b> · Canva editables</span>
        <h2 id="kh-canva-h">{!! $canva['title'] ?? '' !!}</h2>
        <p class="kh-canva-lede">{!! $canva['lede'] ?? '' !!}</p>
        <a href="{{ $canva['button']['href'] ?? '#' }}" class="btn btn-primary">
          {{ $canva['button']['label'] ?? 'Open the Canva folder' }}
          <svg class="arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
        </a>
      </div>

      <ul class="kh-canva-list" data-aos="fade-up" data-aos-delay="120">
        @foreach ($canva['steps'] ?? [] as $step)
          <li>
            <span class="kh-canva-num">{{ $step['num'] }}</span>
            <div>
              <b>{{ $step['title'] }}</b>
              <span>{{ $step['description'] }}</span>
            </div>
          </li>
        @endforeach
      </ul>
    </div>
  </div>
</section>

<div class="kh-mod" data-kh-mod hidden role="dialog" aria-modal="true" aria-labelledby="kh-mod-title">
  <div class="kh-mod-backdrop" data-kh-close></div>
  <div class="kh-mod-card" role="document">
    <button type="button" class="kh-mod-close" data-kh-close aria-label="Close preview">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
    </button>

    <div class="kh-mod-cover" data-kh-mod-cover></div>

    <div class="kh-mod-body">
      <header class="kh-mod-head">
        <span class="kh-tag" data-kh-mod-tag>Resource</span>
        <span class="kh-fmt" data-kh-mod-fmt>PDF</span>
      </header>
      <h2 id="kh-mod-title" data-kh-mod-title>Resource title</h2>
      <p class="kh-mod-desc" data-kh-mod-desc>Description goes here.</p>

      <dl class="kh-mod-specs">
        <div><dt>Pages</dt><dd data-kh-mod-pages>—</dd></div>
        <div><dt>File size</dt><dd data-kh-mod-size>—</dd></div>
        <div><dt>Languages</dt><dd data-kh-mod-langs>—</dd></div>
        <div><dt>Published</dt><dd data-kh-mod-published>—</dd></div>
        <div><dt>Downloads</dt><dd data-kh-mod-downloads>—</dd></div>
        <div><dt>License</dt><dd>CC BY-SA 4.0</dd></div>
      </dl>

      <div class="kh-mod-cta">
        <a href="#" class="btn btn-primary" data-kh-mod-dl download>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
          Download
        </a>
        <a href="#" class="btn btn-ghost kh-mod-canva" data-kh-mod-canva hidden>
          Open on Canva
          <svg class="arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
        </a>
      </div>
    </div>
  </div>
</div>

</main>
@endsection
