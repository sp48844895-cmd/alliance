@extends('layouts.app')

@section('title', $main->cat_name.' · Learning Corner · ChhattisgarhABC')
@section('meta_description', 'Browse subtopics under '.$main->cat_name.' — learning resources from ChhattisgarhABC.')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
@endpush

@section('content')
<main id="main" class="lc-page">
  <h1 class="sr-only">{{ $main->cat_name }}</h1>

  @include('pages.learning-corner.partials.layout-top')

    <div class="lc-page-toolbar">
      @include('pages.learning-corner.partials.breadcrumb', [
        'items' => [
          ['label' => 'Topics', 'url' => route('learning-corner')],
          ['label' => $main->cat_name],
        ],
      ])
    </div>

    @if (count($subcategories) > 0)
      <div class="lc-subtopic-grid">
        @foreach ($subcategories as $sub)
          <a href="{{ route('learning-corner.sub', ['main' => $main->id, 'sub' => $sub->id]) }}"
             class="lc-subtopic-card"
             aria-label="Open {{ e($sub->cat_name) }}">
            <span class="lc-subtopic-card__icon">
              @include('partials.lucide-icon', ['class' => $sub->cat_icon ?: 'icon-folder'])
            </span>
            <span class="lc-subtopic-card__body">
              <span class="lc-subtopic-card__name">{{ $sub->cat_name }}</span>
              @if (!empty($sub->description))
                <span class="lc-subtopic-card__desc">{{ \Illuminate\Support\Str::limit($sub->description, 80) }}</span>
              @endif
              <span class="lc-subtopic-card__count">
                {{ (int) $sub->resource_count }} {{ (int) $sub->resource_count === 1 ? 'resource' : 'resources' }}
              </span>
            </span>
            <span class="lc-subtopic-card__arrow" aria-hidden="true"><i class="bi bi-arrow-right"></i></span>
          </a>
        @endforeach
      </div>
    @else
      <div class="lc-empty lc-empty--page">
        <i class="bi bi-inbox" aria-hidden="true"></i>
        <h3>No subtopics yet</h3>
        <p>This category does not have any subtopics. Go back and try another main topic.</p>
        <a href="{{ route('learning-corner') }}" class="lc-btn">All topics</a>
      </div>
    @endif

  </div>
</div>

</main>
@endsection
