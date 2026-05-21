@if (!empty($social))
  <div class="{{ $wrapperClass ?? 'member-card__social' }} social-icons" role="group" aria-label="Social links for {{ $name }}">
    @foreach ($social as $link)
      <a href="{{ $link['url'] }}" class="social-icons__link" @if (($link['url'] ?? '') !== '#') target="_blank" rel="noopener noreferrer" @endif aria-label="{{ ucfirst($link['platform']) }}">
        @if ($link['platform'] === 'facebook')
          <svg class="social-icons__svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        @elseif ($link['platform'] === 'instagram')
          <svg class="social-icons__svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke="currentColor" stroke-width="1.75"/>
            <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.75"/>
            <circle cx="17.5" cy="6.5" r="1.25" fill="currentColor"/>
          </svg>
        @elseif ($link['platform'] === 'twitter')
          <svg class="social-icons__svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M4 4l7.5 9.5L4 20h2.5l6-6.8 4.9 6.8H20l-8-10.2L18.7 4h-2.5l-5.5 6.3L6.8 4H4z" fill="currentColor"/>
          </svg>
        @elseif ($link['platform'] === 'linkedin')
          <svg class="social-icons__svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-12h4v2" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
            <rect x="2" y="9" width="4" height="12" stroke="currentColor" stroke-width="1.75"/>
            <circle cx="4" cy="4" r="2" stroke="currentColor" stroke-width="1.75"/>
          </svg>
        @elseif ($link['platform'] === 'youtube')
          <svg class="social-icons__svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2 29 29 0 0 0-.46 5.33 29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.47 8.6.47 8.6.47s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.33 29 29 0 0 0-.46-5.33z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M10 9l6 3.5-6 3.5V9z" fill="currentColor"/>
          </svg>
        @else
          <svg class="social-icons__svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.75"/>
            <path d="M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
          </svg>
        @endif
      </a>
    @endforeach
  </div>
@endif

