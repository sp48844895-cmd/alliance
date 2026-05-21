@extends('layouts.app')

@section('title', $pageContent['meta_title'] ?? 'Get Involved · ChhattisgarhABC')
@section('meta_description', $pageContent['meta_description'] ?? 'Four pathways to join the alliance — individual professional, volunteer, CSO/NGO or firm/organization. Pick yours and support social and behavioural change communication.')

@section('content')
<main id="main">
    @include('pages.partials.get-involved.hero')
    @include('pages.partials.get-involved.join')
</main>
@endsection
