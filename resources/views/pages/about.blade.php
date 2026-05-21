@extends('layouts.app')

@section('title', $pageContent['meta_title'] ?? 'About the Alliance · ChhattisgarhABC')
@section('meta_description', $pageSections['meta']['meta_description'] ?? 'ChhattisgarhABC is an open, non-financial alliance of youth, professionals, civil society and government — co-creating Social & Behaviour Change Communication across Chhattisgarh, with a deep focus on PVTG villages.')

@section('content')
<main id="main">
@include('sections.about.hero')
@include('sections.about.vision_mission')
@include('sections.about.approach')
@include('sections.about.voices')
@include('sections.about.partners')
</main>
@endsection
