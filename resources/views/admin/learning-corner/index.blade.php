@extends('layouts.admin')

@section('title', 'Learning resources')
@section('breadcrumb')
    <span class="text-[var(--color-ink-2)]">Learning resources</span>
@endsection
@section('page_title', 'Learning resources')

@section('topbar_actions')
    <a href="{{ route('admin.learning-corner.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i>
        <span>New resource</span>
    </a>
@endsection

@section('content')
    <p class="text-sm text-[var(--color-mute)] mb-5">Step 3: Add learning materials under a subcategory.</p>

    <form method="GET" action="{{ route('admin.learning-corner.index') }}" class="card p-4 mb-5">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            <div class="md:col-span-3">
                <label class="label" for="main_id">Main category</label>
                <select id="main_id" name="main_id" class="select">
                    <option value="">All main</option>
                    @foreach ($mainCategories as $m)
                        <option value="{{ $m->id }}" @selected((string) $filters['main_id'] === (string) $m->id)>
                            {{ $m->cat_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3">
                <label class="label" for="cat_id">Subcategory</label>
                <select id="cat_id" name="cat_id" class="select">
                    <option value="">All subcategories</option>
                    @foreach ($subcategories as $c)
                        <option value="{{ $c->id }}" @selected((string) $filters['cat_id'] === (string) $c->id)>
                            {{ $c->main_name }} › {{ $c->cat_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3">
                <label class="label" for="m_type">Resource type</label>
                <select id="m_type" name="m_type" class="select">
                    <option value="">All types</option>
                    @foreach (['book', 'posters', 'mobile kunji', 'video'] as $t)
                        <option value="{{ $t }}" @selected($filters['m_type'] === $t)>
                            {{ ucwords($t) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3 flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-1">
                    <i class="bi bi-funnel"></i>
                    <span>Apply</span>
                </button>
                @if ($filters['main_id'] || $filters['cat_id'] || $filters['m_type'])
                    <a href="{{ route('admin.learning-corner.index') }}" class="btn btn-ghost btn-sm" title="Clear">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>

    <x-admin.datatable-card
        :empty="$resources->isEmpty()"
        empty-icon="bi-inbox"
        empty-title="No resources yet"
        empty-text="Add a learning resource under a subcategory.">
        <x-slot:emptyAction>
            <a href="{{ route('admin.learning-corner.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i>
                <span>New resource</span>
            </a>
        </x-slot:emptyAction>
        <table data-admin-datatable class="table w-full">
            <thead>
                <tr>
                    <th class="no-sort w-16">Thumb</th>
                    <th>Title & type</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th class="no-sort col-actions text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($resources as $r)
                    <tr>
                        <td>
                            <x-admin-image
                                :filename="$r->image"
                                folder="uploads/learning"
                                :alt="$r->title"
                                class="w-12 h-12 rounded-md object-cover border border-[var(--color-line)]"
                                icon="bi-image" />
                        </td>
                        <td>
                            <div class="font-medium text-[var(--color-ink-2)] line-clamp-2">{{ $r->title }}</div>
                            <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                <span class="pill pill-clay text-xs">{{ ucwords($r->m_type) }}</span>
                                @if (isset($r->status) && (int) $r->status === 0)
                                    <span class="pill pill-mute text-xs">Draft</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-sm text-[var(--color-mute)]">
                            {{ $r->main_name }} › {{ $r->sub_name }}
                        </td>
                        <td class="text-xs text-[var(--color-mute)] whitespace-nowrap">
                            {{ $r->date ? \Illuminate\Support\Carbon::parse($r->date)->format('d M Y') : '—' }}
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-1">
                                @if ($r->link)
                                    <a href="{{ $r->link }}" target="_blank" rel="noopener" class="btn btn-ghost btn-sm" title="Open link">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                @endif
                                <a href="{{ route('admin.learning-corner.edit', $r->id) }}" class="btn btn-ghost btn-sm" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <x-admin.delete-form
                                    :action="route('admin.learning-corner.destroy', $r->id)"
                                    message="Delete this resource?"
                                    buttonClass="btn btn-ghost btn-sm"
                                    iconClass="text-[var(--color-flame)]" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-admin.datatable-card>
@endsection
