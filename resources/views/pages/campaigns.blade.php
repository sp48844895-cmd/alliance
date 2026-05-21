@extends('layouts.app')

@section('title', $pageContent['meta_title'] ?? 'Campaigns · From Behaviour to Habit · ChhattisgarhABC')
@section('meta_description', $pageSections['meta']['meta_description'] ?? 'Six in-progress SBC campaigns across Chhattisgarh — Role of Males, Children & Education, Life Cycle Nutrition, Gender & Behaviour, Adolescent Health, and Community Participation. Filter by theme, district, and stage.')

@section('content')
<main id="main">
@include('sections.campaigns.hero')
@include('sections.campaigns.filters')
@include('sections.campaigns.grid')
@include('sections.campaigns.timelines')
@include('sections.campaigns.cta')
</main>
@endsection
