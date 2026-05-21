@extends('layouts.app')

@section('title', $story['title'].' · Stories · ChhattisgarhABC')
@section('meta_description', $story['lede'])

@section('content')
<main id="main" class="st-article-page">
  <section class="st-article-hero" aria-labelledby="st-article-title">
    <div class="container-x">
      <a href="{{ route('stories') }}" class="st-article-back">← Back to stories</a>

      <div class="st-article-shell">
        <div class="st-article-copy">
          <span class="chapter fade-up" data-delay="1"><b>04</b> · {{ $story['category'] }}</span>

          <div class="st-article-chips fade-up" data-delay="2">
            <span class="st-article-chip">{{ $story['theme'] }}</span>
            <span class="st-article-chip">{{ $story['district'] }}</span>
            <span class="st-article-chip">{{ $story['published_label'] }}</span>
          </div>

          <h1 id="st-article-title" class="st-article-title fade-up" data-delay="2">{{ $story['title'] }}</h1>

          <p class="st-article-lede fade-up" data-delay="3">{{ $story['lede'] }}</p>

          <div class="st-article-byline fade-up" data-delay="4">
            <div class="st-article-byline-badge">{{ $story['author_initials'] }}</div>
            <div class="st-article-byline-copy">
              <strong>{{ $story['author'] }}</strong>
              <span>{{ $story['author_role'] }}</span>
            </div>
            <div class="st-article-byline-meta">
              <div class="st-article-byline-item">
                <span>Published</span>
                <strong>{{ $story['published_label'] }}</strong>
              </div>
              <div class="st-article-byline-item">
                <span>Read time</span>
                <strong>{{ $story['read_time'] }}</strong>
              </div>
              <div class="st-article-byline-item">
                <span>Filed from</span>
                <strong>{{ $story['district'] }}</strong>
              </div>
            </div>
          </div>
        </div>

        <figure class="st-article-figure fade-up" data-delay="3">
          <div class="st-article-figure-card">
            <img src="{{ $story['hero_image'] }}" alt="{{ $story['title'] }}">
          </div>
          <figcaption>{{ $story['hero_caption'] }}</figcaption>
        </figure>
      </div>

      <div class="st-article-glance fade-up" data-delay="4">
        @foreach ($story['stats'] as $stat)
          <div class="st-article-glance-card">
            <span>{{ $stat['label'] }}</span>
            <strong>{{ $stat['value'] }}</strong>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="st-article-main">
    <div class="container-x">
      <div class="st-article-layout">
        <aside class="st-article-rail">
          <div class="st-article-panel">
            <span class="chapter"><b>Guide</b> · On this page</span>
            <nav class="st-article-toc">
              <a href="#story-overview" class="st-article-toc-link is-active">Overview</a>
              @foreach ($story['sections'] as $index => $section)
                <a href="#story-section-{{ $index + 1 }}" class="st-article-toc-link">{{ $section['title'] }}</a>
              @endforeach
              <a href="#story-timeline" class="st-article-toc-link">Timeline</a>
              @if (!empty($relatedStories))
                <a href="#story-related" class="st-article-toc-link">Related stories</a>
              @endif
            </nav>
          </div>

          <div class="st-article-panel">
            <span class="chapter"><b>Why it matters</b> · Quick take</span>
            <p class="st-article-panel-copy">{{ $story['why_it_matters'] }}</p>
          </div>

          <div class="st-article-panel">
            <span class="chapter"><b>Field</b> · Highlights</span>
            <ul class="st-article-highlight-list">
              @foreach ($story['highlights'] as $highlight)
                <li>{{ $highlight }}</li>
              @endforeach
            </ul>
          </div>
        </aside>

        <article class="st-article-content">
          <section id="story-overview" class="st-article-overview">
            <p class="st-article-intro">{{ $story['intro'] }}</p>

            <div class="st-article-pullquote">
              <div class="st-article-pullquote-mark">"</div>
              <div class="st-article-pullquote-copy">
                <p>{{ $story['quote']['text'] }}</p>
                <cite>{{ $story['quote']['cite'] }}</cite>
              </div>
            </div>
          </section>

          @foreach ($story['sections'] as $index => $section)
            <section id="story-section-{{ $index + 1 }}" class="st-article-section">
              <div class="st-article-section-step">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</div>
              <div class="st-article-section-copy">
                <h2>{{ $section['title'] }}</h2>
                @foreach ($section['paragraphs'] as $paragraph)
                  <p>{{ $paragraph }}</p>
                @endforeach
              </div>
            </section>
          @endforeach

          <section id="story-timeline" class="st-article-timeline-wrap">
            <div class="st-article-section-heading">
              <span class="chapter"><b>Timeline</b> · How the shift moved</span>
              <h2>From local action to shared habit</h2>
            </div>

            <div class="st-article-timeline">
              @foreach ($story['timeline'] as $item)
                <div class="st-article-timeline-item">
                  <span class="st-article-timeline-label">{{ $item['label'] }}</span>
                  <div class="st-article-timeline-card">
                    <h3>{{ $item['title'] }}</h3>
                    <p>{{ $item['text'] }}</p>
                  </div>
                </div>
              @endforeach
            </div>
          </section>
        </article>
      </div>
    </div>
  </section>

  @if (!empty($relatedStories))
    <section id="story-related" class="st-article-related" aria-labelledby="st-article-related-title">
      <div class="container-x">
        <div class="st-article-related-head">
          <span class="chapter"><b>05</b> · Keep reading</span>
          <h2 id="st-article-related-title">Related stories from the field</h2>
        </div>

        <div class="st-article-related-grid">
          @foreach ($relatedStories as $relatedStory)
            <article class="st-article-related-card">
              <a href="{{ route('stories.show', $relatedStory['slug']) }}" class="st-article-related-link">
                <div class="st-article-related-media">
                  <img src="{{ $relatedStory['hero_image'] }}" alt="{{ $relatedStory['title'] }}">
                </div>
                <div class="st-article-related-copy">
                  <span class="st-article-related-meta">{{ $relatedStory['category'] }} · {{ $relatedStory['district'] }}</span>
                  <h3>{{ $relatedStory['title'] }}</h3>
                  <p>{{ $relatedStory['lede'] }}</p>
                  <span class="st-article-related-cta">Read story →</span>
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
