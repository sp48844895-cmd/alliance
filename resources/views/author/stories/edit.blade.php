@extends('layouts.author')

@section('title', 'Edit story')
@section('page_title', 'Edit story')

@section('breadcrumb')
    <a href="{{ route('author.stories.index') }}">Stories</a>
    <i class="bi bi-chevron-right text-[10px]"></i>
    <span>Edit</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('author.stories.index') }}" class="btn btn-ghost btn-sm">
        <i class="bi bi-arrow-left"></i>
        <span>Back</span>
    </a>
@endsection

@section('content')
    @if(($story->approval_status ?? '') === 'rejected')
        <div class="alert alert-error mb-5">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>This story was rejected. Update and resubmit for admin review.</span>
        </div>
    @endif
    @include('author.stories._form', ['story' => $story])
@endsection
