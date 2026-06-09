@extends('layouts.admin')

@section('title', 'Edit Slider Slide')
@section('page_title', 'Edit Slider Slide')
@section('breadcrumb')
    <a href="{{ route('admin.home-slider.index') }}">Home slider</a>
    <i class="bi bi-chevron-right text-xs"></i>
    <span class="text-[var(--color-ink-2)]">Edit</span>
@endsection

@section('content')
    @include('admin.home-slider._form')
@endsection
