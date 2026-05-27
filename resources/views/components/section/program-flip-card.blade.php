@props([
    'title' => '',
    'description' => '',
    'imageUrl' => '',
    'accent' => 'grad',
    'delay' => 0,
])

@php
    $accents = ['grad', 'orange', 'black'];
    $accentClass = in_array($accent, $accents, true) ? $accent : 'grad';
@endphp

<article
    class="program-flip-card program-flip-card--{{ $accentClass }}"
    data-aos="fade-up"
    @if ($delay) data-aos-delay="{{ $delay }}" @endif
>
    <div class="program-flip-card__inner" tabindex="0" aria-label="{{ $title }} — flip for details">
        <div class="program-flip-card__face program-flip-card__front">
            @if ($imageUrl !== '')
                <img class="program-flip-card__photo" src="{{ $imageUrl }}" alt="" loading="lazy" decoding="async">
            @else
                <div class="program-flip-card__photo program-flip-card__photo--placeholder" aria-hidden="true"></div>
            @endif
            <div class="program-flip-card__front-overlay">
                <h3 class="program-flip-card__title">{{ $title }}</h3>
            </div>
        </div>
        <div class="program-flip-card__face program-flip-card__back">
            <div class="program-flip-card__back-inner">
                <h3 class="program-flip-card__back-title">{{ $title }}</h3>
                <p class="program-flip-card__description">{{ $description }}</p>
            </div>
        </div>
    </div>
</article>
