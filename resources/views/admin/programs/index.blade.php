@extends('layouts.admin')

@section('title', 'Programs')
@section('page_title', 'Programs & Initiatives')
@section('breadcrumb')
    <span class="text-[var(--color-ink-2)]">Programs</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.programs.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i>
        New program
    </a>
@endsection

@section('content')
    <form method="GET" action="{{ route('admin.programs.index') }}" class="card p-4 mb-5">
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
                    <a href="{{ route('admin.programs.index') }}" class="btn btn-ghost btn-sm" title="Clear">Clear</a>
                @endif
            </div>
        </div>
    </form>

    <x-admin.datatable-card
        :empty="$programs->isEmpty()"
        empty-icon="bi-layout-text-window"
        empty-title="No programs yet"
        empty-text="Add a program to feature it on the home page.">
        <x-slot:emptyAction>
            <a href="{{ route('admin.programs.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i>
                New program
            </a>
        </x-slot:emptyAction>
        <table data-admin-datatable class="table w-full">
            <thead>
                <tr>
                    <th>Program</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th class="no-sort col-actions text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($programs as $row)
                    <tr>
                        <td>
                            <div class="font-medium text-[var(--color-ink-2)]">{{ $row->title }}</div>
                            <div class="text-xs text-[var(--color-mute-2)] mt-0.5 line-clamp-1">{{ Str::limit($row->short_desc, 80) }}</div>
                        </td>
                        <td>
                            <div class="text-xs text-[var(--color-mute)] space-y-1">
                                <div>{{ $row->tag ?: 'No tag' }}</div>
                                <div><span class="pill pill-teal">{{ $row->card_style }}</span> · Order {{ $row->sort_order }}</div>
                            </div>
                        </td>
                        <td data-status-cell>
                            <x-admin.status-pill :active="(bool) $row->status" />
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.programs.edit', $row->id) }}" class="btn btn-ghost btn-sm" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <x-admin.toggle-form
                                    :action="route('admin.programs.toggle', $row->id)"
                                    :active="(bool) $row->status" />
                                <x-admin.delete-form
                                    :action="route('admin.programs.destroy', $row->id)"
                                    message="Delete this program? This cannot be undone."
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
