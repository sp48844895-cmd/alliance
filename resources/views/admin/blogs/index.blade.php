@extends('layouts.admin')

@section('title', 'Stories')
@section('page_title', 'Stories')

@section('breadcrumb')
    <span>Content</span>
    <i class="bi bi-chevron-right text-xs"></i>
    <span>Stories</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i>
        <span>Add story</span>
    </a>
@endsection

@section('content')
    <form method="GET" action="{{ route('admin.blogs.index') }}" class="card p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
            <div class="lg:col-span-4">
                <label class="label" for="cat_id">Category</label>
                <select id="cat_id" name="cat_id" class="select">
                    <option value="">All categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected((string) $filters['cat_id'] === (string) $cat->id)>{{ $cat->category_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-3">
                <label class="label" for="status">Status</label>
                <select id="status" name="status" class="select">
                    <option value="">All statuses</option>
                    <option value="1" @selected((string) $filters['status'] === '1')>Published</option>
                    <option value="0" @selected((string) $filters['status'] === '0')>Draft</option>
                </select>
            </div>
            <div class="lg:col-span-5 flex items-center gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-1">
                    <i class="bi bi-funnel"></i>
                    <span>Apply filters</span>
                </button>
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-ghost btn-sm">Clear</a>
            </div>
        </div>
    </form>

    <x-admin.datatable-card
        :empty="$blogs->isEmpty()"
        empty-icon="bi-journal-richtext"
        empty-title="No stories yet"
        empty-text="Add a story or loosen your filters.">
        <x-slot:emptyAction>
            <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i>
                <span>Add story</span>
            </a>
        </x-slot:emptyAction>
        <table data-admin-datatable class="table w-full">
            <thead>
                <tr>
                    <th class="no-sort w-14">Cover</th>
                    <th>Title</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th>Updated</th>
                    <th class="no-sort col-actions text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($blogs as $b)
                    <tr>
                        <td>
                            <x-admin-image
                                :filename="$b->image"
                                folder="storage/story"
                                class="w-10 h-10 rounded-lg object-cover border border-[var(--color-line)]"
                                icon="bi-journal-richtext" />
                        </td>
                        <td>
                            <a href="{{ route('admin.blogs.edit', $b->id) }}" class="font-semibold text-[var(--color-ink-2)] hover:text-[var(--color-clay-600)] line-clamp-2 leading-snug">
                                {{ $b->title }}
                            </a>
                        </td>
                        <td>
                            <div class="text-xs text-[var(--color-mute)] space-y-1">
                                <div>{{ $b->category_name ?: 'No category' }}</div>
                                <div>{{ $b->admin ?: 'No author' }} · {{ $b->views ?: '0' }} views</div>
                            </div>
                        </td>
                        <td data-status-cell>
                            <x-admin.status-pill
                                :active="(int) $b->status === 1"
                                activeLabel="Published"
                                inactiveLabel="Draft" />
                        </td>
                        <td class="text-xs text-[var(--color-mute)] whitespace-nowrap">
                            {{ \Illuminate\Support\Carbon::parse($b->date_updated)->format('d M Y') }}
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
                                    message="Delete this story permanently?" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-admin.datatable-card>
@endsection
