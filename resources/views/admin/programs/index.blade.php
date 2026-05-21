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
<div class="space-y-5">

    <form method="GET" action="{{ route('admin.programs.index') }}" class="card p-4">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-8">
                <label class="label" for="q">Search</label>
                <input type="text" id="q" name="q" value="{{ $q }}"
                    placeholder="Program title or tag..." class="input">
            </div>
            <div class="md:col-span-2">
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
                    <a href="{{ route('admin.programs.index') }}" class="btn btn-ghost" title="Reset">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>

    @if ($programs->total() === 0)
        <div class="card p-10 text-center">
            <div class="w-14 h-14 mx-auto rounded-full bg-[var(--color-clay-50)] flex items-center justify-center mb-4">
                <i class="bi bi-layout-text-window text-2xl text-[var(--color-clay-500)]"></i>
            </div>
            <div class="font-display text-lg text-[var(--color-ink-2)] mb-1">No programs yet</div>
            <p class="text-sm text-[var(--color-mute)] mb-4">Create your first program to display it on the home page.</p>
            <a href="{{ route('admin.programs.create') }}" class="btn btn-primary btn-sm inline-flex">
                <i class="bi bi-plus-lg"></i> New program
            </a>
        </div>
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-12">#</th>
                            <th>Title</th>
                            <th>Tag</th>
                            <th>Card style</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($programs as $row)
                            <tr>
                                <td class="text-[var(--color-mute)] font-mono text-xs">{{ $row->id }}</td>
                                <td>
                                    <div class="font-medium text-[var(--color-ink-2)]">{{ $row->title }}</div>
                                    <div class="text-xs text-[var(--color-mute-2)] mt-0.5 line-clamp-1">{{ Str::limit($row->short_desc, 80) }}</div>
                                </td>
                                <td class="text-sm text-[var(--color-mute)]">{{ $row->tag ?: '—' }}</td>
                                <td>
                                    <span class="pill pill-teal">{{ $row->card_style }}</span>
                                </td>
                                <td class="text-sm text-[var(--color-ink-2)]">{{ $row->sort_order }}</td>
                                <td>
                                    <x-admin.status-pill :active="(bool) $row->status" />
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.programs.edit', $row->id) }}"
                                            class="btn btn-ghost btn-sm" title="Edit">
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
            </div>
        </div>

        <div>{{ $programs->links() }}</div>
    @endif
</div>
@endsection
