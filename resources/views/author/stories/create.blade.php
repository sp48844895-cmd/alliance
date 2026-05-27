@extends('layouts.author')

@section('title', 'New story')
@section('page_title', 'New story')

@section('breadcrumb')
    <a href="{{ route('author.stories.index') }}">Stories</a>
    <i class="bi bi-chevron-right text-xs"></i>
    <span>New</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('author.stories.index') }}" class="btn btn-ghost btn-sm">
        <i class="bi bi-arrow-left"></i>
        <span>Back</span>
    </a>
@endsection

@section('content')
    @include('author.stories._form')
@endsection
