@props(['insights'])

@if ($insights && count($insights))
    <div class="insights-sub-head" data-aos="fade-up">
        <span class="chapter"><b>↳</b> Insights</span>
        <h2 id="insights-h">Data and impact that drive <em>change</em>.</h2>
        <p>Key highlights, numbers, and updates from Alliance for Behaviour Change work across Chhattisgarh.</p>
    </div>

    <div class="insights-grid">
        @foreach ($insights as $index => $insight)
            @php $delay = $index * 80; @endphp
            <article class="insight-card" data-aos="fade-up" @if ($delay) data-aos-delay="{{ $delay }}" @endif>
                @if (!empty($insight->image_url))
                    <div class="insight-card-img">
                        <img src="{{ $insight->image_url }}" alt="{{ $insight->title }}" loading="lazy">
                    </div>
                @endif
                <div class="insight-card-body">
                    @if ($insight->tag)
                        <span class="program-tag">{{ $insight->tag }}</span>
                    @endif
                    <h3>{{ $insight->title }}</h3>
                    <p>{{ $insight->description }}</p>
                    @if ($insight->link_text && $insight->link_url)
                        <a href="{{ $insight->link_url }}" class="insight-link">
                            {{ $insight->link_text }}
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
@endif
