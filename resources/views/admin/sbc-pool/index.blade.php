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
<div class="space-y-5">

    <form method="GET" action="{{ route('admin.sbc-pool.index') }}" class="card p-4">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-10">
                <label class="label" for="q">Search</label>
                <input type="text" id="q" name="q" value="{{ $q }}" placeholder="Name or email..." class="input">
            </div>
            <div class="md:col-span-2 flex items-end gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                @if ($q !== '')
                    <a href="{{ route('admin.sbc-pool.index') }}" class="btn btn-ghost" title="Reset">
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
            <div class="font-display text-lg text-[var(--color-ink-2)] mb-1">No members yet</div>
            <p class="text-sm text-[var(--color-mute)] mb-4">Create your first SBC resource pool member.</p>
            <a href="{{ route('admin.sbc-pool.create') }}" class="btn btn-primary btn-sm inline-flex">
                <i class="bi bi-plus-lg"></i> Add member
            </a>
        </div>
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-12">#</th>
                            <th class="w-16">Photo</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="w-24">Order</th>
                            <th class="w-24">Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $member)
                            <tr>
                                <td class="text-[var(--color-mute)] font-mono text-xs">{{ $member->id }}</td>
                                <td>
                                    <x-admin-image
                                        :filename="$member->photo"
                                        folder="storage/sbc-pool"
                                        class="w-10 h-10 rounded-full object-contain border border-[var(--color-line)]"
                                        icon="bi-person" />
                                </td>
                                <td class="font-medium text-[var(--color-ink-2)]">{{ $member->name }}</td>
                                <td class="text-sm text-[var(--color-mute)]">{{ $member->email ?: '—' }}</td>
                                <td class="text-sm text-[var(--color-ink-2)]">{{ $member->sort_order }}</td>
                                <td>
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
            </div>
        </div>

        <div>{{ $members->links() }}</div>
    @endif
</div>
@endsection
