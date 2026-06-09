@extends('layouts.app')

@section('title', $metaTitle)
@section('meta_description', $metaDescription)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
@endpush

@section('content')
<main id="main" class="lc-page">
  <h1 class="sr-only">Learning Corner</h1>

  @include('learning-corner.partials.layout-top')

  @if (count($mainCategories) > 0)
    <div class="lc-topic-grid">
      @foreach ($mainCategories as $category)
        <a href="{{ $category['url'] }}" class="lc-topic-card" aria-label="Open {{ $category['name'] }}">
          <span class="lc-topic-card__icon">
            @include('partials.lucide-icon', ['class' => $category['icon']])
          </span>
          <span class="lc-topic-card__body">
            <span class="lc-topic-card__name">{{ $category['name'] }}</span>
            @if ($category['description_short'] !== '')
              <span class="lc-topic-card__desc">{{ $category['description_short'] }}</span>
            @endif
            <span class="lc-topic-card__meta">
              @if (count($category['children']) > 0)
                <span>{{ count($category['children']) }} subtopics</span>
              @endif
              @if ($category['total_count'] > 0)
                <span>{{ $category['total_count'] }} resources</span>
              @endif
            </span>
          </span>
          <span class="lc-topic-card__arrow" aria-hidden="true"><i class="bi bi-arrow-right"></i></span>
        </a>
      @endforeach
    </div>
  @else
    <div class="lc-empty lc-empty--page">
      <i class="bi bi-folder-x" aria-hidden="true"></i>
      <h3>No topics yet</h3>
      <p>Learning categories will appear here once they are published.</p>
    </div>
  @endif
</main>
@endsection
