<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', 'Author') · ABC Chhattisgarh</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,400;1,500;1,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <x-build-assets />
    @stack('head')
    <x-rich-editor-styles />
</head>
<body class="grain min-h-screen">

@php
    $portalType = auth()->user()->type ?? 'volunteer';
    $portalLabel = \App\Support\StoryContributor::portalLabel($portalType);
    $sections = [
        ['heading' => 'Overview', 'links' => [
            ['route' => 'author.dashboard', 'icon' => 'bi-grid-1x2', 'label' => 'Dashboard'],
        ]],
        ['heading' => 'Account', 'links' => [
            ['route' => 'author.profile.edit', 'icon' => 'bi-person', 'label' => 'My profile', 'wildcard' => 'author.profile.*'],
        ]],
        ['heading' => 'Stories', 'links' => [
            ['route' => 'author.stories.index', 'icon' => 'bi-journal-text', 'label' => 'My stories', 'wildcard' => 'author.stories.*'],
            ['route' => 'author.stories.create', 'icon' => 'bi-plus-lg', 'label' => 'New story'],
        ]],
    ];
    if ($portalType === 'intern') {
        array_unshift($sections, [
            'heading' => 'Intern',
            'links' => [
                ['route' => 'intern.dashboard', 'icon' => 'bi-mortarboard', 'label' => 'Work log'],
            ],
        ]);
    }
@endphp

<div class="flex min-h-screen">

    <div data-admin-overlay class="fixed inset-0 bg-black/40 z-30 lg:hidden hidden [&.is-visible]:block"></div>

    <aside data-admin-sidebar class="fixed lg:sticky top-0 z-40 h-screen w-[280px] -translate-x-full lg:translate-x-0 [&.is-open]:translate-x-0 transition-transform duration-300 bg-[var(--color-paper)] border-r border-[var(--color-line)] flex flex-col">

        <div class="px-5 py-5 border-b border-[var(--color-line)]">
            <a href="{{ route('author.dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-lg bg-[var(--color-clay-500)] flex items-center justify-center shrink-0">
                    <span class="font-display text-white text-lg leading-none font-semibold tracking-tight">A</span>
                </div>
                <div class="min-w-0">
                    <div class="font-display text-base leading-tight text-[var(--color-ink-2)] font-medium">Alliance for<br>Behavior Change</div>
                    <div class="text-[10px] uppercase tracking-[0.2em] text-[var(--color-mute-2)] mt-0.5">Chhattisgarh · {{ $portalLabel }}</div>
                </div>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-2">
            @foreach($sections as $section)
                <div class="nav-section">{{ $section['heading'] }}</div>
                @foreach($section['links'] as $link)
                    @php
                        $isActive = isset($link['wildcard'])
                            ? request()->routeIs($link['wildcard'])
                            : request()->routeIs($link['route']);
                        $href = \Illuminate\Support\Facades\Route::has($link['route'])
                            ? route($link['route'])
                            : '#';
                    @endphp
                    <a href="{{ $href }}" class="nav-link {{ $isActive ? 'is-active' : '' }}">
                        <i class="bi {{ $link['icon'] }} nav-icon"></i>
                        <span class="truncate">{{ $link['label'] }}</span>
                    </a>
                @endforeach
            @endforeach
        </nav>

        <div class="px-3 py-3 border-t border-[var(--color-line)]">
            <div class="flex items-center gap-3 px-2 py-2 rounded-lg bg-white border border-[var(--color-line)]">
                <div class="w-8 h-8 rounded-full bg-[var(--color-clay-100)] text-[var(--color-clay-700)] font-semibold flex items-center justify-center text-xs shrink-0">
                    {{ strtoupper(substr(auth()->user()->fname ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs font-semibold text-[var(--color-ink-2)] truncate">{{ auth()->user()->fname ?? 'Author' }} {{ auth()->user()->lname ?? '' }}</div>
                    <div class="text-[10px] text-[var(--color-mute-2)] truncate">{{ auth()->user()->email ?? '' }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-[var(--color-mute)] hover:text-[var(--color-flame)] transition" title="Sign out">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="flex-1 min-w-0 flex flex-col">
        <header class="sticky top-0 z-20 bg-white/85 backdrop-blur border-b border-[var(--color-line)]">
            <div class="flex items-center gap-4 px-4 lg:px-10 h-16">
                <button data-admin-toggle class="lg:hidden btn-ghost btn-sm" aria-label="Toggle sidebar">
                    <i class="bi bi-list text-lg"></i>
                </button>
                <div class="flex-1 min-w-0">
                    <div class="breadcrumb">
                        <a href="{{ route('author.dashboard') }}">Stories</a>
                        @hasSection('breadcrumb')
                            <i class="bi bi-chevron-right text-[10px]"></i>
                            @yield('breadcrumb')
                        @endif
                    </div>
                    <h1 class="font-display text-xl leading-tight text-[var(--color-ink-2)] font-medium">@yield('page_title', 'Author')</h1>
                </div>
                <div class="hidden md:flex items-center gap-2">
                    @yield('topbar_actions')
                </div>
            </div>
        </header>

        <main class="flex-1 px-4 lg:px-10 py-6 lg:py-8">
            @if (session('success'))
                <div data-auto-dismiss class="alert alert-success mb-4">
                    <i class="bi bi-check-circle-fill mt-0.5"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div data-auto-dismiss class="alert alert-error mb-4">
                    <i class="bi bi-exclamation-triangle-fill mt-0.5"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error mb-4">
                    <i class="bi bi-exclamation-triangle-fill mt-0.5"></i>
                    <div>
                        <div class="font-semibold mb-1">Please fix the following:</div>
                        <ul class="list-disc ml-5 space-y-0.5">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<x-rich-editor-scripts />
@stack('scripts')
</body>
</html>
