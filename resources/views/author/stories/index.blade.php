@extends('layouts.author')

@section('title', 'My stories')
@section('page_title', 'My stories')

@section('breadcrumb')
    <span>Stories</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('author.stories.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i>
        <span>New story</span>
    </a>
@endsection

@section('content')
    <form method="GET" action="{{ route('author.stories.index') }}" class="card p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
            <div class="lg:col-span-4">
                <label class="label" for="approval_status">Approval status</label>
                <select id="approval_status" name="approval_status" class="select" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="pending" @selected(($filters['approval_status'] ?? '') === 'pending')>Pending review</option>
                    <option value="approved" @selected(($filters['approval_status'] ?? '') === 'approved')>Approved</option>
                    <option value="rejected" @selected(($filters['approval_status'] ?? '') === 'rejected')>Rejected</option>
                </select>
            </div>
            <div class="lg:col-span-2 flex gap-2">
                <a href="{{ route('author.stories.index') }}" class="btn btn-ghost btn-sm">Reset</a>
            </div>
        </div>
    </form>

    @if($stories->isEmpty())
        <div class="card p-10 text-center">
            <i class="bi bi-inbox text-3xl text-[var(--color-mute-2)]"></i>
            <div class="font-display text-lg mt-3">No stories yet</div>
            <div class="text-sm text-[var(--color-mute)] mt-1">Create a story and wait for admin approval.</div>
            <a href="{{ route('author.stories.create') }}" class="btn btn-primary btn-sm mt-4">New story</a>
        </div>
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Updated</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stories as $story)
                            @php
                                $status = $story->approval_status ?? 'pending';
                                $pill = match($status) {
                                    'approved' => 'pill-leaf',
                                    'rejected' => 'pill-flame',
                                    default => 'pill-clay',
                                };
                            @endphp
                            <tr>
                                <td class="font-semibold text-[var(--color-ink-2)]">{{ $story->title }}</td>
                                <td class="text-sm">{{ $story->category ?: '—' }}</td>
                                <td><span class="pill {{ $pill }}">{{ ucfirst($status) }}</span></td>
                                <td class="text-xs text-[var(--color-mute)]">{{ \Illuminate\Support\Carbon::parse($story->updated_at)->format('d M Y') }}</td>
                                <td>
                                    <div class="flex justify-end gap-1">
                                        @if(in_array($status, ['pending', 'rejected'], true))
                                            <a href="{{ route('author.stories.edit', $story->id) }}" class="btn btn-ghost btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>
                                        @endif
                                        @if($status === 'approved')
                                            <a href="{{ route('stories.show', $story->slug) }}" target="_blank" class="btn btn-ghost btn-sm" title="Public page"><i class="bi bi-box-arrow-up-right"></i></a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-5">{{ $stories->withQueryString()->links() }}</div>
    @endif
@endsection
