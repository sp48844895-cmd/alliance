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
        <x-section.heading
            :chapter-num="$s['chapter_num'] ?? '03'"
            :chapter-label="$s['chapter_label'] ?? 'Programs & Initiatives'"
            :heading-html="$s['heading_html'] ?? 'Programs &amp; Initiatives'"
            :side-text="$sideText"
            heading-id="programs-h"
            wrapper-class="programs-head"
        />

        <div class="program-flip-grid">
            @foreach ($cards as $index => $card)
                <x-section.program-flip-card
                    :title="$card['title']"
                    :description="$card['description']"
                    :image-url="$card['image_url']"
                    :accent="$card['accent']"
                    :delay="$index * 80"
                />
            @endforeach
        </div>

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
