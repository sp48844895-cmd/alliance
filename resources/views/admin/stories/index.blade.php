@extends('layouts.admin')

@section('title', 'Story approvals')
@section('page_title', 'Story approvals')

@section('breadcrumb')
    <span>Content</span>
    <i class="bi bi-chevron-right text-xs"></i>
    <span>Stories</span>
@endsection

@section('content')
    @if($pendingCount > 0)
        <div class="alert alert-success mb-5">
            <i class="bi bi-hourglass-split"></i>
            <span><strong>{{ $pendingCount }}</strong> {{ $pendingCount === 1 ? 'story' : 'stories' }} waiting for approval.</span>
        </div>
    @endif

    <form method="GET" action="{{ route('admin.stories.index') }}" class="card p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
            <div class="lg:col-span-8">
                <label class="label" for="approval_status">Approval status</label>
                <select id="approval_status" name="approval_status" class="select">
                    <option value="">All statuses</option>
                    <option value="pending" @selected(($filters['approval_status'] ?? '') === 'pending')>Pending</option>
                    <option value="approved" @selected(($filters['approval_status'] ?? '') === 'approved')>Approved</option>
                    <option value="rejected" @selected(($filters['approval_status'] ?? '') === 'rejected')>Rejected</option>
                </select>
            </div>
            <div class="lg:col-span-4 flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-1">
                    <i class="bi bi-funnel"></i>
                    <span>Apply filters</span>
                </button>
                <a href="{{ route('admin.stories.index') }}" class="btn btn-ghost btn-sm">Clear</a>
            </div>
        </div>
    </form>

    <x-admin.datatable-card
        :empty="$stories->isEmpty()"
        empty-icon="bi-inbox"
        empty-title="No stories found"
        empty-text="Try a different approval filter.">
        <table data-admin-datatable class="table w-full">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Submitted by</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th class="no-sort col-actions text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stories as $s)
                    @php
                        $status = $s->approval_status ?? 'pending';
                        $pill = match($status) {
                            'approved' => 'pill-leaf',
                            'rejected' => 'pill-flame',
                            default => 'pill-clay',
                        };
                        $submitterName = trim(($s->fname ?? '') . ' ' . ($s->lname ?? ''));
                        $roleLabels = [
                            'guest' => 'Guest',
                            'intern'    => 'Intern',
                            'ngo'       => 'CSO / NGO / Firm / Organization',
                            'admin'     => 'Admin',
                            'fellow'    => 'Fellow',
                        ];
                        $rolePills = [
                            'guest' => 'pill-leaf',
                            'intern'    => 'pill-amber',
                            'ngo'       => 'pill-clay',
                            'admin'     => 'pill-mute',
                            'fellow'    => 'pill-river',
                        ];
                        $authorType = $s->author_type ?? '';
                        $roleLabel = $roleLabels[$authorType] ?? ($authorType !== '' ? ucfirst($authorType) : 'Unknown');
                        $rolePill = $rolePills[$authorType] ?? 'pill-mute';
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('admin.stories.show', $s->id) }}" class="font-semibold text-[var(--color-ink-2)] hover:text-[var(--color-clay-600)] line-clamp-2 leading-snug">
                                {{ $s->title }}
                            </a>
                        </td>
                        <td>
                            <div class="font-medium text-[var(--color-ink-2)]">
                                {{ $submitterName !== '' ? $submitterName : 'Unknown' }}
                            </div>
                            @if(!empty($s->email))
                                <a href="mailto:{{ $s->email }}" class="text-xs text-[var(--color-mute)] hover:text-[var(--color-clay-600)] block truncate max-w-[220px]">
                                    {{ $s->email }}
                                </a>
                            @endif
                            @if(!empty($s->author_username))
                                <div class="text-xs text-[var(--color-mute-2)] font-mono mt-0.5">{{ '@' . $s->author_username }}</div>
                            @endif
                            <span class="pill {{ $rolePill }} text-[10px] mt-1.5">{{ $roleLabel }}</span>
                        </td>
                        <td>
                            <span class="text-sm text-[var(--color-ink-2)]">{{ $s->category ?: '—' }}</span>
                        </td>
                        <td><span class="pill {{ $pill }}">{{ ucfirst($status) }}</span></td>
                        <td class="text-xs text-[var(--color-mute)] whitespace-nowrap">
                            {{ \Illuminate\Support\Carbon::parse($s->created_at)->format('d M Y') }}
                            <div class="text-[var(--color-mute-2)]">{{ \Illuminate\Support\Carbon::parse($s->created_at)->format('h:i A') }}</div>
                        </td>
                        <td>
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('admin.stories.show', $s->id) }}" class="btn btn-ghost btn-sm" title="Review"><i class="bi bi-eye"></i></a>
                                @if($status === 'approved')
                                    <a href="{{ route('stories.show', $s->slug) }}" target="_blank" class="btn btn-ghost btn-sm" title="Public page"><i class="bi bi-box-arrow-up-right"></i></a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-admin.datatable-card>
@endsection
