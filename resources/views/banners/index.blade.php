@extends('layouts.app')

@section('title', 'Banners')

@section('content')
<main id="main" class="container-x section">
  <h1>Banners</h1>
  <div class="hub-grid">
    @foreach ($banners as $banner)
    <article class="resource">
      @if ($banner['desktop_image'] !== '')
      <img src="{{ $banner['desktop_image'] }}" alt="Banner" loading="lazy">
      @endif
      @if ($banner['link'])
      <a href="{{ $banner['link'] }}">Open link</a>
      @endif
    </article>
    @endforeach
  </div>
</main>
@endsection
