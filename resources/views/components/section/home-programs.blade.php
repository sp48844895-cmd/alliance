@props([
    'section' => [],
    'programs' => null,
])

@php
    $s = $section;
    $accents = ['grad', 'orange', 'black'];
    $legacyBase = rtrim((string) config('media.legacy_base', ''), '/');
    $placeholderImages = [
        $legacyBase.'/images/home/1.jpg',
        $legacyBase.'/images/home/2.jpg',
        $legacyBase.'/images/home/3.jpg',
        $legacyBase.'/images/home/4.jpg',
        $legacyBase.'/images/home/5.jpg',
    ];

    $cards = [];

    if ($programs && $programs->count() > 0) {
        foreach ($programs as $index => $program) {
            $description = trim((string) ($program->short_desc ?? ''));
            $fullDesc = trim((string) ($program->full_desc ?? ''));
            if ($fullDesc !== '' && $fullDesc !== $description) {
                $description = $description !== '' ? $description.' '.$fullDesc : $fullDesc;
            }

            $imageUrl = trim((string) ($program->image_url ?? ''));
            if ($imageUrl === '' && ! empty($program->image)) {
                $imageUrl = \App\Support\MediaUrl::tryResolve('program', (string) $program->image) ?? '';
            }
            if ($imageUrl === '') {
                $imageUrl = $placeholderImages[$index % count($placeholderImages)] ?? '';
            }

            $cards[] = [
                'title' => $program->title ?? '',
                'description' => $description,
                'image_url' => $imageUrl,
                'accent' => $accents[$index % 3],
            ];
        }
    } elseif (! empty($s['cards'])) {
        foreach ($s['cards'] as $index => $card) {
            $description = trim((string) ($card['lede'] ?? ''));
            $paragraphs = $card['paragraphs'] ?? [];
            if (! empty($paragraphs)) {
                $extra = trim(implode(' ', array_map('trim', $paragraphs)));
                $description = $description !== '' ? $description.' '.$extra : $extra;
            }

            $cards[] = [
                'title' => $card['title'] ?? '',
                'description' => $description,
                'image_url' => $card['image_url'] ?? ($placeholderImages[$index % count($placeholderImages)] ?? ''),
                'accent' => $accents[$index % 3],
            ];
        }
    }

    $exploreUrl = $s['explore_url'] ?? route('programs');
    $exploreLabel = $s['explore_label'] ?? 'Explore More';
    $sideText = $s['lede_html'] ?? 'A focused view of flagship SBC initiatives across Chhattisgarh — from youth volunteer networks to local learning resources and community led campaigns.';
@endphp

@if (! empty($cards))
    <div class="container-x">
        <div class="programs-head programs-head--center-slider">
            <div>
                <span class="chapter"><b>{{ $s['chapter_num'] ?? '03' }}</b> · {{ $s['chapter_label'] ?? 'Programs & Initiatives' }}</span>
                <h2 id="programs-h">{!! $s['heading_html'] ?? 'Programs &amp; Initiatives' !!}</h2>
            </div>
            <div class="programs-center-controls">
                <button id="programs-center-prev" class="programs-center-nav-btn" type="button" aria-label="Previous program">‹</button>
                <button id="programs-center-next" class="programs-center-nav-btn" type="button" aria-label="Next program">›</button>
            </div>
        </div>

        <p class="programs-center-lede">{{ strip_tags($sideText) }}</p>

        <div class="programs-center-slider">
            <div class="programs-center-track" id="programs-center-track">
                @foreach ($cards as $index => $card)
                    <article class="programs-center-card" @if ($index === 0) active @endif data-program-card>
                        @if (! empty($card['image_url']))
                            <img class="programs-center-card__bg" src="{{ $card['image_url'] }}" alt="{{ $card['title'] }}" loading="lazy" decoding="async">
                        @endif
                        <div class="programs-center-card__content">
                            @if (! empty($card['image_url']))
                                <img class="programs-center-card__thumb" src="{{ $card['image_url'] }}" alt="{{ $card['title'] }}" loading="lazy" decoding="async">
                            @endif
                            <div class="programs-center-card__copy">
                                <h3 class="programs-center-card__title">{{ $card['title'] }}</h3>
                                <p class="programs-center-card__desc">{{ $card['description'] }}</p>
                                <a href="{{ route('programs') }}" class="programs-center-card__btn">Details</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="programs-center-dots" id="programs-center-dots"></div>

        <div class="programs-explore" data-aos="fade-up">
            <a href="{{ $exploreUrl }}" class="btn btn-primary programs-explore__btn">
                {{ $exploreLabel }}
                <span class="arrow" aria-hidden="true">→</span>
            </a>
        </div>
    </div>
@else
    @include('sections.defaults.home.programs')
@endif

@once
    @push('scripts')
        <script src="{{ asset('assets/js/programs-center-slider.js') }}?v={{ filemtime(public_path('assets/js/programs-center-slider.js')) }}"></script>
    @endpush
@endonce
