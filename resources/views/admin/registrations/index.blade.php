@extends('layouts.admin')

@section('title', 'Join Applications')
@section('page_title', 'Join Applications')

@section('breadcrumb')
    <span>Join Applications</span>
@endsection

@section('topbar_actions')
    <span class="pill {{ $stats['new'] > 0 ? 'pill-clay' : 'pill-mute' }}">
        <i class="bi bi-inbox"></i>
        New: {{ $stats['new'] }}
    </span>
@endsection

@section('content')
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-5">
        <div class="stat p-4">
            <div class="stat-label">All applications</div>
            <div class="stat-num mt-1">{{ number_format($stats['total']) }}</div>
        </div>
        <div class="stat p-4">
            <div class="stat-label">New</div>
            <div class="stat-num mt-1 text-[var(--color-clay-600)]">{{ number_format($stats['new']) }}</div>
        </div>
        <div class="stat p-4">
            <div class="stat-label">Organisations</div>
            <div class="stat-num mt-1">{{ number_format($stats['partner']) }}</div>
        </div>
        <div class="stat p-4">
            <div class="stat-label">Volunteers</div>
            <div class="stat-num mt-1">{{ number_format($stats['volunteer']) }}</div>
        </div>
        <div class="stat p-4">
            <div class="stat-label">Intern</div>
            <div class="stat-num mt-1">{{ number_format($stats['intern']) }}</div>
        </div>
        <div class="stat p-4">
            <div class="stat-label">Fellowship</div>
            <div class="stat-num mt-1">{{ number_format($stats['fellow']) }}</div>
        </div>
    </div>

    <p class="text-sm text-[var(--color-mute)] mb-4">
        Volunteer and organisation sign-ups from Get Involved appear here after registration.
        Quick filters:
        <a href="{{ route('admin.registrations.index', ['type' => 'volunteer', 'status' => 'new']) }}" class="text-[var(--color-clay-600)] font-semibold hover:underline">New volunteers</a>,
        <a href="{{ route('admin.registrations.index', ['type' => 'partner', 'status' => 'new']) }}" class="text-[var(--color-clay-600)] font-semibold hover:underline">New organisations</a>.
        Message copies also stay in
        <a href="{{ route('admin.contact-messages.index') }}" class="text-[var(--color-clay-600)] font-semibold hover:underline">Contact inbox</a>.
    </p>

    <form method="GET" action="{{ route('admin.registrations.index') }}" class="card p-4 mb-5">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            <div class="md:col-span-4">
                <label class="label" for="type">Application type</label>
                <select id="type" name="type" class="select">
                    <option value="">All types</option>
                    @foreach($typeLabels as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['type'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-4">
                <label class="label" for="status">Status</label>
                <select id="status" name="status" class="select">
                    <option value="">All statuses</option>
                    <option value="new" @selected(($filters['status'] ?? '') === 'new')>New</option>
                    <option value="reviewed" @selected(($filters['status'] ?? '') === 'reviewed')>Reviewed</option>
                    <option value="accepted" @selected(($filters['status'] ?? '') === 'accepted')>Accepted</option>
                    <option value="rejected" @selected(($filters['status'] ?? '') === 'rejected')>Rejected</option>
                </select>
            </div>
            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-1">
                    <i class="bi bi-funnel"></i>
                    <span>Apply filters</span>
                </button>
                <a href="{{ route('admin.registrations.index') }}" class="btn btn-ghost btn-sm">Clear</a>
            </div>
        </div>
    </form>

    <x-admin.datatable-card
        :empty="$registrations->isEmpty()"
        empty-icon="bi-inbox"
        empty-title="No applications yet"
        empty-text="Organisation, volunteer, intern and fellowship registrations will appear here.">
        <table data-admin-datatable class="table w-full">
            <thead>
                <tr>
                    <th>Applicant</th>
                    <th>Application</th>
                    <th>Status</th>
                    <th>Account</th>
                    <th>Submitted</th>
                    <th class="no-sort col-actions text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $row)
                    @php
                        $pill = match($row->status) {
                            'accepted' => 'pill-leaf',
                            'rejected' => 'pill-mute',
                            'reviewed' => 'pill-river',
                            default => 'pill-clay',
                        };
                    @endphp
                    <tr class="{{ $row->status === 'new' ? 'bg-[var(--color-clay-50)]' : '' }}">
                        <td>
                            <div class="font-semibold text-[var(--color-ink-2)]">{{ $row->full_name }}</div>
                            <div class="text-xs text-[var(--color-mute)]">{{ $row->email }}</div>
                        </td>
                        <td>
                            <span class="pill pill-mute">{{ $typeLabels[$row->type] ?? ucfirst($row->type) }}</span>
                            <div class="text-xs text-[var(--color-mute)] mt-1">{{ $row->institution ?: 'No institution' }}</div>
                        </td>
                        <td><span class="pill {{ $pill }}">{{ ucfirst($row->status) }}</span></td>
                        <td>
                            @if($row->user_id)
                                @if($row->user_is_active)
                                    <span class="pill pill-leaf">Active</span>
                                @else
                                    <span class="pill pill-clay">Pending</span>
                                @endif
                            @else
                                <span class="text-xs text-[var(--color-mute)]">—</span>
                            @endif
                        </td>
                        <td class="text-xs text-[var(--color-mute)] whitespace-nowrap">
                            {{ \Illuminate\Support\Carbon::parse($row->created_at)->format('d M Y') }}
                        </td>
                        <td>
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('admin.registrations.show', $row->id) }}" class="btn btn-ghost btn-sm" title="View"><i class="bi bi-eye"></i></a>
                                <form method="POST" action="{{ route('admin.registrations.destroy', $row->id) }}" class="inline" data-confirm="Delete this application?">@csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="bi bi-trash"></i></button></form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-admin.datatable-card>
@endsection
