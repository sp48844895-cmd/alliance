@extends('layouts.admin')

@section('title', 'Memberships')
@section('page_title', 'Memberships')
@section('breadcrumb')
    <span class="text-[var(--color-ink-2)]">Memberships</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.memberships.create') }}"
        class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i> New member
    </a>
    <a href="{{ route('admin.memberships.export', request()->query()) }}"
        class="btn btn-ghost btn-sm">
        <i class="bi bi-download"></i> Export CSV
    </a>
@endsection

@section('content')
<div class="space-y-5">

    <form method="GET" action="{{ route('admin.memberships.index') }}" class="card p-4">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-4">
                <label class="label" for="q">Search</label>
                <input type="text" id="q" name="q" value="{{ $q }}"
                    placeholder="Name, email, mobile..." class="input">
            </div>
            <div class="md:col-span-2">
                <label class="label" for="type">Type</label>
                <select id="type" name="type" class="select">
                    <option value="">All</option>
                    <option value="Individual" @selected($type === 'Individual')>Individual</option>
                    <option value="CSO/NGO" @selected($type === 'CSO/NGO')>CSO/NGO</option>
                    <option value="Volunteer" @selected($type === 'Volunteer')>Volunteer</option>
                    <option value="Firm/Organization" @selected($type === 'Firm/Organization')>Firm/Organization</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="label" for="district_id">District</label>
                <select id="district_id" name="district_id" class="select">
                    <option value="">All</option>
                    @foreach ($districts as $d)
                        <option value="{{ $d->id }}" @selected((string) $district_id === (string) $d->id)>
                            {{ $d->district_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="label" for="block_id">Block</label>
                <select id="block_id" name="block_id" class="select">
                    <option value="">All</option>
                    @foreach ($blocks as $b)
                        <option value="{{ $b->id }}" @selected((string) $block_id === (string) $b->id)>
                            {{ $b->block_name }}{{ $b->district_name ? ' · ' . $b->district_name : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2 flex items-end gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                @if ($q !== '' || $type !== '' || $district_id !== '' || $block_id !== '')
                    <a href="{{ route('admin.memberships.index') }}" class="btn btn-ghost" title="Reset">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>

    @if ($members->total() === 0)
        <div class="card p-10 text-center">
            <div class="w-14 h-14 mx-auto rounded-full bg-[var(--color-clay-50)] flex items-center justify-center mb-4">
                <i class="bi bi-people text-2xl text-[var(--color-clay-500)]"></i>
            </div>
            <div class="font-display text-lg text-[var(--color-ink-2)] mb-1">No memberships found</div>
            <p class="text-sm text-[var(--color-mute)]">Try adjusting your filters.</p>
        </div>
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-12">#</th>
                            <th class="w-14">Avatar</th>
                            <th>Member</th>
                            <th>Mobile</th>
                            <th>Type</th>
                            <th>District</th>
                            <th>Code</th>
                            <th>Date</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $row)
                            @php
                                $typeMap = [
                                    'Individual'        => 'pill-river',
                                    'individual'        => 'pill-river',
                                    'CSO/NGO'           => 'pill-leaf',
                                    'Volunteer'         => 'pill-amber',
                                    'Firm/Organization' => 'pill-clay',
                                ];
                                $typeClass = $typeMap[$row->type] ?? 'pill-mute';
                                $districtName = $districtMap[(int) $row->district] ?? '—';
                            @endphp
                            <tr>
                                <td class="text-[var(--color-mute)] font-mono text-xs">{{ $row->id }}</td>
                                <td>
                                    @if (!empty($row->image_url))
                                        <img src="{{ $row->image_url }}"
                                            alt="" class="w-9 h-9 rounded-full object-cover border border-[var(--color-line)]">
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-[var(--color-clay-100)] text-[var(--color-clay-700)] font-semibold flex items-center justify-center text-xs">
                                            {{ strtoupper(substr($row->name ?? '?', 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="font-medium text-[var(--color-ink-2)]">{{ $row->name }}</div>
                                    <div class="text-xs text-[var(--color-mute-2)]">{{ $row->email }}</div>
                                </td>
                                <td class="text-sm text-[var(--color-mute)]">{{ $row->mobile }}</td>
                                <td><span class="pill {{ $typeClass }}">{{ $row->type ?: '—' }}</span></td>
                                <td class="text-sm text-[var(--color-ink-2)]">{{ $districtName }}</td>
                                <td><span class="font-mono text-xs text-[var(--color-clay-700)]">{{ $row->code }}</span></td>
                                <td class="text-xs text-[var(--color-mute)]">
                                    {{ $row->date ? date('d M Y', strtotime($row->date)) : '—' }}
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.memberships.show', $row->id) }}"
                                            class="btn btn-ghost btn-sm" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.memberships.edit', $row->id) }}"
                                            class="btn btn-ghost btn-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <x-admin.delete-form
                                            :action="route('admin.memberships.destroy', $row->id)"
                                            message="Delete this membership? This cannot be undone."
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

        <div>{{ $members->links() }}</div>
    @endif
</div>
@endsection
