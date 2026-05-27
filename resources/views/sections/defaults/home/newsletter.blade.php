@php
    $chapterNum = $s['chapter_num'] ?? '08';
    $chapterLabel = $s['chapter_label'] ?? 'Newsletter Subscribe';
    $headingHtml = $s['heading_html'] ?? 'Stay connected with the <em>Alliance</em>.';
    $ledeHtml = $s['lede_html'] ?? 'Get stories, events, and resources from across Chhattisgarh — field updates, campaign highlights, and learning materials delivered to your inbox.';
@endphp
<div class="container-x">
    <div class="newsletter-section-grid">
        <div data-aos="fade-up">
            <span class="chapter newsletter-chapter"><b style="color:var(--ochre);">{{ $chapterNum }}</b> · {{ $chapterLabel }}</span>
            <h2 id="newsletter-h">{!! $headingHtml !!}</h2>
            <p class="cta-lede">{!! $ledeHtml !!}</p>
        </div>

        <div class="newsletter-panel" data-aos="fade-up" data-aos-delay="120">
            @if (session('status'))
                <p class="newsletter-alert newsletter-alert--success" role="status">{{ session('status') }}</p>
            @endif
            @error('email')
                <p class="newsletter-alert newsletter-alert--error" role="alert">{{ $message }}</p>
            @enderror

            <form class="newsletter newsletter--home" action="{{ route('newsletter.subscribe') }}" method="POST" aria-label="Subscribe to ChhattisgarhABC newsletter">
                @csrf
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    placeholder="your@email · monthly digest"
                    aria-label="Email address"
                    autocomplete="email"
                />
                <button type="submit">Subscribe</button>
            </form>
            <p class="newsletter-note">Updates from the alliance — stories, events and resources, straight from the field. No spam, unsubscribe anytime.</p>
        </div>
    </div>
</div>
