@extends('layouts.admin')

@section('title', 'Events')
@section('page_title', 'Events')
@section('breadcrumb')
    <span class="text-[var(--color-ink-2)]">Events</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i>
        New event
    </a>
@endsection

@section('content')
<div class="space-y-5">

    <form method="GET" action="{{ route('admin.events.index') }}" class="card p-4">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-7">
                <label class="label" for="q">Search</label>
                <input type="text" id="q" name="q" value="{{ $q }}"
                    placeholder="Event name or location..." class="input">
            </div>
            <div class="md:col-span-3">
                <label class="label" for="status">Status</label>
                <select id="status" name="status" class="select">
                    <option value="">All</option>
                    <option value="1" @selected($status === '1')>Active</option>
                    <option value="0" @selected($status === '0')>Inactive</option>
                </select>
            </div>
            <div class="md:col-span-2 flex items-end gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                @if ($q !== '' || $status !== '')
                    <a href="{{ route('admin.events.index') }}" class="btn btn-ghost" title="Reset">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>

    @if ($events->total() === 0)
        <div class="card p-10 text-center">
            <div class="w-14 h-14 mx-auto rounded-full bg-[var(--color-clay-50)] flex items-center justify-center mb-4">
                <i class="bi bi-calendar-event text-2xl text-[var(--color-clay-500)]"></i>
            </div>
            <div class="font-display text-lg text-[var(--color-ink-2)] mb-1">No events yet</div>
            <p class="text-sm text-[var(--color-mute)] mb-4">Create your first event to see it here.</p>
            <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-sm inline-flex">
                <i class="bi bi-plus-lg"></i> New event
            </a>
        </div>
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-12">#</th>
                            <th class="w-16">Image</th>
                            <th>Event</th>
                            <th>Location</th>
                            <th>Start date</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $row)
                            @php
                                $eventImagePath = app(\App\Services\EventPageService::class)->resolveImageUrl((string) ($row->event_image ?? ''));
                            @endphp
                            <tr>
                                <td class="text-[var(--color-mute)] font-mono text-xs">{{ $row->id }}</td>
                                <td>
                                    <x-admin-image
                                        :src="$eventImagePath"
                                        class="w-10 h-10 rounded-md object-cover border border-[var(--color-line)]"
                                        icon="bi-image"
                                        alt="{{ $row->event_name }}" />
                                </td>
                                <td>
                                    <div class="font-medium text-[var(--color-ink-2)]">{{ $row->event_name }}</div>
                                    <div class="text-xs text-[var(--color-mute-2)] mt-0.5">
                                        <i class="bi bi-clock"></i> {{ $row->time }}
                                    </div>
                                </td>
                                <td class="text-sm text-[var(--color-mute)]">{{ $row->location }}</td>
                                <td class="text-sm text-[var(--color-ink-2)]">
                                    {{ $row->start_date ? date('d M Y', strtotime($row->start_date)) : '—' }}
                                </td>
                                <td>
                                    <x-admin.status-pill :active="(bool) $row->event_status" />
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.events.show', $row->id) }}"
                                            class="btn btn-ghost btn-sm" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.events.edit', $row->id) }}"
                                            class="btn btn-ghost btn-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <x-admin.toggle-form
                                            :action="route('admin.events.toggle', $row->id)"
                                            :active="(bool) $row->event_status" />
                                        <x-admin.delete-form
                                            :action="route('admin.events.destroy', $row->id)"
                                            message="Delete this event? This cannot be undone."
                                            buttonClass="btn btn-ghost btn-sm"
                                            iconClass="text-[var(--color-flame)]" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div>{{ $events->links() }}</div>
    @endif
</div>
@endsection
