@php
    $compact = $compact ?? false;
    $chapterNum = $chapterNum ?? '02';
    $chapterLabel = $chapterLabel ?? 'Newsletter Subscribe';
    $headingHtml = $headingHtml ?? 'Stay connected with the <em>Alliance</em>.';
    $ledeHtml = $ledeHtml ?? 'Get stories, events, and resources from across Chhattisgarh — delivered to your inbox.';
@endphp
<section class="cta {{ $compact ? 'newsletter-subscribe--compact' : 'home-newsletter' }}" id="newsletter" aria-labelledby="newsletter-h">
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
        <p class="newsletter-note">Updates from the alliance — stories, events and resources. No spam, unsubscribe anytime.</p>
      </div>
    </div>
  </div>
</section>
