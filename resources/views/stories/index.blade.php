@extends('layouts.app')

@section('title', $metaTitle)
@section('meta_description', $metaDescription)

@php
  $storyCards = $storyPaginator ? $storyPaginator->items() : [];
@endphp

@section('content')
<main id="main">

<section class="st-filters" aria-label="Filter stories by category, district and date range">
  <div class="container-x">
    <div class="st-filters-row">
      <div class="st-filters-block">
        <label class="st-filters-lbl" for="st-filter-category"><b>Category</b></label>
        <div class="st-select">
          <select id="st-filter-category" data-st-filter="category">
            @foreach ($filters['categories'] as $category)
              <option value="{{ $category['value'] }}">{{ $category['label'] }}</option>
            @endforeach
          </select>
          <svg class="st-select-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
        </div>
      </div>

      <div class="st-filters-block">
        <label class="st-filters-lbl" for="st-filter-district"><b>District</b></label>
        <div class="st-select">
          <select id="st-filter-district" data-st-filter="district">
            @foreach ($filters['districts'] as $district)
              <option value="{{ $district['value'] }}">{{ $district['label'] }}</option>
            @endforeach
          </select>
          <svg class="st-select-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
        </div>
      </div>

      <div class="st-filters-block">
        <label class="st-filters-lbl" for="st-filter-from"><b>From</b></label>
        <div class="st-date">
          <input id="st-filter-from" type="date" data-st-date="from" aria-label="Filter stories from date">
        </div>
      </div>

      <div class="st-filters-block">
        <label class="st-filters-lbl" for="st-filter-to"><b>To</b></label>
        <div class="st-date">
          <input id="st-filter-to" type="date" data-st-date="to" aria-label="Filter stories to date">
        </div>
      </div>

      <div class="st-filters-meta">
        <span class="st-filters-count" aria-live="polite"><b data-st-count>{{ $storyCount }}</b> stories</span>
        <button type="button" class="st-filters-reset" data-st-reset>Reset</button>
      </div>
    </div>
  </div>
</section>

<section class="st-grid container-x" id="st-grid" aria-labelledby="st-grid-h">
  <h2 id="st-grid-h" class="sr-only">Story grid</h2>

  <div class="st-grid-empty" data-st-empty hidden>
    <span class="chapter"><b>{{ $storyGrid['empty']['chapter'] }}</b> · {{ $storyGrid['empty']['chapter_suffix'] }}</span>
    <p>{{ $storyGrid['empty']['text'] }} <button type="button" class="st-link" data-st-reset>reset all filters</button>.</p>
  </div>

  <div class="st-grid-wrap" data-st-list>
    @foreach ($storyCards as $card)
      <article class="{{ $card['classes'] }}" data-aos="fade-up" @if ($card['aos_delay']) data-aos-delay="{{ $card['aos_delay'] }}" @endif data-category="{{ $card['category'] }}" data-theme="{{ $card['theme'] }}" data-district="{{ $card['district'] }}" data-date="{{ $card['date'] }}">
        <a class="st-card-link" href="{{ $card['url'] }}">
          <div class="st-card-img" style="background-image:url('{{ $card['image'] }}');">
            @if ($card['cat_label'] !== '')
              <span class="st-card-cat">{{ $card['cat_label'] }}</span>
            @endif
            @if ($card['location'] !== '')
              <span class="st-card-location">{{ $card['location'] }}</span>
            @endif
          </div>
          <div class="st-card-body">
            @if ($card['where'] !== '')
              <div class="st-card-where">{{ $card['where'] }}</div>
            @endif
            <h3 class="st-card-title">{!! $card['title'] !!}</h3>
            <p class="st-card-lede">{{ $card['lede'] }}</p>
            <p class="st-card-authored">{{ $card['authored_by'] }}</p>
          </div>
        </a>
        <div class="st-card-foot">
          <x-story-share :url="$card['share_url']" :title="$card['share_title']" :hashtags="$card['share_hashtags']" />
        </div>
      </article>
    @endforeach
  </div>

  <x-pagination :paginator="$storyPaginator" noun="stories" prefix="st-pagination" />
</section>

@if (count($videos['items'] ?? []) > 0)
<section class="st-videos" id="st-videos" aria-labelledby="st-videos-h">
  <div class="container-x">
    <div class="st-videos-head">
      <span class="chapter"><b>{{ $videos['chapter'] ?? '05' }}</b> · Video testimonials</span>
      <h2 id="st-videos-h" data-aos="fade-up">{!! $videos['title'] ?? '' !!}</h2>
      <p class="st-videos-sub" data-aos="fade-up" data-aos-delay="100">{{ $videos['subtitle'] ?? '' }}</p>
    </div>
    <div class="st-videos-grid" data-st-videos>
      @foreach ($videos['items'] as $video)
        <button type="button" class="st-video" data-video-id="{{ $video['video_id'] }}" data-video-title="{{ $video['title'] }}">
          <div class="st-video-poster" style="background-image:url('{{ $video['poster'] }}');">
            <span class="st-video-len">{{ $video['length'] }}</span>
          </div>
          <div class="st-video-body">
            <span class="st-video-cat">{{ $video['category'] }}</span>
            <h3>{!! $video['heading'] !!}</h3>
            <p>{{ $video['description'] }}</p>
          </div>
        </button>
      @endforeach
    </div>
  </div>
</section>
@endif

</main>
@endsection
