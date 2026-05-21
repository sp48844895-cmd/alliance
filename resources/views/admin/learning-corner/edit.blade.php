@extends('layouts.admin')

@section('title', 'Edit resource')
@section('breadcrumb')
    <a href="{{ route('admin.learning-corner.index') }}">Learning Corner</a>
    <i class="bi bi-chevron-right text-[10px]"></i>
    <span class="text-[var(--color-ink-2)]">Edit #{{ $resource->id }}</span>
@endsection
@section('page_title', 'Edit resource')

@section('topbar_actions')
    <a href="{{ route('admin.learning-corner.index') }}" class="btn btn-ghost">
        <i class="bi bi-arrow-left"></i>
        <span>Back</span>
    </a>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.learning-corner.update', $resource->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.learning-corner._form', ['categories' => $categories, 'resource' => $resource])
    </form>
@endsection
