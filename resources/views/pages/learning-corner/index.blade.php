@extends('layouts.app')

@section('title', 'Learning Corner · ChhattisgarhABC')
@section('meta_description', 'Browse learning topics — books, videos, posters and training modules for social and behaviour change in Chhattisgarh.')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
@endpush

@section('content')
<main id="main" class="lc-page">
  <h1 class="sr-only">Learning Corner</h1>

  @include('pages.learning-corner.partials.layout-top')

    @if (count($mainCategories) > 0)
      <div class="lc-topic-grid">
        @foreach ($mainCategories as $node)
          <a href="{{ route('learning-corner.main', $node->id) }}"
             class="lc-topic-card"
             aria-label="Open {{ e($node->cat_name) }}">
            <span class="lc-topic-card__icon">
              @include('partials.lucide-icon', ['class' => $node->cat_icon ?: 'icon-folder'])
            </span>
            <span class="lc-topic-card__body">
              <span class="lc-topic-card__name">{{ $node->cat_name }}</span>
              @if (!empty($node->description))
                <span class="lc-topic-card__desc">{{ \Illuminate\Support\Str::limit($node->description, 100) }}</span>
              @endif
              <span class="lc-topic-card__meta">
                @if (count($node->children) > 0)
                  <span>{{ count($node->children) }} {{ count($node->children) === 1 ? 'subtopic' : 'subtopics' }}</span>
                @endif
                @if ($node->total_count > 0)
                  <span>{{ $node->total_count }} {{ $node->total_count === 1 ? 'resource' : 'resources' }}</span>
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

  </div>
</div>

</main>
@endsection
