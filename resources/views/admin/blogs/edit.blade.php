@extends('layouts.admin')

@section('title', 'Edit story')
@section('page_title', 'Edit story')

@section('breadcrumb')
    <a href="{{ route('admin.blogs.index') }}">Stories</a>
    <i class="bi bi-chevron-right text-xs"></i>
    <span>Edit</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-ghost btn-sm">
        <i class="bi bi-arrow-left"></i>
        <span>Back to stories</span>
    </a>
@endsection

@section('content')
    @include('admin.blogs._form')
@endsection
