@extends('layouts.admin')

@section('title', 'Story approvals')
@section('page_title', 'Story approvals')

@section('breadcrumb')
    <span>Content</span>
    <i class="bi bi-chevron-right text-[10px]"></i>
    <span>Stories</span>
@endsection

@section('content')
    @if($pendingCount > 0)
        <div class="alert alert-success mb-5">
            <i class="bi bi-hourglass-split"></i>
            <span><strong>{{ $pendingCount }}</strong> {{ $pendingCount === 1 ? 'story' : 'stories' }} waiting for your approval.</span>
        </div>
    @endif

    <form method="GET" action="{{ route('admin.stories.index') }}" class="card p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
            <div class="lg:col-span-5">
                <label class="label" for="q">Search</label>
                <input id="q" type="search" name="q" value="{{ $filters['q'] }}" placeholder="Search by title..." class="input">
            </div>
            <div class="lg:col-span-3">
                <label class="label" for="approval_status">Approval</label>
                <select id="approval_status" name="approval_status" class="select" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="pending" @selected(($filters['approval_status'] ?? '') === 'pending')>Pending</option>
                    <option value="approved" @selected(($filters['approval_status'] ?? '') === 'approved')>Approved</option>
                    <option value="rejected" @selected(($filters['approval_status'] ?? '') === 'rejected')>Rejected</option>
                </select>
            </div>
            <div class="lg:col-span-4 flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-1">Filter</button>
                <a href="{{ route('admin.stories.index') }}" class="btn btn-ghost btn-sm">Reset</a>
            </div>
        </div>
    </form>

    @if($stories->isEmpty())
        <div class="card p-10 text-center">
            <i class="bi bi-inbox text-3xl text-[var(--color-mute-2)]"></i>
            <div class="font-display text-lg mt-3">No stories found</div>
        </div>
    @else
        <div class="card overflow-hidden">
            <table class="table w-full table-fixed">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th class="w-36 hidden sm:table-cell">Author</th>
                        <th class="w-28 hidden md:table-cell">Category</th>
                        <th class="w-24">Status</th>
                        <th class="w-28 hidden sm:table-cell">Submitted</th>
                        <th class="w-20 text-right">Actions</th>
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
                        @endphp
                        <tr>
                            <td class="max-w-0">
                                <a href="{{ route('admin.stories.show', $s->id) }}" class="font-semibold text-[var(--color-ink-2)] hover:text-[var(--color-clay-600)] truncate block">
                                    {{ $s->title }}
                                </a>
                            </td>
                            <td class="hidden sm:table-cell max-w-0">
                                <span class="text-sm text-[var(--color-ink-2)] truncate block">{{ trim(($s->fname ?? '') . ' ' . ($s->lname ?? '')) ?: ($s->email ?? '—') }}</span>
                            </td>
                            <td class="hidden md:table-cell max-w-0">
                                <span class="text-sm text-[var(--color-mute)] truncate block">{{ $s->category ?: '—' }}</span>
                            </td>
                            <td><span class="pill {{ $pill }}">{{ ucfirst($status) }}</span></td>
                            <td class="hidden sm:table-cell text-xs text-[var(--color-mute)]">{{ \Illuminate\Support\Carbon::parse($s->created_at)->format('d M Y') }}</td>
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
        </div>
        <div class="mt-5">{{ $stories->withQueryString()->links() }}</div>
    @endif
@endsection
