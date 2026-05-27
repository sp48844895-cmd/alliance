@extends('layouts.admin')

@section('title', 'SBC Resource Pool')
@section('page_title', 'SBC Resource Pool')
@section('breadcrumb')
    <span class="text-[var(--color-ink-2)]">SBC Resource Pool</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.sbc-pool.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i>
        Add member
    </a>
@endsection

@section('content')
    <x-admin.datatable-card
        :empty="$members->isEmpty()"
        empty-icon="bi-people"
        empty-title="No pool members yet"
        empty-text="Add your first SBC resource pool member.">
        <x-slot:emptyAction>
            <a href="{{ route('admin.sbc-pool.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i>
                Add member
            </a>
        </x-slot:emptyAction>
        <table data-admin-datatable class="table w-full">
            <thead>
                <tr>
                    <th class="no-sort w-14">Photo</th>
                    <th>Member</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th class="no-sort col-actions text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($members as $member)
                    <tr>
                        <td>
                            <x-admin-image
                                :filename="$member->photo"
                                folder="storage/sbc-pool"
                                class="w-10 h-10 rounded-full object-contain border border-[var(--color-line)]"
                                icon="bi-person" />
                        </td>
                        <td>
                            <div class="font-medium text-[var(--color-ink-2)]">{{ $member->name }}</div>
                            <div class="text-xs text-[var(--color-mute)]">{{ $member->email ?: 'No email' }}</div>
                        </td>
                        <td class="text-sm text-[var(--color-ink-2)]">{{ $member->sort_order }}</td>
                        <td data-status-cell>
                            <x-admin.status-pill :active="(int) $member->status === 1" />
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.sbc-pool.edit', $member->id) }}" class="btn btn-ghost btn-sm" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <x-admin.toggle-form
                                    :action="route('admin.sbc-pool.toggle', $member->id)"
                                    :active="(int) $member->status === 1" />
                                <x-admin.delete-form
                                    :action="route('admin.sbc-pool.destroy', $member->id)"
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
