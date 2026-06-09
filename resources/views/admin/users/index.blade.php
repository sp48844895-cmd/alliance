@extends('layouts.admin')

@section('title', 'Admin users')
@section('page_title', 'Admin users')

@section('breadcrumb')
    <span>Settings</span>
    <i class="bi bi-chevron-right text-xs"></i>
    <span>Users</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i>
        <span>Add user</span>
    </a>
@endsection

@php
    $typeMap = [
        'admin'     => ['label' => 'Admin',     'pill' => 'pill-clay'],
        'guest' => ['label' => 'Guest', 'pill' => 'pill-leaf'],
        'intern'    => ['label' => 'Intern',    'pill' => 'pill-amber'],
        'fellow'    => ['label' => 'Fellow',    'pill' => 'pill-river'],
        'ngo'       => ['label' => 'CSO / NGO', 'pill' => 'pill-mute'],
    ];
    $currentUserId = (int) (auth()->id() ?? 0);
@endphp

@section('content')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
        <div class="card p-5">
            <div class="text-xs uppercase tracking-widest text-[var(--color-mute-2)] font-semibold">Total</div>
            <div class="font-display text-3xl text-[var(--color-ink-2)] mt-1">{{ $totalUsers }}</div>
            <div class="text-xs text-[var(--color-mute)] mt-1">All accounts</div>
        </div>
        <div class="card p-5">
            <div class="text-xs uppercase tracking-widest text-[var(--color-mute-2)] font-semibold">Admins</div>
            <div class="font-display text-3xl text-[var(--color-ink-2)] mt-1">{{ $totalAdmins }}</div>
        </div>
        <div class="card p-5">
            <div class="text-xs uppercase tracking-widest text-[var(--color-mute-2)] font-semibold">Fellows</div>
            <div class="font-display text-3xl text-[var(--color-ink-2)] mt-1">{{ $totalFellows }}</div>
        </div>
        <div class="card p-5">
            <div class="text-xs uppercase tracking-widest text-[var(--color-mute-2)] font-semibold">Other roles</div>
            <div class="font-display text-3xl text-[var(--color-ink-2)] mt-1">{{ $totalOther }}</div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="card p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
            <div class="lg:col-span-8">
                <label class="label" for="type">Account type</label>
                <select id="type" name="type" class="select">
                    <option value="">All types</option>
                    @foreach ($typeMap as $key => $meta)
                        <option value="{{ $key }}" @selected((string) $filters['type'] === $key)>{{ $meta['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-4 flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-1">
                    <i class="bi bi-funnel"></i>
                    <span>Apply</span>
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">Clear</a>
            </div>
        </div>
    </form>

    <x-admin.datatable-card
        :empty="$users->isEmpty()"
        empty-icon="bi-person-x"
        empty-title="No users match"
        empty-text="Change the filter or add someone new.">
        <x-slot:emptyAction>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i>
                <span>Add user</span>
            </a>
        </x-slot:emptyAction>
        <table data-admin-datatable class="table w-full">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Access</th>
                    <th>Joined</th>
                    <th class="no-sort col-actions text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $u)
                    @php
                        $typeMeta = $typeMap[$u->type] ?? ['label' => ucfirst($u->type), 'pill' => 'pill-mute'];
                        $initials = strtoupper(substr($u->fname ?? '', 0, 1) . substr($u->lname ?? '', 0, 1));
                        $hasImage = $u->image && file_exists(public_path('uploads/users/' . $u->image));
                        $joined = $u->date ?? $u->created_at;
                    @endphp
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                @if ($hasImage)
                                    <img src="{{ asset('uploads/users/' . $u->image) }}" alt="" class="w-10 h-10 rounded-full object-cover border border-[var(--color-line)] shrink-0">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-[var(--color-clay-100)] text-[var(--color-clay-700)] font-semibold flex items-center justify-center text-xs shrink-0">
                                        {{ $initials ?: 'U' }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <div class="font-semibold text-[var(--color-ink-2)] truncate">
                                        {{ trim(($u->fname ?? '') . ' ' . ($u->lname ?? '')) ?: '—' }}
                                    </div>
                                    <div class="text-xs text-[var(--color-mute)] truncate">{{ $u->email }}</div>
                                    <div class="text-xs text-[var(--color-mute-2)] font-mono">{{ '@' . $u->username }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-wrap gap-1.5">
                                <span class="pill {{ $typeMeta['pill'] }}">{{ $typeMeta['label'] }}</span>
                                @if ((int) $u->role === 1)
                                    <span class="pill pill-flame">Full admin</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-xs text-[var(--color-mute)] whitespace-nowrap">
                            {{ $joined ? \Illuminate\Support\Carbon::parse($joined)->format('d M Y') : '—' }}
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-ghost btn-sm" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if ($currentUserId !== (int) $u->id)
                                    <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" data-confirm="Delete {{ $u->username }}?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="pill pill-mute text-xs">You</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-admin.datatable-card>
@endsection
