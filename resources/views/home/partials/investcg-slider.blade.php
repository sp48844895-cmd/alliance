@php
    $slideCount = count($homeSliderSlides);
    $rotationStep = $slideCount > 0 ? 360 / $slideCount : 0;
    $firstSlide = $homeSliderSlides[0] ?? null;
@endphp

<section class="card-slider-section" id="investcg-card-slider" aria-labelledby="investcg-slider-h">
    <div class="container-x">
        <div class="card-slider-content">
            <div class="card-slider-container">
                <div class="card-slider-drag-handle" aria-hidden="true"></div>
                <div class="card-slider-circle">
                    @foreach ($homeSliderSlides as $index => $slide)
                        <div class="card-slider-card"
                            data-title="{{ $slide['title'] }}"
                            data-description="{{ $slide['short_description'] }}"
                            data-url="{{ $slide['url'] ?? '' }}"
                            style="transform: rotate({{ $index * $rotationStep }}deg); opacity: 1">
                            <div class="card-content">
                                @if (! empty($slide['url']))
                                    <a href="{{ $slide['url'] }}" class="card-slider-link"
                                        @if (str_starts_with($slide['url'], 'http')) target="_blank" rel="noopener noreferrer" @endif
                                        aria-label="{{ $slide['title'] }}">
                                        <img src="{{ $slide['image_url'] }}" alt="{{ $slide['title'] }}"
                                            class="card-image" loading="lazy" decoding="async">
                                    </a>
                                @else
                                    <img src="{{ $slide['image_url'] }}" alt="{{ $slide['title'] }}"
                                        class="card-image" loading="lazy" decoding="async">
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <footer class="card-slider-footer">
                    <div class="active-card-content">
                        <h3 class="active-card-title" id="investcg-slider-h">{{ $firstSlide['title'] ?? '' }}</h3>
                        <p class="active-card-description">{{ $firstSlide['short_description'] ?? '' }}</p>
                    </div>
                    <nav class="card-slider-navigation" aria-label="Card slider navigation">
                        <button class="nav-btn nav-prev" type="button" aria-label="Previous slide">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" aria-hidden="true">
                                <path d="M14 17L9 12L14 7"></path>
                            </svg>
                        </button>
                        <button class="nav-btn nav-next" type="button" aria-label="Next slide">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" aria-hidden="true">
                                <path d="M10 7L15 12L10 17"></path>
                            </svg>
                        </button>
                    </nav>
                </footer>
            </div>
        </div>
    </div>
</section>
