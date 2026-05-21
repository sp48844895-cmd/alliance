@php
  $layout = $layout ?? 'announce';
  $settings = $settings ?? [];
@endphp

@if ($layout === 'footer')
<div class="social-icons social-icons--footer" role="group" aria-label="Social media">
@endif

@if (!empty($settings['social_facebook'] ?? 'https://www.facebook.com/ChhattisgarhABC/'))
<a href="{{ $settings['social_facebook'] ?? 'https://www.facebook.com/ChhattisgarhABC/' }}" class="social-icons__link" target="_blank" rel="noopener" aria-label="Facebook">
  <svg class="social-icons__svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
</a>
@endif
@if (!empty($settings['social_instagram'] ?? 'https://www.instagram.com/chhattisgarhabc/'))
<a href="{{ $settings['social_instagram'] ?? 'https://www.instagram.com/chhattisgarhabc/' }}" class="social-icons__link" target="_blank" rel="noopener" aria-label="Instagram">
  <svg class="social-icons__svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke="currentColor" stroke-width="1.75"/>
    <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.75"/>
    <circle cx="17.5" cy="6.5" r="1.25" fill="currentColor"/>
  </svg>
</a>
@endif
@if (!empty($settings['social_twitter'] ?? 'https://twitter.com/chhattisgarhabc'))
<a href="{{ $settings['social_twitter'] ?? 'https://twitter.com/chhattisgarhabc' }}" class="social-icons__link" target="_blank" rel="noopener" aria-label="X (Twitter)">
  <svg class="social-icons__svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M4 4l7.5 9.5L4 20h2.5l6-6.8 4.9 6.8H20l-8-10.2L18.7 4h-2.5l-5.5 6.3L6.8 4H4z" fill="currentColor"/>
  </svg>
</a>
@endif
@if (!empty($settings['social_youtube'] ?? 'https://www.youtube.com/@chhattisgarhabc'))
<a href="{{ $settings['social_youtube'] ?? 'https://www.youtube.com/@chhattisgarhabc' }}" class="social-icons__link" target="_blank" rel="noopener" aria-label="YouTube">
  <svg class="social-icons__svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2 29 29 0 0 0-.46 5.33 29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.47 8.6.47 8.6.47s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.33 29 29 0 0 0-.46-5.33z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    <path d="M10 9l6 3.5-6 3.5V9z" fill="currentColor"/>
  </svg>
</a>
@endif

@if ($layout === 'footer')
</div>
@endif
