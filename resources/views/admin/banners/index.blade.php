@extends('layouts.admin')

@section('title', 'Banners')
@section('breadcrumb')
    <span class="text-[var(--color-ink-2)]">Banners</span>
@endsection
@section('page_title', 'Homepage banners')

@section('topbar_actions')
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i>
        <span>New banner</span>
    </a>
@endsection

@section('content')
    <form method="GET" action="{{ route('admin.banners.index') }}" class="card p-4 mb-5">
        <div class="flex flex-wrap items-end gap-4">
            <div class="min-w-[160px]">
                <label class="label" for="status">Status</label>
                <select name="status" id="status" class="select">
                    <option value="" @selected($status === '')>All</option>
                    <option value="1" @selected($status === '1')>Active</option>
                    <option value="0" @selected($status === '0')>Inactive</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                @if ($status !== '')
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-ghost btn-sm" title="Clear">Clear</a>
                @endif
            </div>
        </div>
    </form>

    <x-admin.datatable-card
        :empty="$banners->isEmpty()"
        empty-icon="bi-inbox"
        empty-title="No banners yet"
        empty-text="Create a banner with title, description, background image, front image, and optional link.">
        <x-slot:emptyAction>
            <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i>
                <span>New banner</span>
            </a>
        </x-slot:emptyAction>
        <table data-admin-datatable class="table w-full">
            <thead>
                <tr>
                    <th class="no-sort">Preview</th>
                    <th>Content</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Added</th>
                    <th class="no-sort col-actions text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($banners as $banner)
                    <tr>
                        <td>
                            <div class="flex gap-2">
                                <div class="w-20">
                                    <div class="text-[10px] font-semibold uppercase tracking-wider text-[var(--color-mute)] mb-1">Background</div>
                                    <div class="aspect-video bg-[var(--color-paper)] rounded border border-[var(--color-line)] overflow-hidden">
                                        <x-admin-image
                                            :filename="$banner->dbannerimg"
                                            folder="uploads/banners"
                                            alt="Background"
                                            class="w-full h-full object-contain"
                                            icon="bi-image" />
                                    </div>
                                </div>
                                <div class="w-14">
                                    <div class="text-[10px] font-semibold uppercase tracking-wider text-[var(--color-mute)] mb-1">Front</div>
                                    <div class="aspect-[9/16] bg-[var(--color-paper)] rounded border border-[var(--color-line)] overflow-hidden">
                                        <x-admin-image
                                            :filename="$banner->front_image ?? ''"
                                            folder="uploads/banners"
                                            alt="Front"
                                            class="w-full h-full object-contain"
                                            icon="bi-image" />
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if ($banner->small_title)
                                <div class="text-[10px] font-semibold uppercase tracking-wider text-[var(--color-mute)]">{{ $banner->small_title }}</div>
                            @endif
                            <div class="font-semibold text-sm">{{ $banner->title ?: '—' }}</div>
                            <div class="text-xs text-[var(--color-mute)] line-clamp-2 mt-1">{{ $banner->description ?: '—' }}</div>
                            @if ($banner->url)
                                <div class="text-xs text-[var(--color-flame)] truncate mt-1 max-w-[220px]" title="{{ $banner->url }}">
                                    <i class="bi bi-link-45deg"></i> {{ $banner->url }}
                                </div>
                            @endif
                        </td>
                        <td class="text-sm text-[var(--color-mute)]">{{ $banner->sort_order ?? 0 }}</td>
                        <td data-status-cell>
                            <x-admin.status-pill :active="(int) ($banner->status ?? 1) === 1" />
                        </td>
                        <td class="text-xs text-[var(--color-mute)] whitespace-nowrap">
                            {{ $banner->created_at ? \Illuminate\Support\Carbon::parse($banner->created_at)->format('d M Y') : '—' }}
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-ghost btn-sm" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <x-admin.toggle-form
                                    :action="route('admin.banners.toggle', $banner->id)"
                                    :active="(int) ($banner->status ?? 1) === 1" />
                                <x-admin.delete-form
                                    :action="route('admin.banners.destroy', $banner->id)"
                                    message="Delete this banner?"
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
