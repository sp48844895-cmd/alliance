@extends('layouts.admin')

@section('title', 'New event')
@section('page_title', 'Create event')
@section('breadcrumb')
    <a href="{{ route('admin.events.index') }}">Events</a>
    <i class="bi bi-chevron-right text-xs"></i>
    <span class="text-[var(--color-ink-2)]">Create</span>
@endsection

@section('content')
    @include('admin.events._form')
@endsection
