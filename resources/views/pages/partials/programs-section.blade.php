<section class="programs-section" id="programs-initiatives" aria-labelledby="programs-h">

@if ($programsUseSlider)
    <div class="container-x">
        <div class="programs-head programs-head--center-slider">
            <div>
                <span class="chapter"><b>{{ $programsHeader['chapter_num'] }}</b> · {{ $programsHeader['chapter_label'] }}</span>
                <h2 id="programs-h">{!! $programsHeader['heading_html'] !!}</h2>
            </div>
            <div class="programs-center-controls">
                <button id="programs-center-prev" class="programs-center-nav-btn" type="button" aria-label="Previous program">‹</button>
                <button id="programs-center-next" class="programs-center-nav-btn" type="button" aria-label="Next program">›</button>
            </div>
        </div>

        <p class="programs-center-lede">{{ $programsHeader['lede_text'] }}</p>

        <div class="programs-center-slider">
            <div class="programs-center-track" id="programs-center-track">
                @foreach ($programsCards as $card)
                    <article class="programs-center-card" @if ($card['is_active']) active @endif data-program-card>
                        @if ($card['image_url'] !== '')
                            <img class="programs-center-card__bg" src="{{ $card['image_url'] }}" alt="{{ $card['title'] }}" loading="lazy" decoding="async">
                        @endif
                        <div class="programs-center-card__content">
                            @if ($card['image_url'] !== '')
                                <img class="programs-center-card__thumb" src="{{ $card['image_url'] }}" alt="{{ $card['title'] }}" loading="lazy" decoding="async">
                            @endif
                            <div class="programs-center-card__copy">
                                <h3 class="programs-center-card__title">{{ $card['title'] }}</h3>
                                <p class="programs-center-card__desc">{{ $card['description'] }}</p>
                                <a href="{{ $programsDetailsUrl }}" class="programs-center-card__btn">Details</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="programs-center-dots" id="programs-center-dots"></div>

        <div class="programs-explore" data-aos="fade-up">
            <a href="{{ $programsHeader['explore_url'] }}" class="btn btn-primary programs-explore__btn">
                {{ $programsHeader['explore_label'] }}
                <span class="arrow" aria-hidden="true">→</span>
            </a>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/js/programs-center-slider.js') }}?v={{ filemtime(public_path('assets/js/programs-center-slider.js')) }}"></script>
    @endpush

@else
    <div class="container-x">
        <div class="programs-head">
            <div>
                <span class="chapter"><b>{{ $programsHeader['chapter_num'] }}</b> · {{ $programsHeader['chapter_label'] }}</span>
                <h2 id="programs-h" data-aos="fade-up">{!! $programsHeader['heading_html'] !!}</h2>
            </div>
            <p data-aos="fade-up" data-aos-delay="120">{{ $programsHeader['lede_text'] }}</p>
        </div>

        <div class="program-flip-grid">
            @foreach ($programsCards as $card)
                <article
                    class="program-flip-card program-flip-card--{{ $card['accent'] }}"
                    data-aos="fade-up"
                    @if ($card['delay']) data-aos-delay="{{ $card['delay'] }}" @endif
                >
                    <div class="program-flip-card__inner" tabindex="0" aria-label="{{ $card['title'] }} — flip for details">
                        <div class="program-flip-card__face program-flip-card__front">
                            @if ($card['image_url'] !== '')
                                <img class="program-flip-card__photo" src="{{ $card['image_url'] }}" alt="" loading="lazy" decoding="async">
                            @else
                                <div class="program-flip-card__photo program-flip-card__photo--placeholder" aria-hidden="true"></div>
                            @endif
                            <div class="program-flip-card__front-overlay">
                                <h3 class="program-flip-card__title">{{ $card['title'] }}</h3>
                            </div>
                        </div>
                        <div class="program-flip-card__face program-flip-card__back">
                            <div class="program-flip-card__back-inner">
                                <h3 class="program-flip-card__back-title">{{ $card['title'] }}</h3>
                                <p class="program-flip-card__description">{{ $card['description'] }}</p>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="programs-explore" data-aos="fade-up">
            <a href="{{ $programsHeader['explore_url'] }}" class="btn btn-primary programs-explore__btn">
                {{ $programsHeader['explore_label'] }}
                <span class="arrow" aria-hidden="true">→</span>
            </a>
        </div>
    </div>
@endif

</section>
