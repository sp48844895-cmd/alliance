@extends('layouts.app')

@section('title', $pageContent['meta_title'] ?? 'Programs and Initiatives · ChhattisgarhABC')
@section('meta_description', $pageContent['meta_description'] ?? 'Flagship SBC programs and initiatives across Chhattisgarh — youth networks, learning resources and community-led behaviour change.')

@section('content')
<main id="main">
  @include('sections.home.programs')
</main>
@endsection
