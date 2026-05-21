<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', 'Admin') · ABC Chhattisgarh</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght,SOFT,WONK@0,9..144,300..700,30..100,0..1;1,9..144,300..700,30..100,0..1&family=Manrope:wght@400;500;600;700&family=Caveat:wght@500;600&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="admin-panel grain min-h-screen">

@php
    $sections = [
        ['heading' => 'Overview', 'links' => [
            ['route' => 'admin.dashboard', 'icon' => 'bi-grid-1x2', 'label' => 'Dashboard'],
        ]],
        ['heading' => 'Content', 'links' => [
            ['route' => 'admin.blogs.index',      'icon' => 'bi-journal-richtext', 'label' => 'Stories',          'wildcard' => 'admin.blogs.*'],
            ['route' => 'admin.stories.index',    'icon' => 'bi-book',             'label' => 'Story approvals', 'wildcard' => 'admin.stories.*'],
            ['route' => 'admin.categories.index', 'icon' => 'bi-tags',             'label' => 'Categories',     'wildcard' => 'admin.categories.*'],
            ['route' => 'admin.events.index',     'icon' => 'bi-calendar-event',   'label' => 'Events',         'wildcard' => 'admin.events.*'],
            ['route' => 'admin.programs.index',   'icon' => 'bi-layout-text-window', 'label' => 'Programs',     'wildcard' => 'admin.programs.*'],
            ['route' => 'admin.banners.index',    'icon' => 'bi-image',            'label' => 'Banners',        'wildcard' => 'admin.banners.*'],
            ['route' => 'admin.sbc-pool.index',   'icon' => 'bi-person-badge',     'label' => 'SBC Resource Pool', 'wildcard' => 'admin.sbc-pool.*'],
        ]],
        ['heading' => 'Learning', 'links' => [
            ['route' => 'admin.learning-cats.index',     'icon' => 'bi-bookmark-star', 'label' => 'Learning Categories', 'wildcard' => 'admin.learning-cats.*'],
            ['route' => 'admin.learning-corner.index',   'icon' => 'bi-collection',    'label' => 'Learning Corner',     'wildcard' => 'admin.learning-corner.*'],
        ]],
        ['heading' => 'Community', 'links' => [
            ['route' => 'admin.memberships.index', 'icon' => 'bi-people',     'label' => 'Memberships',  'wildcard' => 'admin.memberships.*'],
            ['route' => 'admin.contact-messages.index', 'icon' => 'bi-chat-dots', 'label' => 'Contact Messages', 'wildcard' => 'admin.contact-messages.*'],
            ['route' => 'admin.mails.index',       'icon' => 'bi-envelope',   'label' => 'Contact Mail', 'wildcard' => 'admin.mails.*'],
        ]],
        ['heading' => 'Geography', 'links' => [
            ['route' => 'admin.districts.index', 'icon' => 'bi-map',          'label' => 'Districts', 'wildcard' => 'admin.districts.*'],
            ['route' => 'admin.blocks.index',    'icon' => 'bi-geo-alt',      'label' => 'Blocks',    'wildcard' => 'admin.blocks.*'],
        ]],
        ['heading' => 'Configuration', 'links' => [
            ['route' => 'admin.settings.edit', 'icon' => 'bi-gear',          'label' => 'Site Settings', 'wildcard' => 'admin.settings.*'],
            ['route' => 'admin.users.index',   'icon' => 'bi-shield-lock',   'label' => 'Users',         'wildcard' => 'admin.users.*'],
        ]],
    ];
@endphp

<div class="flex min-h-screen">

    <div data-admin-overlay class="fixed inset-0 bg-black/40 z-30 lg:hidden hidden [&.is-visible]:block"></div>

    <aside data-admin-sidebar class="fixed lg:sticky top-0 z-40 h-screen w-[280px] -translate-x-full lg:translate-x-0 [&.is-open]:translate-x-0 transition-transform duration-300 bg-[var(--color-paper)] border-r border-[var(--color-line)] flex flex-col">

        <div class="relative overflow-hidden border-b border-[var(--color-line)]">
            <div class="absolute inset-0 opacity-100" style="background: linear-gradient(135deg, #5d2cb5 0%, #3237f0 100%);"></div>
            <div class="absolute inset-0 opacity-20" style="background-image: url(\"data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='60' height='60'><g fill='none' stroke='white' stroke-width='0.8' opacity='0.6'><circle cx='30' cy='30' r='25'/><circle cx='30' cy='30' r='16'/><circle cx='30' cy='30' r='8'/></g><g fill='white' opacity='0.5'><circle cx='30' cy='5' r='2'/><circle cx='55' cy='30' r='2'/><circle cx='30' cy='55' r='2'/><circle cx='5' cy='30' r='2'/></g></svg>\"); background-size: 60px 60px; background-position: right -10px top -10px; background-repeat: no-repeat;"></div>
            <div class="relative px-5 py-5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center shrink-0">
                        <span class="font-display text-white text-lg leading-none font-semibold tracking-tight">A</span>
                    </div>
                    <div class="min-w-0">
                        <div class="font-display text-base leading-tight text-white font-medium tracking-tight">Alliance for<br>Behaviour Change</div>
                        <div class="text-[10px] uppercase tracking-[0.2em] text-white/70 mt-0.5">Chhattisgarh · Admin</div>
                    </div>
                </a>
            </div>
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
            <div class="flex items-center gap-3 px-2 py-2 rounded-xl bg-white border border-[var(--color-line)]">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs shrink-0 text-white font-bold" style="background: linear-gradient(135deg, #5d2cb5, #3237f0);">
                    {{ strtoupper(substr(auth()->user()->fname ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs font-semibold text-[var(--color-ink-2)] truncate">{{ auth()->user()->fname ?? 'Admin' }} {{ auth()->user()->lname ?? '' }}</div>
                    <div class="text-[10px] font-semibold text-[var(--color-mute)] truncate">{{ auth()->user()->email ?? '' }}</div>
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
        <header class="sticky top-0 z-20 bg-white/90 backdrop-blur border-b border-[var(--color-line)] relative">
            <div class="absolute top-0 left-0 right-0 h-[2px]" style="background: linear-gradient(to right, #5d2cb5, #3237f0);"></div>
            <div class="flex items-center gap-4 px-4 lg:px-10 h-16">
                <button data-admin-toggle class="lg:hidden btn-ghost btn-sm" aria-label="Toggle sidebar">
                    <i class="bi bi-list text-lg"></i>
                </button>
                <div class="flex-1 min-w-0">
                    <div class="breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Admin</a>
                        @hasSection('breadcrumb')
                            <i class="bi bi-chevron-right text-[10px]"></i>
                            @yield('breadcrumb')
                        @endif
                    </div>
                    <h1 class="font-display text-xl leading-tight text-[var(--color-ink-2)] font-semibold">@yield('page_title', 'Admin')</h1>
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

@stack('scripts')
</body>
</html>
