@extends('layouts.admin')

@section('title', $event->event_name)
@section('page_title', $event->event_name)
@section('breadcrumb')
    <a href="{{ route('admin.events.index') }}">Events</a>
    <i class="bi bi-chevron-right text-xs"></i>
    <span class="text-[var(--color-ink-2)]">{{ \Illuminate\Support\Str::limit($event->event_name, 40) }}</span>
@endsection

@section('topbar_actions')
    @if ($publicUrl)
        <a href="{{ $publicUrl }}" class="btn btn-ghost btn-sm" target="_blank" rel="noopener">
            <i class="bi bi-box-arrow-up-right"></i> View on site
        </a>
    @endif
    <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-ghost btn-sm">
        <i class="bi bi-pencil"></i> Edit
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
<div class="space-y-5 stagger">

    <div class="card grain p-6 lg:p-8">
        <div class="flex flex-col lg:flex-row items-start gap-6">
            @if ($imageUrl !== '')
                <img src="{{ $imageUrl }}" alt="{{ $event->event_name }}"
                    class="w-40 h-40 rounded-lg object-cover border-2 border-[var(--color-clay-100)] shrink-0">
            @else
                <div class="w-40 h-40 rounded-lg bg-[var(--color-clay-50)] text-[var(--color-clay-500)] flex items-center justify-center shrink-0">
                    <i class="bi bi-calendar-event text-4xl"></i>
                </div>
            @endif

            <div class="flex-1 min-w-0">
                <h2 class="font-display text-2xl text-[var(--color-ink-2)] leading-tight">
                    {{ $event->event_name }}
                </h2>
                <div class="flex flex-wrap items-center gap-2 mt-2">
                    @if ($event->event_status)
                        <span class="pill pill-leaf">Active</span>
                    @else
                        <span class="pill pill-flame">Inactive</span>
                    @endif
                    <span class="text-xs text-[var(--color-mute)]">
                        <i class="bi bi-person"></i> {{ $event->admin ?: 'Admin' }}
                    </span>
                </div>
                <div class="flex flex-wrap gap-4 mt-4 text-sm text-[var(--color-ink-2)]">
                    <span>
                        <i class="bi bi-geo-alt text-[var(--color-mute)]"></i>
                        {{ $event->location }}
                    </span>
                    <span>
                        <i class="bi bi-calendar3 text-[var(--color-mute)]"></i>
                        {{ $event->start_date ? date('d M Y', strtotime($event->start_date)) : '—' }}
                    </span>
                    <span>
                        <i class="bi bi-clock text-[var(--color-mute)]"></i>
                        {{ $event->time ?: '—' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <div class="card p-5 lg:p-6 space-y-4">
            <h3 class="font-display text-lg text-[var(--color-ink-2)]">Schedule</h3>

            <div>
                <div class="label">Start date</div>
                <div class="text-sm text-[var(--color-ink-2)]">
                    {{ $event->start_date ? date('d M Y', strtotime($event->start_date)) : '—' }}
                </div>
            </div>

            <div>
                <div class="label">Display date</div>
                <div class="text-sm text-[var(--color-ink-2)]">{{ $event->date ?: '—' }}</div>
            </div>

            <div>
                <div class="label">Time</div>
                <div class="text-sm text-[var(--color-ink-2)]">{{ $event->time ?: '—' }}</div>
            </div>

            <div>
                <div class="label">End date / time</div>
                <div class="text-sm text-[var(--color-ink-2)]">{{ $event->end_date ?: '—' }}</div>
            </div>

            <div>
                <div class="label">Location</div>
                <div class="text-sm text-[var(--color-ink-2)]">{{ $event->location ?: '—' }}</div>
            </div>
        </div>

        <div class="card p-5 lg:p-6 space-y-4 lg:col-span-2">
            <h3 class="font-display text-lg text-[var(--color-ink-2)]">Description</h3>
            <div class="text-sm text-[var(--color-ink-2)] leading-relaxed prose prose-sm max-w-none admin-event-description">
                {!! $event->description !!}
            </div>
        </div>
    </div>

    @if (!empty($event->googlemap))
        <div class="card p-5 lg:p-6">
            <h3 class="font-display text-lg text-[var(--color-ink-2)] mb-2">Event link</h3>
            <a href="{{ \Illuminate\Support\Str::startsWith($event->googlemap, ['http://', 'https://']) ? $event->googlemap : '#' }}"
                target="_blank" rel="noopener"
                class="text-sm text-[var(--color-river)] hover:text-[var(--color-clay-500)] break-all">
                <i class="bi bi-link-45deg"></i> {{ $event->googlemap }}
            </a>
        </div>
    @endif
</div>
@endsection
