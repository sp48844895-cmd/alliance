@extends('layouts.admin')

@section('title', 'Edit banner')
@section('breadcrumb')
    <a href="{{ route('admin.banners.index') }}">Banners</a>
    <i class="bi bi-chevron-right text-xs"></i>
    <span class="text-[var(--color-ink-2)]">Edit #{{ $banner->id }}</span>
@endsection
@section('page_title', 'Edit banner')

@section('topbar_actions')
    <a href="{{ route('admin.banners.index') }}" class="btn btn-ghost">
        <i class="bi bi-arrow-left"></i>
        <span>Back</span>
    </a>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.banners.update', $banner->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.banners._form', ['banner' => $banner])
    </form>
@endsection
