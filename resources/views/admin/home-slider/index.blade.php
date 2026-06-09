@extends('layouts.admin')

@section('title', 'Home Slider')
@section('page_title', 'Home Card Slider')
@section('breadcrumb')
    <span class="text-[var(--color-ink-2)]">Home slider</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.home-slider.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i>
        New slide
    </a>
@endsection

@section('content')
    <form method="GET" action="{{ route('admin.home-slider.index') }}" class="card p-4 mb-5">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            <div class="md:col-span-8">
                <label class="label" for="status">Status</label>
                <select id="status" name="status" class="select">
                    <option value="">All statuses</option>
                    <option value="1" @selected($status === '1')>Active</option>
                    <option value="0" @selected($status === '0')>Inactive</option>
                </select>
            </div>
            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-1">
                    <i class="bi bi-funnel"></i>
                    <span>Apply filters</span>
                </button>
                @if ($status !== '')
                    <a href="{{ route('admin.home-slider.index') }}" class="btn btn-ghost btn-sm" title="Clear">Clear</a>
                @endif
            </div>
        </div>
    </form>

    <x-admin.datatable-card
        :empty="$slides->isEmpty()"
        empty-icon="bi-sliders"
        empty-title="No slider slides yet"
        empty-text="Add slides to power the circular card slider on the home page.">
        <x-slot:emptyAction>
            <a href="{{ route('admin.home-slider.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i>
                New slide
            </a>
        </x-slot:emptyAction>
        <table data-admin-datatable class="table w-full">
            <thead>
                <tr>
                    <th class="no-sort">Image</th>
                    <th>Slide</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th class="no-sort col-actions text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($slides as $row)
                    <tr>
                        <td>
                            <div class="w-16 aspect-[173/231] bg-[var(--color-paper)] rounded border border-[var(--color-line)] overflow-hidden">
                                <x-admin-image
                                    :filename="$row->image"
                                    folder="uploads/home-slider"
                                    alt="{{ $row->title }}"
                                    class="w-full h-full object-cover"
                                    icon="bi-image" />
                            </div>
                        </td>
                        <td>
                            <div class="font-medium text-[var(--color-ink-2)]">{{ $row->title }}</div>
                            <div class="text-xs text-[var(--color-mute-2)] mt-0.5 line-clamp-2">{{ Str::limit($row->short_description, 100) }}</div>
                        </td>
                        <td>
                            <div class="text-xs text-[var(--color-mute)] space-y-1">
                                @if ($row->url)
                                    <div class="line-clamp-1">{{ $row->url }}</div>
                                @else
                                    <div>No link</div>
                                @endif
                                <div>Order {{ $row->sort_order }}</div>
                            </div>
                        </td>
                        <td data-status-cell>
                            <x-admin.status-pill :active="(bool) $row->status" />
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.home-slider.edit', $row->id) }}" class="btn btn-ghost btn-sm" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <x-admin.toggle-form
                                    :action="route('admin.home-slider.toggle', $row->id)"
                                    :active="(bool) $row->status" />
                                <x-admin.delete-form
                                    :action="route('admin.home-slider.destroy', $row->id)"
                                    message="Delete this slide? This cannot be undone."
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
