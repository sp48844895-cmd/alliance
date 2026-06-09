@extends('layouts.app')

@section('title', $sub->cat_name.' · '.$main->cat_name.' · Learning Corner')
@section('meta_description', 'Learning resources for '.$sub->cat_name.' under '.$main->cat_name.'.')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
@endpush

@section('content')
<main id="main" class="lc-page">
  <h1 class="sr-only">{{ $sub->cat_name }}</h1>

  @include('pages.learning-corner.partials.layout-top')

    <div class="lc-page-toolbar">
      @include('pages.learning-corner.partials.breadcrumb', [
        'items' => [
          ['label' => 'Topics', 'url' => route('learning-corner')],
          ['label' => $main->cat_name, 'url' => route('learning-corner.main', $main->id)],
          ['label' => $sub->cat_name],
        ],
      ])
    </div>

    @if (count($resources) > 0)
      <div class="lc-resource-grid">
        @foreach ($resources as $resource)
          @include('pages.learning-corner.partials.resource-card', ['resource' => $resource])
        @endforeach
      </div>
    @else
      <div class="lc-empty lc-empty--page">
        <i class="bi bi-inbox" aria-hidden="true"></i>
        <h3>No resources yet</h3>
        <p>This subtopic does not have any learning material uploaded yet.</p>
        <a href="{{ route('learning-corner.main', $main->id) }}" class="lc-btn">Back to subtopics</a>
      </div>
    @endif

  </div>
</div>

</main>
@endsection
