@extends('layouts.admin')

@section('title', 'Blocks')
@section('page_title', 'Blocks')
@section('breadcrumb')
    <span class="text-[var(--color-ink-2)]">Blocks</span>
@endsection

@section('content')
    <div class="card p-5 mb-5">
        <h3 class="font-display text-base text-[var(--color-ink-2)] mb-1">Add block</h3>
        <p class="text-xs text-[var(--color-mute)] mb-4">Link a new block to an existing district.</p>

        <form method="POST" action="{{ route('admin.blocks.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-4">
                    <label class="label" for="new_district_id">District</label>
                    <select id="new_district_id" name="district_id" required class="select">
                        <option value="">Select district</option>
                        @foreach ($districts as $d)
                            <option value="{{ $d->id }}" @selected((string) old('district_id') === (string) $d->id)>
                                {{ $d->district_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-5">
                    <label class="label" for="new_block_name">Block name</label>
                    <input type="text" id="new_block_name" name="block_name"
                        value="{{ old('block_name') }}" maxlength="50" required
                        class="input" placeholder="e.g. Abhanpur">
                </div>
                <div class="md:col-span-2">
                    <label class="label" for="new_status">Status</label>
                    <select id="new_status" name="status" required class="select">
                        <option value="1" @selected(old('status', '1') === '1')>Active</option>
                        <option value="0" @selected(old('status') === '0')>Inactive</option>
                    </select>
                </div>
                <div class="md:col-span-1 flex items-end">
                    <button type="submit" class="btn btn-primary w-full" title="Add block">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <form method="GET" action="{{ route('admin.blocks.index') }}" class="card p-4 mb-5">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            <div class="md:col-span-8">
                <label class="label" for="district_id">District</label>
                <select id="district_id" name="district_id" class="select">
                    <option value="">All districts</option>
                    @foreach ($districts as $d)
                        <option value="{{ $d->id }}" @selected((string) $district_id === (string) $d->id)>
                            {{ $d->district_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-1">
                    <i class="bi bi-funnel"></i>
                    <span>Apply filters</span>
                </button>
                @if ($district_id !== '')
                    <a href="{{ route('admin.blocks.index') }}" class="btn btn-ghost btn-sm" title="Clear">Clear</a>
                @endif
            </div>
        </div>
    </form>

    <x-admin.datatable-card
        :empty="$blocks->isEmpty()"
        empty-icon="bi-geo-alt"
        empty-title="No blocks found"
        empty-text="Add a block using the form above.">
        <table data-admin-datatable class="table w-full">
            <thead>
                <tr>
                    <th>Block</th>
                    <th>District</th>
                    <th>Status</th>
                    <th class="no-sort col-actions text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($blocks as $row)
                    <tr>
                        <td>
                            <div class="font-medium text-[var(--color-ink-2)]">{{ $row->block_name }}</div>
                        </td>
                        <td class="text-sm text-[var(--color-mute)]">{{ $row->district_name ?: '—' }}</td>
                        <td data-status-cell>
                            <x-admin.status-pill :active="(bool) $row->status" />
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.blocks.edit', $row->id) }}" class="btn btn-ghost btn-sm" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <x-admin.toggle-form
                                    :action="route('admin.blocks.toggle', $row->id)"
                                    :active="(bool) $row->status" />
                                <x-admin.delete-form
                                    :action="route('admin.blocks.destroy', $row->id)"
                                    message="Delete this block? This cannot be undone."
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
