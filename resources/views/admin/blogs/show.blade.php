@extends('layouts.admin')

@section('title', 'View story')
@section('page_title', 'View story')
@section('breadcrumb')
    <a href="{{ route('admin.blogs.index') }}">Stories</a>
    <i class="bi bi-chevron-right text-[10px]"></i>
    <span class="text-[var(--color-ink-2)]">View #{{ $blog->id }}</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-ghost">
        <i class="bi bi-arrow-left"></i>
        <span>Back</span>
    </a>
    <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-primary btn-sm">
        <i class="bi bi-pencil"></i>
        <span>Edit</span>
    </a>
@endsection

@section('content')
    @php
        $content = html_entity_decode((string) ($blog->content ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $content = str_replace(
            ['../uploads/story/', 'uploads/story/'],
            [asset('storage/story').'/', asset('storage/story').'/'],
            $content
        );
    @endphp

    <div class="space-y-5">
        <div class="card p-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-3">
                    <x-admin-image
                        :filename="$blog->image"
                        folder="storage/story"
                        class="w-full max-w-[220px] aspect-square rounded-lg object-cover border border-[var(--color-line)]"
                        icon="bi-journal-richtext" />
                </div>

                <div class="lg:col-span-9 space-y-3">
                    <h2 class="font-display text-2xl text-[var(--color-ink-2)]">{{ $blog->title }}</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        <div><span class="text-[var(--color-mute)]">Category:</span> <span class="text-[var(--color-ink-2)]">{{ $blog->category_name ?: '—' }}</span></div>
                        <div><span class="text-[var(--color-mute)]">Author:</span> <span class="text-[var(--color-ink-2)]">{{ $blog->admin ?: '—' }}</span></div>
                        <div><span class="text-[var(--color-mute)]">Status:</span>
                            @if ((int) $blog->status === 1)
                                <span class="pill pill-leaf">Published</span>
                            @else
                                <span class="pill pill-flame">Draft</span>
                            @endif
                        </div>
                        <div><span class="text-[var(--color-mute)]">Views:</span> <span class="text-[var(--color-ink-2)]">{{ $blog->views ?: '0' }}</span></div>
                        <div><span class="text-[var(--color-mute)]">Created:</span> <span class="text-[var(--color-ink-2)]">{{ $blog->date_created ? \Illuminate\Support\Carbon::parse($blog->date_created)->format('d M Y') : '—' }}</span></div>
                        <div><span class="text-[var(--color-mute)]">Updated:</span> <span class="text-[var(--color-ink-2)]">{{ $blog->date_updated ? \Illuminate\Support\Carbon::parse($blog->date_updated)->format('d M Y') : '—' }}</span></div>
                    </div>

                    @if (!empty($blog->tag))
                        <div><span class="text-[var(--color-mute)] text-sm">Tags:</span> <span class="text-[var(--color-ink-2)] text-sm">{{ $blog->tag }}</span></div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card p-6">
            <h3 class="font-display text-xl text-[var(--color-ink-2)] mb-4">Content</h3>
            <div class="prose max-w-none">
                {!! $content !!}
            </div>
        </div>
    </div>
@endsection
