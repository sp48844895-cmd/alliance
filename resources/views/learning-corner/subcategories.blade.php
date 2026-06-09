@extends('layouts.app')

@section('title', $main['name'].' · Learning Corner · ChhattisgarhABC')
@section('meta_description', 'Browse subtopics under '.$main['name'].' — learning resources from ChhattisgarhABC.')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
@endpush

@section('content')
<main id="main" class="lc-page">
  <h1 class="sr-only">{{ $main['name'] }}</h1>

  @include('learning-corner.partials.layout-top')

  <div class="lc-page-toolbar">
    @include('learning-corner.partials.breadcrumb', [
      'items' => [
        ['label' => 'Topics', 'url' => route('learning-corner')],
        ['label' => $main['name']],
      ],
    ])
  </div>

  @if (count($subcategories) > 0)
    <div class="lc-subtopic-grid">
      @foreach ($subcategories as $sub)
        <a href="{{ $sub['url'] }}" class="lc-subtopic-card" aria-label="Open {{ $sub['name'] }}">
          <span class="lc-subtopic-card__icon">
            @include('partials.lucide-icon', ['class' => $sub['icon']])
          </span>
          <span class="lc-subtopic-card__body">
            <span class="lc-subtopic-card__name">{{ $sub['name'] }}</span>
            @if ($sub['description_short'] !== '')
              <span class="lc-subtopic-card__desc">{{ $sub['description_short'] }}</span>
            @endif
            <span class="lc-subtopic-card__count">
              {{ $sub['resource_count'] }} resources
            </span>
          </span>
          <span class="lc-subtopic-card__arrow" aria-hidden="true"><i class="bi bi-arrow-right"></i></span>
        </a>
      @endforeach
    </div>
  @else
    <div class="lc-empty lc-empty--page">
      <h3>No subtopics yet</h3>
      <p>Subtopics for this topic will appear here once published.</p>
    </div>
  @endif
</main>
@endsection
