@extends("layouts.app")

@section("title", $pageContent['meta_title'] ?? 'ChhattisgarhABC · Alliance for Behaviour Change Chhattisgarh')
@section("meta_description", $pageSections['meta']['meta_description'] ?? 'ChhattisgarhABC is a community platform where youth, professionals, civil society and government come together to share experiences and advance Social and Behaviour Change Communication (SBC) across Chhattisgarh.')

@section("content")
<main id="main" class="home-page">
@include('sections.home.hero')
@include('sections.home.marquee')
@include('sections.home.intro')
@include('sections.home.programs')
@include('sections.home.champions')
@include('sections.home.events')
@include('sections.home.hub')
@include('sections.home.get-involved-cta')
</main>
@endsection
