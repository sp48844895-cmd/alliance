@extends('layouts.admin')

@section('title', 'Edit event')
@section('page_title', 'Edit event')
@section('breadcrumb')
    <a href="{{ route('admin.events.index') }}">Events</a>
    <i class="bi bi-chevron-right text-[10px]"></i>
    <a href="{{ route('admin.events.show', $event->id) }}">{{ \Illuminate\Support\Str::limit($event->event_name, 40) }}</a>
    <i class="bi bi-chevron-right text-[10px]"></i>
    <span class="text-[var(--color-ink-2)]">Edit</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-ghost btn-sm">
        <i class="bi bi-eye"></i> View
    </a>
    <form method="POST" action="{{ route('admin.events.destroy', $event->id) }}"
        data-confirm="Delete this event? This cannot be undone.">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">
            <i class="bi bi-trash"></i> Delete
        </button>
    </form>
@endsection

@section('content')
    @include('admin.events._form')
@endsection
