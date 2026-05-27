@extends('layouts.admin')

@section('title', 'Districts')
@section('page_title', 'Districts')
@section('breadcrumb')
    <span class="text-[var(--color-ink-2)]">Districts</span>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="lg:col-span-1">
            <div class="card p-5 sticky top-24">
                <h3 class="font-display text-base text-[var(--color-ink-2)] mb-1">Add district</h3>
                <p class="text-xs text-[var(--color-mute)] mb-4">Add a new district to the list.</p>

                <form method="POST" action="{{ route('admin.districts.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="label" for="district_name">District name</label>
                        <input type="text" id="district_name" name="district_name"
                            value="{{ old('district_name') }}" maxlength="50" required
                            class="input" placeholder="e.g. Raipur">
                    </div>
                    <div>
                        <label class="label" for="status">Status</label>
                        <select id="status" name="status" required class="select">
                            <option value="1" @selected(old('status', '1') === '1')>Active</option>
                            <option value="0" @selected(old('status') === '0')>Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="bi bi-plus-lg"></i> Add district
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <x-admin.datatable-card
                :empty="$districts->isEmpty()"
                empty-icon="bi-map"
                empty-title="No districts yet"
                empty-text="Use the form on the left to add the first district.">
                <table data-admin-datatable class="table w-full">
                    <thead>
                        <tr>
                            <th>District</th>
                            <th>Blocks</th>
                            <th>Status</th>
                            <th class="no-sort col-actions text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($districts as $row)
                            <tr>
                                <td>
                                    <div class="font-medium text-[var(--color-ink-2)]">{{ $row->district_name }}</div>
                                </td>
                                <td>
                                    <span class="pill pill-mute">{{ $row->blocks_count }}</span>
                                </td>
                                <td data-status-cell>
                                    <x-admin.status-pill :active="(bool) $row->status" />
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.districts.edit', $row->id) }}" class="btn btn-ghost btn-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <x-admin.toggle-form
                                            :action="route('admin.districts.toggle', $row->id)"
                                            :active="(bool) $row->status" />
                                        <x-admin.delete-form
                                            :action="route('admin.districts.destroy', $row->id)"
                                            message="Delete this district? Only allowed if no blocks reference it."
                                            buttonClass="btn btn-ghost btn-sm"
                                            iconClass="text-[var(--color-flame)]"
                                            :disabled="$row->blocks_count > 0" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-admin.datatable-card>
        </div>
    </div>
@endsection
