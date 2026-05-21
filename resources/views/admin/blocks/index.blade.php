@extends('layouts.admin')

@section('title', 'Blocks')
@section('page_title', 'Blocks')
@section('breadcrumb')
    <span class="text-[var(--color-ink-2)]">Blocks</span>
@endsection

@section('content')
<div class="space-y-5">

    <div class="card p-5">
        <h3 class="font-display text-base text-[var(--color-ink-2)] mb-1">Add block</h3>
        <p class="text-xs text-[var(--color-mute)] mb-4">Link a new block to one of the existing districts.</p>

        <form method="POST" action="{{ route('admin.blocks.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-4">
                    <label class="label" for="new_district_id">District</label>
                    <select id="new_district_id" name="district_id" required class="select">
                        <option value="">— Select district —</option>
                        @foreach ($districts as $d)
                            <option value="{{ $d->id }}"
                                @selected((string) old('district_id') === (string) $d->id)>
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

    <form method="GET" action="{{ route('admin.blocks.index') }}" class="card p-4">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-6">
                <label class="label" for="q">Search</label>
                <input type="text" id="q" name="q" value="{{ $q }}"
                    placeholder="Block name..." class="input">
            </div>
            <div class="md:col-span-4">
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
            <div class="md:col-span-2 flex items-end gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                @if ($q !== '' || $district_id !== '')
                    <a href="{{ route('admin.blocks.index') }}" class="btn btn-ghost" title="Reset">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>

    @if ($blocks->total() === 0)
        <div class="card p-10 text-center">
            <div class="w-14 h-14 mx-auto rounded-full bg-[var(--color-clay-50)] flex items-center justify-center mb-4">
                <i class="bi bi-geo-alt text-2xl text-[var(--color-clay-500)]"></i>
            </div>
            <div class="font-display text-lg text-[var(--color-ink-2)]">No blocks found</div>
            <p class="text-sm text-[var(--color-mute)]">Use the form above to add a block.</p>
        </div>
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-12">#</th>
                            <th>Block</th>
                            <th>District</th>
                            <th class="w-28">Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($blocks as $row)
                            <tr>
                                <td class="text-[var(--color-mute)] font-mono text-xs">{{ $row->id }}</td>
                                <td>
                                    <div class="font-medium text-[var(--color-ink-2)]">{{ $row->block_name }}</div>
                                </td>
                                <td class="text-sm text-[var(--color-mute)]">{{ $row->district_name ?: '—' }}</td>
                                <td>
                                    <x-admin.status-pill :active="(bool) $row->status" />
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.blocks.edit', $row->id) }}"
                                            class="btn btn-ghost btn-sm" title="Edit">
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
            </div>
        </div>

        <div>{{ $blocks->links() }}</div>
    @endif
</div>
@endsection
