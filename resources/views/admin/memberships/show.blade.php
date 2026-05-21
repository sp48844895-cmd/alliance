@extends('layouts.admin')

@section('title', $member->name)
@section('page_title', $member->name)
@section('breadcrumb')
    <a href="{{ route('admin.memberships.index') }}">Memberships</a>
    <i class="bi bi-chevron-right text-[10px]"></i>
    <span class="text-[var(--color-ink-2)]">{{ \Illuminate\Support\Str::limit($member->name, 32) }}</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.memberships.edit', $member->id) }}" class="btn btn-ghost btn-sm">
        <i class="bi bi-pencil"></i> Edit
    </a>
    <form method="POST" action="{{ route('admin.memberships.destroy', $member->id) }}"
        data-confirm="Delete this membership? This cannot be undone.">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">
            <i class="bi bi-trash"></i> Delete
        </button>
    </form>
@endsection

@section('content')
@php
    $typeMap = [
        'Individual'        => 'pill-river',
        'individual'        => 'pill-river',
        'CSO/NGO'           => 'pill-leaf',
        'Volunteer'         => 'pill-amber',
        'Firm/Organization' => 'pill-clay',
    ];
    $typeClass = $typeMap[$member->type] ?? 'pill-mute';
    $socials = array_filter([
        'Facebook'  => $member->fb,
        'Instagram' => $member->insta,
        'Twitter'   => $member->twitter,
        'YouTube'   => $member->youtube,
        'Website'   => $member->website,
    ], fn ($v) => !empty($v));

    $socialIcons = [
        'Facebook'  => 'bi-facebook',
        'Instagram' => 'bi-instagram',
        'Twitter'   => 'bi-twitter-x',
        'YouTube'   => 'bi-youtube',
        'Website'   => 'bi-globe2',
    ];
@endphp

<div class="space-y-5 stagger">

    <div class="card grain p-6 lg:p-8">
        <div class="flex flex-col sm:flex-row items-start gap-5">
            @if (!empty($memberImageUrl))
                <img src="{{ $memberImageUrl }}"
                    alt="{{ $member->name }}"
                    class="w-20 h-20 rounded-full object-cover border-2 border-[var(--color-clay-100)] shrink-0">
            @else
                <div class="w-20 h-20 rounded-full bg-[var(--color-clay-100)] text-[var(--color-clay-700)] font-display text-3xl flex items-center justify-center shrink-0">
                    {{ strtoupper(substr($member->name ?? '?', 0, 1)) }}
                </div>
            @endif

            <div class="flex-1 min-w-0">
                <h2 class="font-display text-2xl text-[var(--color-ink-2)] leading-tight">
                    {{ $member->name }}
                </h2>
                <div class="flex flex-wrap items-center gap-2 mt-2">
                    <span class="pill {{ $typeClass }}">{{ $member->type ?: 'Member' }}</span>
                    <span class="font-mono text-xs text-[var(--color-clay-700)] bg-[var(--color-clay-50)] px-2 py-0.5 rounded">
                        {{ $member->code }}
                    </span>
                    <span class="text-xs text-[var(--color-mute)]">
                        <i class="bi bi-calendar3"></i>
                        Joined {{ $member->date ? date('d M Y', strtotime($member->date)) : '—' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <div class="card p-5 lg:p-6 space-y-4">
            <h3 class="font-display text-lg text-[var(--color-ink-2)]">Contact</h3>

            <div>
                <div class="label">Mobile</div>
                <div class="text-sm text-[var(--color-ink-2)]">
                    @if ($member->mobile)
                        <a href="tel:{{ $member->mobile }}" class="hover:text-[var(--color-clay-500)]">
                            <i class="bi bi-telephone"></i> {{ $member->mobile }}
                        </a>
                    @else — @endif
                </div>
            </div>

            <div>
                <div class="label">Email</div>
                <div class="text-sm text-[var(--color-ink-2)] break-all">
                    @if ($member->email)
                        <a href="mailto:{{ $member->email }}" class="hover:text-[var(--color-clay-500)]">
                            <i class="bi bi-envelope"></i> {{ $member->email }}
                        </a>
                    @else — @endif
                </div>
            </div>

            <div>
                <div class="label">Address</div>
                <div class="text-sm text-[var(--color-ink-2)]">{{ $member->address ?: '—' }}</div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <div class="label">District</div>
                    <div class="text-sm text-[var(--color-ink-2)]">{{ $districtName ?: '—' }}</div>
                </div>
                <div>
                    <div class="label">Block</div>
                    <div class="text-sm text-[var(--color-ink-2)]">{{ $blockName ?: '—' }}</div>
                </div>
            </div>
        </div>

        <div class="card p-5 lg:p-6 space-y-4 lg:col-span-2">
            <h3 class="font-display text-lg text-[var(--color-ink-2)]">About</h3>

            <div>
                <div class="label">Organisation intro</div>
                <p class="text-sm text-[var(--color-ink-2)] leading-relaxed whitespace-pre-line">
                    {{ $member->org_intro ?: '—' }}
                </p>
            </div>

            <div>
                <div class="label">Areas of work</div>
                @if (count($areas))
                    <div class="flex flex-wrap gap-1.5 mt-1">
                        @foreach ($areas as $area)
                            <span class="pill pill-clay">{{ $area }}</span>
                        @endforeach
                    </div>
                @else
                    <div class="text-sm text-[var(--color-mute)]">—</div>
                @endif
            </div>
        </div>
    </div>

    @if (!empty($member->ngo_organization))
        <div class="card p-5 lg:p-6">
            <h3 class="font-display text-lg text-[var(--color-ink-2)] mb-2">Affiliated organisation</h3>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[var(--color-leaf-soft)] text-[var(--color-leaf)] flex items-center justify-center">
                    <i class="bi bi-building"></i>
                </div>
                <div class="text-sm font-medium text-[var(--color-ink-2)]">{{ $member->ngo_organization }}</div>
            </div>
        </div>
    @endif

    @if (count($socials))
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-[var(--color-line)]">
                <h3 class="font-display text-lg text-[var(--color-ink-2)]">Social presence</h3>
            </div>
            <table class="table">
                <tbody>
                    @foreach ($socials as $platform => $url)
                        <tr>
                            <td class="w-40">
                                <span class="inline-flex items-center gap-2 text-sm font-medium text-[var(--color-ink-2)]">
                                    <i class="bi {{ $socialIcons[$platform] ?? 'bi-link-45deg' }}"></i>
                                    {{ $platform }}
                                </span>
                            </td>
                            <td class="text-sm break-all">
                                <a href="{{ \Illuminate\Support\Str::startsWith($url, ['http://', 'https://']) ? $url : '#' }}"
                                    target="_blank" rel="noopener"
                                    class="text-[var(--color-river)] hover:text-[var(--color-clay-500)]">
                                    {{ $url }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
