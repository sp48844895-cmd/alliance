@php
    $join = $pageSections['join'] ?? [];
    $joinHead = $join['head'] ?? [];
    $joinOptions = $join['options'] ?? [
        [
            'slug' => 'volunteer',
            'prefix' => 'Engage with us as',
            'title' => 'an Individual Volunteer',
            'description' => 'Support field outreach, local events and community learning across districts.',
            'cta_label' => 'Register',
            'cta_url' => route('register.volunteer'),
            'pathway' => 'volunteer',
            'icon' => 'volunteer',
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
                    $slug = $option['slug'] ?? 'pathway';
                    $pathway = $option['pathway'] ?? $slug;
                    $ctaUrl = $option['cta_url'] ?? match ($slug) {
                        'volunteer' => route('register.volunteer'),
                        'intern' => route('register.intern'),
                        'fellow' => route('register.fellow'),
                        default => route('contact', ['pathway' => $pathway]),
                    };
                    $tone = $option['tone'] ?? 'ochre';
                @endphp
                <article class="gi-join-card" id="gi-{{ $slug }}" data-tone="{{ $tone }}" data-aos="fade-up">
                    <div class="gi-join-card__icon" aria-hidden="true">
                        @include('pages.partials.get-involved.icons.' . ($option['icon'] ?? $slug))
                    </div>
                    <p class="gi-join-card__prefix">{{ $option['prefix'] ?? 'Engage with us as' }}</p>
                    <h3>{{ $option['title'] ?? '' }}</h3>
                    <p class="gi-join-card__desc">{{ $option['description'] ?? ($option['detail'] ?? '') }}</p>
                    <a class="btn btn-primary gi-join-card__cta" href="{{ $ctaUrl }}">{{ $option['cta_label'] ?? 'Get started' }}</a>
                </article>
            @endforeach
        </div>
    </div>
</section>
