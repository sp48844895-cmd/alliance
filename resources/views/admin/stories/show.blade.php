@extends('layouts.admin')

@section('title', 'Review story')
@section('page_title', 'Review story')

@section('breadcrumb')
    <a href="{{ route('admin.stories.index') }}">Stories</a>
    <i class="bi bi-chevron-right text-[10px]"></i>
    <span>Review</span>
@endsection

@section('content')
    @php
        $status = $story->approval_status ?? 'pending';
        $authorName = trim(($story->fname ?? '') . ' ' . ($story->lname ?? ''));
        $thumb = $story->thumbnail_path ?? '';
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-5">
            <div class="card p-5 lg:p-6">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    @if($status === 'approved')
                        <span class="pill pill-leaf">Approved</span>
                    @elseif($status === 'rejected')
                        <span class="pill pill-flame">Rejected</span>
                    @else
                        <span class="pill pill-clay">Pending review</span>
                    @endif
                    @if($story->category)
                        <span class="text-sm text-[var(--color-mute)]">{{ $story->category }}</span>
                    @endif
                </div>
                <h2 class="font-display text-2xl text-[var(--color-ink-2)] mb-4">{{ $story->title }}</h2>
                @if($thumb && !str_starts_with($thumb, 'http'))
                    <img src="{{ asset($thumb) }}" alt="" class="w-full max-h-80 object-cover rounded-lg border border-[var(--color-line)] mb-5">
                @endif
                <div class="prose prose-sm max-w-none text-[var(--color-ink-2)] whitespace-pre-wrap">{!! nl2br(e($story->content)) !!}</div>
                @if($story->tag)
                    <p class="text-sm text-[var(--color-mute)] mt-4">Tags: {{ $story->tag }}</p>
                @endif
            </div>
        </div>

        <div class="space-y-5">
            <div class="card p-5">
                <h3 class="font-display text-base mb-3">Submitted by</h3>
                <p class="text-sm font-semibold">{{ $authorName !== '' ? $authorName : 'Unknown' }}</p>
                <p class="text-xs text-[var(--color-mute)]">{{ $story->email ?? '' }}</p>
                <p class="text-xs text-[var(--color-mute)] mt-2">{{ \Illuminate\Support\Carbon::parse($story->created_at)->format('d M Y, h:i A') }}</p>
            </div>

            @if($status === 'rejected' && !empty($story->rejection_note))
                <div class="card p-5 border-[var(--color-flame)]">
                    <h3 class="font-display text-base mb-2">Rejection note</h3>
                    <p class="text-sm">{{ $story->rejection_note }}</p>
                </div>
            @endif

            @if($status !== 'approved')
                <div class="card p-5 space-y-3">
                    <h3 class="font-display text-base mb-2">Actions</h3>
                    <form method="POST" action="{{ route('admin.stories.approve', $story->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm w-full">
                            <i class="bi bi-check-lg"></i> Approve & publish
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.stories.reject', $story->id) }}" class="space-y-2">
                        @csrf
                        <label class="label" for="rejection_note">Rejection note (optional)</label>
                        <textarea id="rejection_note" name="rejection_note" rows="3" class="textarea text-sm">{{ old('rejection_note') }}</textarea>
                        <button type="submit" class="btn btn-danger btn-sm w-full">
                            <i class="bi bi-x-lg"></i> Reject
                        </button>
                    </form>
                </div>
            @else
                <div class="card p-5">
                    <p class="text-sm text-[var(--color-mute)] mb-3">This story is live on the public site.</p>
                    <a href="{{ route('stories.show', $story->slug) }}" target="_blank" class="btn btn-ghost btn-sm w-full">
                        <i class="bi bi-box-arrow-up-right"></i> View public page
                    </a>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.stories.destroy', $story->id) }}" data-confirm="Delete this story permanently?">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm w-full">
                    <i class="bi bi-trash"></i> Delete story
                </button>
            </form>

            <a href="{{ route('admin.stories.index') }}" class="btn btn-ghost btn-sm w-full">Back to list</a>
        </div>
    </div>
@endsection
