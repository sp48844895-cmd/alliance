<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', 'Fellow') · ABC Chhattisgarh</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="grain min-h-screen">

@php
    $sections = [
        ['heading' => 'Overview', 'links' => [
            ['route' => 'fellow.dashboard', 'icon' => 'bi-grid-1x2', 'label' => 'Dashboard', 'wildcard' => 'fellow.dashboard'],
        ]],
    ];
@endphp

<div class="flex min-h-screen">

    <div data-admin-overlay class="fixed inset-0 bg-black/40 z-30 lg:hidden hidden [&.is-visible]:block"></div>

    <aside data-admin-sidebar class="fixed lg:sticky top-0 z-40 h-screen w-[280px] -translate-x-full lg:translate-x-0 [&.is-open]:translate-x-0 transition-transform duration-300 bg-[var(--color-paper)] border-r border-[var(--color-line)] flex flex-col">

        <div class="px-5 py-5 border-b border-[var(--color-line)]">
            <div class="font-display text-lg text-[var(--color-ink-2)] font-medium">Fellowship portal</div>
            <div class="text-xs text-[var(--color-mute)] mt-1">ChhattisgarhABC</div>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6">
            @foreach($sections as $section)
                <div>
                    <div class="px-2 mb-2 text-[10px] uppercase tracking-[0.2em] font-bold text-[var(--color-mute-2)]">{{ $section['heading'] }}</div>
                    <ul class="space-y-0.5">
                        @foreach($section['links'] as $link)
                            @php
                                $active = request()->routeIs($link['route']) || (isset($link['wildcard']) && request()->routeIs($link['wildcard']));
                            @endphp
                            <li>
                                <a href="{{ route($link['route']) }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ $active ? 'bg-[var(--color-clay-50)] text-[var(--color-clay-700)]' : 'text-[var(--color-mute)] hover:bg-[var(--color-paper-2)] hover:text-[var(--color-ink-2)]' }}">
                                    <i class="bi {{ $link['icon'] }} text-base"></i>
                                    <span>{{ $link['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </nav>

        <div class="p-4 border-t border-[var(--color-line)] text-xs text-[var(--color-mute)]">
            Signed in as <span class="font-semibold text-[var(--color-ink-2)]">{{ auth()->user()->fname ?? 'Fellow' }}</span>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0">
        <header class="sticky top-0 z-20 bg-[var(--color-paper)]/90 backdrop-blur border-b border-[var(--color-line)] px-4 lg:px-8 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 min-w-0">
                <button type="button" data-admin-menu-toggle class="lg:hidden btn btn-ghost btn-sm" aria-label="Open menu">
                    <i class="bi bi-list text-lg"></i>
                </button>
                <div class="min-w-0">
                    <div class="text-xs text-[var(--color-mute)] hidden sm:block">@yield('breadcrumb')</div>
                    <h1 class="font-display text-xl text-[var(--color-ink-2)] truncate">@yield('page_title', 'Dashboard')</h1>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                @yield('topbar_actions')
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-ghost btn-sm">
                    <i class="bi bi-box-arrow-up-right"></i>
                    <span class="hidden sm:inline">Public site</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-sm">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 p-4 lg:p-8">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
