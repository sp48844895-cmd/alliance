@extends('layouts.auth')

@section('title', 'Log in')

@section('content')
<div class="min-h-screen bg-[var(--color-paper)]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-10 lg:py-14">
        <div class="flex items-center gap-3 mb-10">
            <div class="w-10 h-10 rounded-lg bg-[var(--color-clay-500)] flex items-center justify-center">
                <span class="font-display text-white text-xl leading-none">A</span>
            </div>
            <div>
                <div class="font-display text-lg text-[var(--color-ink-2)] font-medium">Alliance for Behavior Change</div>
                <div class="text-[10px] uppercase tracking-[0.2em] text-[var(--color-mute-2)]">Chhattisgarh · Portal logins</div>
            </div>
        </div>

        <div class="mb-10 max-w-2xl">
            <p class="text-[10px] uppercase tracking-[0.25em] text-[var(--color-clay-500)] font-semibold mb-2">Sign in</p>
            <h1 class="font-display text-3xl sm:text-4xl text-[var(--color-ink-2)] mb-3">Choose your portal</h1>
            <p class="text-[var(--color-mute)] text-base leading-relaxed">Select the role that matches your account. Each portal uses a separate login and email.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($portals as $portal)
                <a href="{{ route('login.show', $portal['slug']) }}"
                   class="group card p-5 flex flex-col gap-3 hover:ring-2 hover:ring-[var(--color-clay-200)] transition">
                    <div class="flex items-start justify-between gap-3">
                        <span class="w-11 h-11 rounded-lg bg-[var(--color-paper-2)] border border-[var(--color-line)] flex items-center justify-center text-[var(--color-clay-600)] group-hover:bg-[var(--color-clay-50)]">
                            <i class="bi {{ $portal['icon'] }} text-lg"></i>
                        </span>
                        <i class="bi bi-arrow-right text-[var(--color-mute-2)] group-hover:text-[var(--color-clay-600)] transition"></i>
                    </div>
                    <div>
                        <h2 class="font-display text-xl text-[var(--color-ink-2)]">{{ $portal['label'] }}</h2>
                        <p class="text-[10px] uppercase tracking-wider text-[var(--color-mute-2)] mt-0.5">{{ $portal['chapter'] }}</p>
                    </div>
                    <p class="text-sm text-[var(--color-mute)] leading-relaxed flex-1">{{ \Illuminate\Support\Str::limit($portal['lede'], 120) }}</p>
                    <span class="text-xs font-semibold text-[var(--color-clay-600)]">Sign in as {{ $portal['short'] }} →</span>
                </a>
            @endforeach
        </div>

        <p class="text-xs text-[var(--color-mute-2)] mt-10 text-center">
            <a href="{{ route('home') }}" class="hover:text-[var(--color-ink-2)]">← Back to public site</a>
        </p>
    </div>
</div>
@endsection
