@extends('layouts.admin')

@section('title', 'Users')
@section('page_title', 'Users')

@section('breadcrumb')
    <span>Configuration</span>
    <i class="bi bi-chevron-right text-[10px]"></i>
    <span>Users</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i>
        <span>New user</span>
    </a>
@endsection

@php
    $typeMap = [
        'admin'        => ['label' => 'Admin',        'pill' => 'pill-clay'],
        'author'       => ['label' => 'Author',       'pill' => 'pill-river'],
        'volunteer'    => ['label' => 'Volunteer',    'pill' => 'pill-leaf'],
        'intern'       => ['label' => 'Intern',       'pill' => 'pill-amber'],
        'professional' => ['label' => 'Professional', 'pill' => 'pill-mute'],
        'ngo'          => ['label' => 'NGO',          'pill' => 'pill-mute'],
    ];
    $currentUserId = (int) (auth()->id() ?? 0);
@endphp

@section('content')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
        <div class="card p-5">
            <div class="text-[10px] uppercase tracking-widest text-[var(--color-mute-2)] font-semibold">Total users</div>
            <div class="font-display text-3xl text-[var(--color-ink-2)] mt-1">{{ $totalUsers }}</div>
            <div class="text-xs text-[var(--color-mute)] mt-1 flex items-center gap-1">
                <i class="bi bi-people"></i>
                <span>All accounts</span>
            </div>
        </div>
        <div class="card p-5">
            <div class="text-[10px] uppercase tracking-widest text-[var(--color-mute-2)] font-semibold">Admins</div>
            <div class="font-display text-3xl text-[var(--color-ink-2)] mt-1">{{ $totalAdmins }}</div>
            <div class="text-xs text-[var(--color-mute)] mt-1 flex items-center gap-1">
                <i class="bi bi-shield-lock"></i>
                <span>type = admin</span>
            </div>
        </div>
        <div class="card p-5">
            <div class="text-[10px] uppercase tracking-widest text-[var(--color-mute-2)] font-semibold">Authors</div>
            <div class="font-display text-3xl text-[var(--color-ink-2)] mt-1">{{ $totalAuthors }}</div>
            <div class="text-xs text-[var(--color-mute)] mt-1 flex items-center gap-1">
                <i class="bi bi-pencil-square"></i>
                <span>type = author</span>
            </div>
        </div>
        <div class="card p-5">
            <div class="text-[10px] uppercase tracking-widest text-[var(--color-mute-2)] font-semibold">Other portals</div>
            <div class="font-display text-3xl text-[var(--color-ink-2)] mt-1">{{ $totalOther }}</div>
            <div class="text-xs text-[var(--color-mute)] mt-1 flex items-center gap-1">
                <i class="bi bi-collection"></i>
                <span>volunteer · intern · ngo …</span>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="card p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
            <div class="lg:col-span-7">
                <label class="label" for="q">Search</label>
                <div class="relative">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-[var(--color-mute-2)] text-sm"></i>
                    <input id="q" type="search" name="q" value="{{ $filters['q'] }}"
                           placeholder="Search name, username, or email..." class="input pl-9">
                </div>
            </div>

            <div class="lg:col-span-3">
                <label class="label" for="type">Type</label>
                <select id="type" name="type" class="select" onchange="this.form.submit()">
                    <option value="">All types</option>
                    @foreach ($typeMap as $key => $meta)
                        <option value="{{ $key }}" @selected((string) $filters['type'] === $key)>{{ $meta['label'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2 flex items-center gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-1">
                    <i class="bi bi-funnel"></i>
                    <span>Filter</span>
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">Reset</a>
            </div>
        </div>
    </form>

    @if ($users->isEmpty())
        <div class="card p-10 text-center">
            <div class="w-14 h-14 rounded-full bg-[var(--color-paper-2)] flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-person-x text-2xl text-[var(--color-mute-2)]"></i>
            </div>
            <h3 class="font-display text-lg text-[var(--color-ink-2)] mb-1">No users found</h3>
            <p class="text-sm text-[var(--color-mute)] mb-5">Try changing filters or create a new user.</p>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i>
                <span>New user</span>
            </a>
        </div>
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-12">#</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th class="text-right">Actions</th>
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
                                <td class="text-xs font-mono text-[var(--color-mute)]">{{ $u->id }}</td>
                                <td>
                                    <div class="flex items-center gap-3 min-w-[220px]">
                                        @if ($hasImage)
                                            <img src="{{ asset('uploads/users/' . $u->image) }}" alt=""
                                                 class="w-10 h-10 rounded-full object-cover border border-[var(--color-line)] shrink-0">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-[var(--color-clay-100)] text-[var(--color-clay-700)] font-semibold flex items-center justify-center text-xs shrink-0">
                                                {{ $initials ?: 'U' }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <div class="font-semibold text-[var(--color-ink-2)] truncate">
                                                {{ trim(($u->fname ?? '') . ' ' . ($u->lname ?? '')) ?: '—' }}
                                            </div>
                                            <div class="text-[11px] text-[var(--color-mute-2)] font-mono truncate">@ {{ $u->username }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="mailto:{{ $u->email }}" class="text-sm text-[var(--color-ink-2)] hover:text-[var(--color-clay-600)] truncate inline-block max-w-[220px]">
                                        {{ $u->email }}
                                    </a>
                                </td>
                                <td>
                                    <span class="pill {{ $typeMeta['pill'] }}">
                                        <i class="bi bi-person-badge"></i>
                                        {{ $typeMeta['label'] }}
                                    </span>
                                </td>
                                <td>
                                    @if ((int) $u->role === 1)
                                        <span class="pill pill-flame">Admin role</span>
                                    @else
                                        <span class="pill pill-mute">User role</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-xs text-[var(--color-mute)]">
                                        {{ $joined ? \Illuminate\Support\Carbon::parse($joined)->format('d M Y') : '—' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-ghost btn-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        @if ($currentUserId !== (int) $u->id)
                                            <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}"
                                                  data-confirm="Delete user {{ $u->username }} permanently?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="pill pill-mute text-[10px]" title="You cannot delete your own account">
                                                <i class="bi bi-person-check"></i>
                                                You
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5">
            {{ $users->withQueryString()->links() }}
        </div>
    @endif
@endsection
