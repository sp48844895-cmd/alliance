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
                        <li><a href="#{{ $pathway['anchor'] ?? 'gi-join' }}"><b>{{ $pathway['num'] }}</b><span>{{ $pathway['label'] }}</span><i>{{ $pathway['hint'] }}</i></a></li>
                    @endforeach
                </ol>
            </aside>
        </div>
    </div>
</section>
