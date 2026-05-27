@extends('layouts.app')

@section('title', $story['title'].' · Stories · ChhattisgarhABC')
@section('meta_description', $story['lede'] ?? $story['title'])

@section('content')
<main id="main" class="st-article-page st-article-page--simple">
  <section class="st-article-simple" aria-labelledby="st-article-title">
    <div class="container-x">
      <a href="{{ route('stories') }}" class="st-article-back">← Back to stories</a>

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

      <div class="st-article-simple-body story-html-content">
        @if (!empty($story['body_html']))
          {!! $story['body_html'] !!}
        @elseif (!empty($story['content']))
          {!! $story['content'] !!}
        @endif
      </div>
    </div>
  </section>
</main>
@endsection
