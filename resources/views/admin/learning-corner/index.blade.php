@extends('layouts.admin')

@section('title', 'Learning Corner')
@section('breadcrumb')
    <span class="text-[var(--color-ink-2)]">Learning Corner</span>
@endsection
@section('page_title', 'Learning Corner')

@section('topbar_actions')
    <a href="{{ route('admin.learning-corner.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        <span>New resource</span>
    </a>
@endsection

@section('content')
    <form method="GET" action="{{ route('admin.learning-corner.index') }}" class="card p-4 mb-5">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-5">
                <input type="text" name="q" value="{{ $filters['q'] }}" class="input"
                       placeholder="Search by title…">
            </div>
            <div class="md:col-span-3">
                <select name="cat_id" class="select">
                    <option value="">All categories</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}" {{ (string) $filters['cat_id'] === (string) $c->id ? 'selected' : '' }}>
                            {{ $c->cat_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <select name="m_type" class="select">
                    <option value="">All types</option>
                    @foreach (['book', 'posters', 'mobile kunji', 'video'] as $t)
                        <option value="{{ $t }}" {{ $filters['m_type'] === $t ? 'selected' : '' }}>
                            {{ ucwords($t) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="bi bi-funnel"></i>
                    <span>Filter</span>
                </button>
                @if ($filters['q'] !== '' || $filters['cat_id'] || $filters['m_type'])
                    <a href="{{ route('admin.learning-corner.index') }}" class="btn btn-ghost" title="Clear">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>

    @if ($resources->isEmpty())
        <div class="card p-10 text-center">
            <div class="w-14 h-14 rounded-full bg-[var(--color-paper-2)] flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-inbox text-2xl text-[var(--color-mute-2)]"></i>
            </div>
            <h3 class="font-display text-lg text-[var(--color-ink-2)] mb-1">No resources yet</h3>
            <p class="text-sm text-[var(--color-mute)] mb-5">Add the first learning resource to share with your community.</p>
            <a href="{{ route('admin.learning-corner.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                <span>New resource</span>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 stagger">
            @foreach ($resources as $r)
                <div class="card overflow-hidden flex flex-col relative">
                    <div class="absolute top-2 right-2 z-10 flex items-center gap-1.5">
                        <a href="{{ route('admin.learning-corner.edit', $r->id) }}"
                           class="w-8 h-8 rounded-full bg-white/90 backdrop-blur border border-[var(--color-line)] text-[var(--color-ink-2)] hover:text-[var(--color-clay-700)] flex items-center justify-center transition shadow-sm"
                           title="Edit">
                            <i class="bi bi-pencil text-xs"></i>
                        </a>
                        <x-admin.delete-form
                            :action="route('admin.learning-corner.destroy', $r->id)"
                            message="Delete this resource?"
                            buttonClass="w-8 h-8 rounded-full bg-white/90 backdrop-blur border border-[var(--color-line)] text-[var(--color-flame)] hover:bg-[var(--color-flame-soft)] flex items-center justify-center transition shadow-sm"
                            iconClass="text-xs" />
                    </div>

                    <div class="h-40 w-full bg-[var(--color-paper)] overflow-hidden">
                        <x-admin-image
                            :filename="$r->image"
                            folder="uploads/learning"
                            :alt="$r->title"
                            class="object-cover h-40 w-full rounded-t"
                            icon="bi-image" />
                    </div>

                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="pill pill-clay">{{ ucwords($r->m_type) }}</span>
                            <span class="text-xs text-[var(--color-mute)] truncate">{{ $r->cat_name ?? '—' }}</span>
                        </div>
                        <h3 class="font-display text-base text-[var(--color-ink-2)] mt-1 leading-snug line-clamp-2">
                            {{ $r->title }}
                        </h3>
                        @if ($r->content)
                            <p class="text-sm text-[var(--color-mute)] mt-2 line-clamp-2">{{ $r->content }}</p>
                        @endif
                    </div>

                    <div class="border-t border-[var(--color-line)] px-4 py-3 flex items-center justify-between text-xs text-[var(--color-mute)]">
                        <a href="{{ $r->link }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-1.5 hover:text-[var(--color-clay-700)] truncate max-w-[60%]">
                            <i class="bi bi-box-arrow-up-right"></i>
                            <span class="truncate">Open</span>
                        </a>
                        <span class="inline-flex items-center gap-1.5">
                            <i class="bi bi-calendar3"></i>
                            <span>{{ $r->date ? \Illuminate\Support\Carbon::parse($r->date)->format('d M Y') : '—' }}</span>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $resources->links() }}
        </div>
    @endif
@endsection
