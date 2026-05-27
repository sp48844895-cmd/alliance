@extends('layouts.admin')

@section('title', 'Edit Program')
@section('page_title', 'Edit Program')
@section('breadcrumb')
    <a href="{{ route('admin.programs.index') }}">Programs</a>
    <i class="bi bi-chevron-right text-xs"></i>
    <span class="text-[var(--color-ink-2)]">Edit</span>
@endsection

@section('content')
    @include('admin.programs._form')
@endsection
