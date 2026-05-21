@props([
    'section' => [],
    'programs' => null,
    'insights' => null,
])

@php
    $s = $section;
    $hasPrograms = $programs && $programs->count() > 0;
    $cmsCards = $s['cards'] ?? [];
@endphp

@if ($hasPrograms)
    <div class="container-x">
        <x-section.heading
            :chapter-num="$s['chapter_num'] ?? '03'"
            :chapter-label="$s['chapter_label'] ?? 'Programs & Insights'"
            :heading-html="$s['heading_html'] ?? 'Community-led work that turns <em>trust</em> into action.'"
            :side-text="$s['lede_html'] ?? 'A focused view of flagship SBC initiatives across Chhattisgarh, from youth volunteer networks to local learning resources and community-led health campaigns.'"
            heading-id="programs-h"
            wrapper-class="programs-head"
        />

        <div class="programs-grid">
            @foreach ($programs as $index => $program)
                @php
                    $styleClass = match ($program->card_style) {
                        'featured' => 'program-card--featured',
                        'teal' => 'program-card--teal',
                        'ochre' => 'program-card--ochre',
                        'leaf' => 'program-card--leaf',
                        default => '',
                    };
                    $delay = $index * 80;
                @endphp
                <article class="program-card {{ $styleClass }}" data-aos="fade-up" @if ($delay) data-aos-delay="{{ $delay }}" @endif>
                    @if ($program->card_style === 'featured')
                        <div class="program-card-main">
                            @if ($program->tag)
                                <span class="program-tag">{{ $program->tag }}</span>
                            @endif
                            <h3>{{ $program->title }}</h3>
                            <p>{{ $program->short_desc }}</p>
                        </div>
                        @if ($program->full_desc)
                            <div class="program-card-copy">
                                @foreach (array_filter(explode("\n", $program->full_desc)) as $para)
                                    <p>{{ trim($para) }}</p>
                                @endforeach
                            </div>
                        @endif
                    @else
                        @if ($program->tag)
                            <span class="program-tag">{{ $program->tag }}</span>
                        @endif
                        <h3>{{ $program->title }}</h3>
                        <p>{{ $program->short_desc }}</p>
                        @if ($program->full_desc)
                            @foreach (array_filter(explode("\n", $program->full_desc)) as $para)
                                <p>{{ trim($para) }}</p>
                            @endforeach
                        @endif
                    @endif
                </article>
            @endforeach
        </div>

        <x-section.insights-grid :insights="$insights" />
    </div>
@elseif (!empty($cmsCards))
    <div class="container-x">
        <x-section.heading
            :chapter-num="$s['chapter_num'] ?? '03'"
            :chapter-label="$s['chapter_label'] ?? 'Programs & Initiatives'"
            :heading-html="$s['heading_html'] ?? 'Community-led work that turns <em>trust</em> into action.'"
            :side-text="$s['lede_html'] ?? ''"
            heading-id="programs-h"
            wrapper-class="programs-head"
        />

        <div class="programs-grid">
            @foreach ($cmsCards as $card)
                @php
                    $modifier = $card['modifier'] ?? ($card['featured'] ?? false ? 'program-card--featured' : '');
                    if ($modifier === '' && !empty($card['featured'])) {
                        $modifier = 'program-card--featured';
                    }
                @endphp
                <article class="program-card {{ $modifier }}" data-aos="fade-up"@if (!empty($card['aos_delay'])) data-aos-delay="{{ $card['aos_delay'] }}"@endif>
                    @if (!empty($card['featured']))
                        <div class="program-card-main">
                            @if (!empty($card['tag']))
                                <span class="program-tag">{{ $card['tag'] }}</span>
                            @endif
                            <h3>{{ $card['title'] ?? '' }}</h3>
                            <p>{{ $card['lede'] ?? '' }}</p>
                        </div>
                        @if (!empty($card['paragraphs']))
                            <div class="program-card-copy">
                                @foreach ($card['paragraphs'] as $para)
                                    <p>{{ $para }}</p>
                                @endforeach
                            </div>
                        @endif
                    @else
                        @if (!empty($card['tag']))
                            <span class="program-tag">{{ $card['tag'] }}</span>
                        @endif
                        <h3>{{ $card['title'] ?? '' }}</h3>
                        @if (!empty($card['lede']))
                            <p>{{ $card['lede'] }}</p>
                        @endif
                        @foreach ($card['paragraphs'] ?? [] as $para)
                            <p>{{ $para }}</p>
                        @endforeach
                    @endif
                </article>
            @endforeach
        </div>
    </div>
@else
    @include('sections.defaults.home.programs')
@endif
