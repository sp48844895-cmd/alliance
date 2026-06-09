@extends('layouts.app')

@section('title', $metaTitle ?? 'Programs and Initiatives · ChhattisgarhABC')
@section('meta_description', $metaDescription ?? 'Flagship SBC programs and initiatives across Chhattisgarh — youth networks, learning resources and community-led behaviour change.')

@section('content')
<main id="main">

{{-- Section: programs (same markup as home page) --}}
@include('programs.partials.programs-section')

</main>
@endsection
