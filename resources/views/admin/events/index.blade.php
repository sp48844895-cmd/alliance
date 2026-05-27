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
    <form method="GET" action="{{ route('admin.events.index') }}" class="card p-4 mb-5">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            <div class="md:col-span-8">
                <label class="label" for="status">Status</label>
                <select id="status" name="status" class="select">
                    <option value="">All statuses</option>
                    <option value="1" @selected($status === '1')>Active</option>
                    <option value="0" @selected($status === '0')>Inactive</option>
                </select>
            </div>
            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-1">
                    <i class="bi bi-funnel"></i>
                    <span>Apply filters</span>
                </button>
                @if ($status !== '')
                    <a href="{{ route('admin.events.index') }}" class="btn btn-ghost btn-sm" title="Clear">Clear</a>
                @endif
            </div>
        </div>
    </form>

    <x-admin.datatable-card
        :empty="$events->isEmpty()"
        empty-icon="bi-calendar-event"
        empty-title="No events yet"
        empty-text="Create your first event or loosen your filters.">
        <x-slot:emptyAction>
            <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i>
                New event
            </a>
        </x-slot:emptyAction>
        <table data-admin-datatable class="table w-full">
            <thead>
                <tr>
                    <th class="no-sort w-14">Image</th>
                    <th>Event</th>
                    <th>When & where</th>
                    <th>Status</th>
                    <th class="no-sort col-actions text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $row)
                    @php
                        $eventImagePath = app(\App\Services\EventPageService::class)->resolveImageUrl((string) ($row->event_image ?? ''));
                    @endphp
                    <tr>
                        <td>
                            <x-admin-image
                                :src="$eventImagePath"
                                class="w-10 h-10 rounded-md object-cover border border-[var(--color-line)]"
                                icon="bi-image"
                                alt="{{ $row->event_name }}" />
                        </td>
                        <td>
                            <div class="font-medium text-[var(--color-ink-2)]">{{ $row->event_name }}</div>
                        </td>
                        <td>
                            <div class="text-sm text-[var(--color-ink-2)]">
                                {{ $row->start_date ? date('d M Y', strtotime($row->start_date)) : '—' }}
                            </div>
                            <div class="text-xs text-[var(--color-mute)] mt-0.5">
                                <i class="bi bi-clock"></i> {{ $row->time }}
                            </div>
                            <div class="text-xs text-[var(--color-mute-2)] mt-0.5">{{ $row->location }}</div>
                        </td>
                        <td data-status-cell>
                            <x-admin.status-pill :active="(bool) $row->event_status" />
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.events.show', $row->id) }}" class="btn btn-ghost btn-sm" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.events.edit', $row->id) }}" class="btn btn-ghost btn-sm" title="Edit">
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
    </x-admin.datatable-card>
@endsection
