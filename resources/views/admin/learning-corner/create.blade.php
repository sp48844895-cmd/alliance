@extends('layouts.admin')

@section('title', 'New resource')
@section('breadcrumb')
    <a href="{{ route('admin.learning-corner.index') }}">Learning Corner</a>
    <i class="bi bi-chevron-right text-[10px]"></i>
    <span class="text-[var(--color-ink-2)]">New</span>
@endsection
@section('page_title', 'New resource')

@section('topbar_actions')
    <a href="{{ route('admin.learning-corner.index') }}" class="btn btn-ghost">
        <i class="bi bi-arrow-left"></i>
        <span>Back</span>
    </a>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.learning-corner.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.learning-corner._form', ['categories' => $categories])
    </form>
@endsection
