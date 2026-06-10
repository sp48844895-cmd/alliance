@extends('layouts.app')

@section('title', $story['title'].' · Stories · ChhattisgarhABC')
@section('meta_description', $story['lede'] ?? $story['title'])

@section('content')
<main id="main" class="st-article-page st-article-page--simple">
  <section class="st-article-simple" aria-labelledby="st-article-title">
    <div class="container-x">
      <a href="{{ route('stories') }}" class="st-article-back">← Back to stories</a>

      <div class="st-article-layout st-article-layout--detail">
        <div class="st-article-content">
          <header class="st-article-simple-head">
            <h1 id="st-article-title" class="st-article-title">{{ $story['title'] }}</h1>
            <p class="st-article-simple-meta">
              @if (!empty($story['author']))
                <span>{{ $story['author'] }}</span>
              @endif
              @if (!empty($story['published_label']))
                <span>{{ $story['published_label'] }}</span>
              @endif
              @if (!empty($story['category']))
                <span>{{ $story['category'] }}</span>
              @endif
            </p>
          </header>

          @if (!empty($story['hero_image']))
            <figure class="st-article-simple-figure">
              <img src="{{ $story['hero_image'] }}" alt="{{ $story['title'] }}">
            </figure>
          @endif

          <x-story-share-links :story="$story" />

          <div class="st-article-simple-body story-html-content">
            @if (!empty($story['body_html']))
              {!! $story['body_html'] !!}
            @elseif (!empty($story['content']))
              {!! $story['content'] !!}
            @endif
          </div>
        </div>

        <aside class="st-article-rail" aria-label="More stories">
          <div class="st-article-rail-inner">
            @if (!empty($relatedStories))
              <div class="st-article-panel st-article-panel--related">
                <h2 id="st-related-stories-h" class="st-article-panel-title">Related stories</h2>
                <div class="st-article-related-grid st-article-related-grid--rail">
                  @foreach ($relatedStories as $item)
                    <a href="{{ route('stories.show', $item['slug']) }}" class="st-article-related-link">
                      @if (!empty($item['hero_image']))
                        <div class="st-article-related-media">
                          <img src="{{ $item['hero_image'] }}" alt="{{ $item['title'] }}">
                        </div>
                      @endif
                      <div class="st-article-related-copy">
                        @if (!empty($item['published_label']))
                          <span class="st-article-related-meta">{{ $item['published_label'] }}</span>
                        @endif
                        <h3>{{ $item['title'] }}</h3>
                        @if (!empty($item['author']))
                          <p class="st-article-related-authored">{{ $item['author'] }}</p>
                        @endif
                        <span class="st-article-related-cta">Read story →</span>
                      </div>
                    </a>
                  @endforeach
                </div>
              </div>
            @endif

            <div class="st-article-panel">
              <h2 id="st-recent-stories-h" class="st-article-panel-title">Recent stories</h2>
              @if (!empty($recentStories))
                <ul class="st-article-rail-stories">
                  @foreach ($recentStories as $item)
                    <li>
                      <a href="{{ $item['url'] }}">
                        <span class="st-article-rail-stories-date">{{ $item['date_label'] ?? ($item['day'].' '.$item['month']) }}</span>
                        <strong>{{ $item['title'] }}</strong>
                        @if (!empty($item['category']))
                          <span>{{ $item['category'] }}</span>
                        @endif
                      </a>
                    </li>
                  @endforeach
                </ul>
              @else
                <p class="st-article-panel-empty">No other stories yet.</p>
              @endif
              <a href="{{ route('stories') }}" class="st-article-rail-more">View all stories →</a>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </section>
</main>
@endsection
