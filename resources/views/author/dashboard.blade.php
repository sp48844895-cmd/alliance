@extends('layouts.author')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('breadcrumb')
    <span>Dashboard</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('author.stories.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i>
        <span>New story</span>
    </a>
    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-ghost btn-sm">
        <i class="bi bi-box-arrow-up-right"></i>
        <span>Public site</span>
    </a>
@endsection

@section('content')
    <div class="mb-6">
        <p class="text-sm text-[var(--color-mute)]">
            Welcome back, <span class="font-semibold text-[var(--color-ink-2)]">{{ auth()->user()->fname ?? 'there' }}</span>.
            Write a story and send it for review — once approved, it goes live on the public site.
        </p>
    </div>

    <div class="stagger grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="stat">
            <div class="stat-label">Total stories</div>
            <div class="stat-num mt-2">{{ number_format($stats['total']) }}</div>
        </div>
        <div class="stat">
            <div class="stat-label">Pending review</div>
            <div class="stat-num mt-2 text-[var(--color-clay-600)]">{{ number_format($stats['pending']) }}</div>
        </div>
        <div class="stat">
            <div class="stat-label">Approved</div>
            <div class="stat-num mt-2 text-[var(--color-leaf)]">{{ number_format($stats['approved']) }}</div>
        </div>
        <div class="stat">
            <div class="stat-label">Rejected</div>
            <div class="stat-num mt-2">{{ number_format($stats['rejected']) }}</div>
        </div>
    </div>

    <div class="card p-5 lg:p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="font-display text-lg text-[var(--color-ink-2)]">Recent stories</h2>
                <p class="text-xs text-[var(--color-mute)] mt-0.5">Your latest submissions</p>
            </div>
            <a href="{{ route('author.stories.index') }}" class="btn btn-ghost btn-sm">
                <span>View all</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        @if($recentStories->isEmpty())
            <p class="text-sm text-[var(--color-mute)]">No stories yet. <a href="{{ route('author.stories.create') }}" class="text-[var(--color-clay-600)] font-semibold hover:underline">Create your first story</a>.</p>
        @else
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Updated</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentStories as $story)
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
                                <td><span class="pill {{ $pill }}">{{ ucfirst($status) }}</span></td>
                                <td class="text-xs text-[var(--color-mute)]">{{ \Illuminate\Support\Carbon::parse($story->updated_at)->format('d M Y') }}</td>
                                <td class="text-right">
                                    @if(in_array($status, ['pending', 'rejected'], true))
                                        <a href="{{ route('author.stories.edit', $story->id) }}" class="btn btn-ghost btn-sm">Edit</a>
                                    @elseif($status === 'approved')
                                        <a href="{{ route('stories.show', $story->slug) }}" target="_blank" class="btn btn-ghost btn-sm">View</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
