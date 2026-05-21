@extends('layouts.admin')

@section('title', 'Stories')
@section('page_title', 'Stories')

@section('breadcrumb')
    <span>Content</span>
    <i class="bi bi-chevron-right text-[10px]"></i>
    <span>Stories</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i>
        <span>New story</span>
    </a>
@endsection

@section('content')
    <form method="GET" action="{{ route('admin.blogs.index') }}" class="card p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
            <div class="lg:col-span-5">
                <label class="label" for="q">Search</label>
                <div class="relative">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-[var(--color-mute-2)] text-sm"></i>
                    <input id="q" type="search" name="q" value="{{ $filters['q'] }}" placeholder="Search by title..." class="input pl-9">
                </div>
            </div>

            <div class="lg:col-span-3">
                <label class="label" for="cat_id">Category</label>
                <select id="cat_id" name="cat_id" class="select" onchange="this.form.submit()">
                    <option value="">All categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected((string) $filters['cat_id'] === (string) $cat->id)>{{ $cat->category_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2">
                <label class="label" for="status">Status</label>
                <select id="status" name="status" class="select" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="1" @selected((string) $filters['status'] === '1')>Published</option>
                    <option value="0" @selected((string) $filters['status'] === '0')>Draft</option>
                </select>
            </div>

            <div class="lg:col-span-2 flex items-center gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-1">
                    <i class="bi bi-funnel"></i>
                    <span>Filter</span>
                </button>
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-ghost btn-sm">Reset</a>
            </div>
        </div>
    </form>

    @if($blogs->isEmpty())
        <div class="card p-10 text-center">
            <i class="bi bi-inbox text-3xl text-[var(--color-mute-2)]"></i>
            <div class="font-display text-lg mt-3">No stories found</div>
            <div class="text-sm text-[var(--color-mute)] mt-1">Try changing filters or create a new story.</div>
            <div class="mt-4">
                <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i>
                    <span>New story</span>
                </a>
            </div>
        </div>
    @else
        <div class="card overflow-hidden">
            <table class="table w-full table-fixed [&_tbody_td]:align-top [&_tbody_td]:py-4">
                <thead>
                    <tr>
                        <th class="w-14">Image</th>
                        <th class="w-[11rem] lg:w-[12rem]">Title</th>
                        <th class="w-28 hidden sm:table-cell">Category</th>
                        <th class="w-24 hidden lg:table-cell">Author</th>
                        <th class="w-24">Status</th>
                        <th class="w-14 hidden lg:table-cell">Views</th>
                        <th class="w-28 hidden sm:table-cell">Updated</th>
                        <th class="w-32 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($blogs as $b)
                        <tr>
                            <td class="align-middle">
                                <x-admin-image
                                    :filename="$b->image"
                                    folder="storage/story"
                                    class="w-10 h-10 rounded-lg object-cover border border-[var(--color-line)]"
                                    icon="bi-journal-richtext" />
                            </td>
                            <td class="w-[11rem] lg:w-[12rem] max-w-0">
                                <a href="{{ route('admin.blogs.edit', $b->id) }}" class="font-semibold text-[var(--color-ink-2)] hover:text-[var(--color-clay-600)] line-clamp-2 block leading-snug break-words">
                                    {{ $b->title }}
                                </a>
                            </td>
                            <td class="hidden sm:table-cell max-w-0">
                                @if($b->category_name)
                                    <span class="pill pill-clay line-clamp-2 max-w-full inline-block leading-snug">
                                        {{ $b->category_name }}
                                    </span>
                                @else
                                    <span class="text-xs text-[var(--color-mute-2)]">—</span>
                                @endif
                            </td>
                            <td class="hidden lg:table-cell max-w-0">
                                <span class="text-sm text-[var(--color-ink-2)] line-clamp-2 block leading-snug break-words">{{ $b->admin ?: '—' }}</span>
                            </td>
                            <td>
                                <x-admin.status-pill
                                    :active="(int) $b->status === 1"
                                    activeLabel="Published"
                                    inactiveLabel="Draft" />
                            </td>
                            <td class="hidden lg:table-cell">
                                <span class="font-mono text-xs text-[var(--color-mute)]">{{ $b->views ?: '0' }}</span>
                            </td>
                            <td class="hidden sm:table-cell whitespace-nowrap">
                                <span class="text-xs text-[var(--color-mute)] whitespace-nowrap">{{ \Illuminate\Support\Carbon::parse($b->date_updated)->format('d M Y') }}</span>
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.blogs.show', $b->id) }}" class="btn btn-ghost btn-sm" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.blogs.edit', $b->id) }}" class="btn btn-ghost btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <x-admin.toggle-form
                                        :action="route('admin.blogs.toggle', $b->id)"
                                        :active="(bool) $b->status"
                                        variant="publish"
                                        activeTitle="Unpublish"
                                        inactiveTitle="Publish" />
                                    <x-admin.delete-form
                                        :action="route('admin.blogs.destroy', $b->id)"
                                        message="Are you sure you want to delete this story permanently?" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $blogs->withQueryString()->links() }}
        </div>
    @endif
@endsection
