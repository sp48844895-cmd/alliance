@extends('layouts.admin')

@section('title', 'Members')
@section('page_title', 'Members')
@section('breadcrumb')
    <span>Members</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.memberships.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i> Add member
    </a>
    <a href="{{ route('admin.memberships.export', request()->query()) }}" class="btn btn-ghost btn-sm">
        <i class="bi bi-download"></i> Export CSV
    </a>
@endsection

@section('content')
    <form method="GET" action="{{ route('admin.memberships.index') }}" class="card p-4 mb-5">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            <div class="md:col-span-3">
                <label class="label" for="type">Member type</label>
                <select id="type" name="type" class="select">
                    <option value="">All types</option>
                    <option value="Individual" @selected($type === 'Individual')>Individual</option>
                    <option value="CSO/NGO" @selected($type === 'CSO/NGO')>CSO/NGO</option>
                    <option value="Volunteer" @selected($type === 'Volunteer')>Volunteer</option>
                    <option value="Firm/Organization" @selected($type === 'Firm/Organization')>Firm/Organization</option>
                </select>
            </div>
            <div class="md:col-span-3">
                <label class="label" for="district_id">District</label>
                <select id="district_id" name="district_id" class="select">
                    <option value="">All districts</option>
                    @foreach ($districts as $d)
                        <option value="{{ $d->id }}" @selected((string) $district_id === (string) $d->id)>{{ $d->district_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3">
                <label class="label" for="block_id">Block</label>
                <select id="block_id" name="block_id" class="select">
                    <option value="">All blocks</option>
                    @foreach ($blocks as $b)
                        <option value="{{ $b->id }}" @selected((string) $block_id === (string) $b->id)>
                            {{ $b->block_name }}{{ $b->district_name ? ' · ' . $b->district_name : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3 flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-1">
                    <i class="bi bi-funnel"></i> Apply filters
                </button>
                @if ($type !== '' || $district_id !== '' || $block_id !== '')
                    <a href="{{ route('admin.memberships.index') }}" class="btn btn-ghost btn-sm" title="Clear filters">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>

    <x-admin.datatable-card
        :empty="$members->isEmpty()"
        empty-icon="bi-people"
        empty-title="No members found"
        empty-text="Try different filters or add a member.">
        <table data-admin-datatable class="table w-full">
            <thead>
                <tr>
                    <th class="no-sort w-14">Photo</th>
                    <th>Member</th>
                    <th>Location & type</th>
                    <th>Joined</th>
                    <th class="no-sort col-actions text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($members as $row)
                    @php
                        $typeMap = [
                            'Individual' => 'pill-river',
                            'individual' => 'pill-river',
                            'CSO/NGO' => 'pill-leaf',
                            'Volunteer' => 'pill-amber',
                            'Firm/Organization' => 'pill-clay',
                        ];
                        $typeClass = $typeMap[$row->type] ?? 'pill-mute';
                        $districtName = $districtMap[(int) $row->district] ?? '—';
                    @endphp
                    <tr>
                        <td>
                            @if (!empty($row->image_url))
                                <img src="{{ $row->image_url }}" alt="" class="w-9 h-9 rounded-full object-cover border border-[var(--color-line)]">
                            @else
                                <div class="w-9 h-9 rounded-full bg-[var(--color-clay-100)] text-[var(--color-clay-700)] font-semibold flex items-center justify-center text-xs">
                                    {{ strtoupper(substr($row->name ?? '?', 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="font-medium text-[var(--color-ink-2)]">{{ $row->name }}</div>
                            <div class="text-xs text-[var(--color-mute)]">{{ $row->email }}</div>
                            <div class="text-xs text-[var(--color-mute-2)] mt-0.5">{{ $row->mobile }} · <span class="font-mono">{{ $row->code }}</span></div>
                        </td>
                        <td>
                            <span class="pill {{ $typeClass }}">{{ $row->type ?: '—' }}</span>
                            <div class="text-xs text-[var(--color-mute)] mt-1">{{ $districtName }}</div>
                        </td>
                        <td class="text-xs text-[var(--color-mute)] whitespace-nowrap">
                            {{ $row->date ? date('d M Y', strtotime($row->date)) : '—' }}
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.memberships.show', $row->id) }}" class="btn btn-ghost btn-sm" title="View"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.memberships.edit', $row->id) }}" class="btn btn-ghost btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>
                                <x-admin.delete-form
                                    :action="route('admin.memberships.destroy', $row->id)"
                                    message="Delete this member? This cannot be undone."
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
