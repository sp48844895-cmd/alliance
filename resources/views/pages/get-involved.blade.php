@extends('layouts.app')

@section('title', $pageContent['meta_title'] ?? 'Get Involved · ChhattisgarhABC')
@section('meta_description', $pageContent['meta_description'] ?? 'Four pathways to join the alliance — volunteer, intern, fellowship or organisation partner. Pick yours and register to support social and behavioural change communication.')

@section('content')
<main id="main">

{{-- Section: hero --}}
@php $hero = $pageSections['hero'] ?? []; @endphp
<section class="gi-hero" aria-labelledby="gi-hero-h">
    <div class="container-x">
        <div class="gi-hero-grid">
            <div class="gi-hero-text">
                <span class="chapter fade-up" data-delay="1"><b>{{ $hero['chapter'] ?? '07' }}</b> · Join</span>

                <h2 id="gi-hero-h" class="gi-hero-title type-hero fade-up" data-delay="2">
                    {!! $hero['title'] ?? '' !!}
                </h2>

                <p class="gi-hero-lede type-lede fade-up" data-delay="3">
                    {!! $hero['lede'] ?? '' !!}
                </p>

                <div class="gi-hero-cta fade-up" data-delay="4">
                    <a class="btn btn-primary" href="{{ $hero['cta_primary']['href'] ?? '#gi-join' }}">
                        {{ $hero['cta_primary']['label'] ?? 'Find your fit' }}
                        <svg class="arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                    </a>
                    <a class="btn btn-ghost" href="{{ !empty($hero['cta_secondary']['route']) ? route($hero['cta_secondary']['href']) : ($hero['cta_secondary']['href'] ?? route('contact')) }}">{{ $hero['cta_secondary']['label'] ?? 'Contact us' }}</a>
                </div>
            </div>

            <aside class="gi-hero-paths fade-up" data-delay="3" aria-label="Join pathways">
                <span class="gi-hero-paths-eyebrow">Join pathways</span>
                <ol class="gi-hero-paths-list">
                    @foreach ($hero['pathways'] ?? [] as $pathway)
                        @php
                            $pathwayLabel = match (strtolower(trim($pathway['label'] ?? ''))) {
                                'volunteer', 'individual volunteer' => 'Guest',
                                default => $pathway['label'] ?? '',
                            };
                        @endphp
                        <li><a href="#{{ $pathway['anchor'] ?? 'gi-join' }}"><b>{{ $pathway['num'] }}</b><span>{{ $pathwayLabel }}</span><i>{{ $pathway['hint'] }}</i></a></li>
                    @endforeach
                </ol>
            </aside>
        </div>
    </div>
</section>

{{-- Section: join --}}
@php
    $join = $pageSections['join'] ?? [];
    $joinHead = $join['head'] ?? [];
    $joinOptions = $join['options'] ?? [
        [
            'slug' => 'guest',
            'prefix' => 'Engage with us as',
            'title' => 'a Guest',
            'description' => 'Support field outreach, local events and community learning across districts.',
            'cta_label' => 'Register',
            'cta_url' => route('register.guest'),
            'pathway' => 'guest',
            'icon' => 'guest',
            'tone' => 'ochre',
        ],
        [
            'slug' => 'intern',
            'prefix' => 'Engage with us as',
            'title' => 'an Intern',
            'description' => 'Learn on the job through communications, research and programme support roles.',
            'cta_label' => 'Apply',
            'cta_url' => route('register.intern'),
            'icon' => 'intern',
            'tone' => 'leaf',
        ],
        [
            'slug' => 'fellow',
            'prefix' => 'Engage with us as',
            'title' => 'a Fellow (Fellowship)',
            'description' => 'Take on a structured fellowship to deepen SBC practice and district-level impact.',
            'cta_label' => 'Apply',
            'cta_url' => route('register.fellow'),
            'icon' => 'fellow',
            'tone' => 'terracotta',
        ],
        [
            'slug' => 'partner',
            'prefix' => 'Partner with us as',
            'title' => 'a CSO / NGO / Firm / Organization',
            'description' => 'Co-design campaigns, share resources and scale behaviour change with the alliance.',
            'cta_label' => 'Partner with us',
            'pathway' => 'partner',
            'icon' => 'partner',
            'tone' => 'indigo',
        ],
    ];
@endphp

<section class="gi-join" id="gi-join" aria-labelledby="gi-join-h">
    <div class="container-x">
        <div class="gi-join-head" data-aos="fade-up">
            <span class="chapter"><b>{{ $joinHead['chapter'] ?? '08' }}</b> · {{ $joinHead['chapter_label'] ?? 'Get Involved' }}</span>
            <h2 id="gi-join-h">{!! $joinHead['title'] ?? 'Choose your <em>pathway.</em>' !!}</h2>
            @if (!empty($joinHead['lede']))
                <p>{{ $joinHead['lede'] }}</p>
            @endif
        </div>

        <div class="gi-join-grid">
            @foreach ($joinOptions as $option)
                @php
                    $slug = match ($option['slug'] ?? 'pathway') {
                        'volunteer' => 'guest',
                        default => $option['slug'] ?? 'pathway',
                    };
                    $pathway = match ($option['pathway'] ?? $slug) {
                        'volunteer' => 'guest',
                        default => $option['pathway'] ?? $slug,
                    };
                    $icon = match ($option['icon'] ?? $slug) {
                        'volunteer' => 'guest',
                        default => $option['icon'] ?? $slug,
                    };
                    $ctaUrl = $option['cta_url'] ?? match ($slug) {
                        'guest' => route('register.guest'),
                        'intern' => route('register.intern'),
                        'fellow' => route('register.fellow'),
                        default => route('contact', ['pathway' => $pathway]),
                    };
                    $tone = $option['tone'] ?? 'ochre';
                    $title = match (true) {
                        in_array(strtolower(trim($option['title'] ?? '')), ['an individual volunteer', 'individual volunteer', 'a volunteer', 'volunteer'], true) => 'a Guest',
                        ($slug === 'guest' || $pathway === 'guest') && ($option['title'] ?? '') === '' => 'a Guest',
                        default => $option['title'] ?? '',
                    };
                @endphp
                <article class="gi-join-card" id="gi-{{ $slug }}" data-tone="{{ $tone }}" data-aos="fade-up">
                    <div class="gi-join-card__icon" aria-hidden="true">
                        @switch($icon)
                            @case('guest')
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                @break
                            @case('intern')
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-6 10 6-10 6z"/><path d="M6 12v5c0 1 2 3 6 3s6-2 6-3v-5"/></svg>
                                @break
                            @case('fellow')
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M6 20v-1a6 6 0 0 1 12 0v1"/><path d="M19 8l2 2-2 2"/><path d="M5 8 3 10l2 2"/></svg>
                                @break
                            @case('partner')
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-6h6v6"/><path d="M9 11h6"/></svg>
                                @break
                        @endswitch
                    </div>
                    <p class="gi-join-card__prefix">{{ $option['prefix'] ?? 'Engage with us as' }}</p>
                    <h3>{{ $title }}</h3>
                    <p class="gi-join-card__desc">{{ $option['description'] ?? ($option['detail'] ?? '') }}</p>
                    <a class="btn btn-primary gi-join-card__cta" href="{{ $ctaUrl }}">{{ $option['cta_label'] ?? 'Get started' }}</a>
                </article>
            @endforeach
        </div>
    </div>
</section>

</main>
@endsection
