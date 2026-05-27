@extends('layouts.admin')

@section('title', 'New banner')
@section('breadcrumb')
    <a href="{{ route('admin.banners.index') }}">Banners</a>
    <i class="bi bi-chevron-right text-xs"></i>
    <span class="text-[var(--color-ink-2)]">New</span>
@endsection
@section('page_title', 'New banner')

@section('topbar_actions')
    <a href="{{ route('admin.banners.index') }}" class="btn btn-ghost">
        <i class="bi bi-arrow-left"></i>
        <span>Back</span>
    </a>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.banners._form')
    </form>
@endsection
