@extends('layouts.admin')

@section('title', 'New Program')
@section('page_title', 'New Program')
@section('breadcrumb')
    <a href="{{ route('admin.programs.index') }}">Programs</a>
    <i class="bi bi-chevron-right text-xs"></i>
    <span class="text-[var(--color-ink-2)]">New</span>
@endsection

@section('content')
    @include('admin.programs._form')
@endsection
