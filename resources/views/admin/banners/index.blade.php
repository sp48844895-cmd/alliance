@extends('layouts.admin')

@section('title', 'Banners')
@section('breadcrumb')
    <span class="text-[var(--color-ink-2)]">Banners</span>
@endsection
@section('page_title', 'Banners')

@section('topbar_actions')
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        <span>New banner</span>
    </a>
@endsection

@section('content')
    @if ($banners->isEmpty())
        <div class="card p-10 text-center">
            <div class="w-14 h-14 rounded-full bg-[var(--color-paper-2)] flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-inbox text-2xl text-[var(--color-mute-2)]"></i>
            </div>
            <h3 class="font-display text-lg text-[var(--color-ink-2)] mb-1">No banners yet</h3>
            <p class="text-sm text-[var(--color-mute)] mb-5">Create one to feature on the homepage.</p>
            <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                <span>New banner</span>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 stagger">
            @foreach ($banners as $banner)
                <div class="card overflow-hidden flex flex-col">
                    <div class="grid grid-cols-2 gap-2 p-3 bg-[var(--color-paper)]">
                        <div>
                            <div class="text-[10px] font-semibold uppercase tracking-wider text-[var(--color-mute)] mb-1.5">Desktop</div>
                            <div class="aspect-video bg-white rounded-md border border-[var(--color-line)] overflow-hidden">
                                <x-admin-image
                                    :filename="$banner->dbannerimg"
                                    folder="uploads/banners"
                                    alt="Desktop banner"
                                    class="w-full h-full object-contain"
                                    icon="bi-image" />
                            </div>
                        </div>
                        <div>
                            <div class="text-[10px] font-semibold uppercase tracking-wider text-[var(--color-mute)] mb-1.5">Mobile</div>
                            <div class="aspect-[9/16] bg-white rounded-md border border-[var(--color-line)] overflow-hidden max-h-44 mx-auto">
                                <x-admin-image
                                    :filename="$banner->mbannerimg"
                                    folder="uploads/banners"
                                    alt="Mobile banner"
                                    class="w-full h-full object-contain"
                                    icon="bi-image" />
                            </div>
                        </div>
                    </div>

                    <div class="p-4 flex-1 flex flex-col gap-2">
                        @if ($banner->ytlink)
                            <a href="{{ $banner->ytlink }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-xs text-[var(--color-flame)] hover:underline truncate">
                                <i class="bi bi-youtube text-base"></i>
                                <span class="truncate">{{ $banner->ytlink }}</span>
                            </a>
                        @endif
                        <div class="text-xs text-[var(--color-mute)] truncate" title="{{ $banner->redirect }}">
                            <i class="bi bi-link-45deg"></i>
                            <span>{{ $banner->redirect }}</span>
                        </div>
                        <div class="text-[11px] text-[var(--color-mute-2)] mt-auto pt-2">
                            <i class="bi bi-calendar3"></i>
                            <span>{{ $banner->created_at ? \Illuminate\Support\Carbon::parse($banner->created_at)->format('d M Y') : '—' }}</span>
                        </div>
                    </div>

                    <div class="border-t border-[var(--color-line)] px-4 py-3 flex items-center justify-end gap-2">
                        <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-ghost btn-sm">
                            <i class="bi bi-pencil"></i>
                            <span>Edit</span>
                        </a>
                        <x-admin.delete-form
                            :action="route('admin.banners.destroy', $banner->id)"
                            message="Delete this banner?" />
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
