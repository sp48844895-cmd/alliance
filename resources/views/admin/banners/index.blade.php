@extends('layouts.admin')

@section('title', 'Banners')
@section('breadcrumb')
    <span class="text-[var(--color-ink-2)]">Banners</span>
@endsection
@section('page_title', 'Banners')

@section('topbar_actions')
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i>
        <span>New banner</span>
    </a>
@endsection

@section('content')
    <x-admin.datatable-card
        :empty="$banners->isEmpty()"
        empty-icon="bi-inbox"
        empty-title="No banners yet"
        empty-text="Create a banner to feature on the homepage.">
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
                    <th>Links</th>
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
                                    <div class="text-[10px] font-semibold uppercase tracking-wider text-[var(--color-mute)] mb-1">Desktop</div>
                                    <div class="aspect-video bg-[var(--color-paper)] rounded border border-[var(--color-line)] overflow-hidden">
                                        <x-admin-image
                                            :filename="$banner->dbannerimg"
                                            folder="uploads/banners"
                                            alt="Desktop"
                                            class="w-full h-full object-contain"
                                            icon="bi-image" />
                                    </div>
                                </div>
                                <div class="w-14">
                                    <div class="text-[10px] font-semibold uppercase tracking-wider text-[var(--color-mute)] mb-1">Mobile</div>
                                    <div class="aspect-[9/16] bg-[var(--color-paper)] rounded border border-[var(--color-line)] overflow-hidden">
                                        <x-admin-image
                                            :filename="$banner->mbannerimg"
                                            folder="uploads/banners"
                                            alt="Mobile"
                                            class="w-full h-full object-contain"
                                            icon="bi-image" />
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if ($banner->ytlink)
                                <a href="{{ $banner->ytlink }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-xs text-[var(--color-flame)] hover:underline truncate max-w-[220px]">
                                    <i class="bi bi-youtube"></i>
                                    <span class="truncate">YouTube</span>
                                </a>
                            @endif
                            <div class="text-xs text-[var(--color-mute)] truncate mt-1" title="{{ $banner->redirect }}">
                                <i class="bi bi-link-45deg"></i>
                                <span>{{ $banner->redirect ?: 'No redirect' }}</span>
                            </div>
                        </td>
                        <td class="text-xs text-[var(--color-mute)] whitespace-nowrap">
                            {{ $banner->created_at ? \Illuminate\Support\Carbon::parse($banner->created_at)->format('d M Y') : '—' }}
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-ghost btn-sm" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
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
