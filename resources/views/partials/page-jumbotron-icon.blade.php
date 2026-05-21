@switch($icon ?? 'spark')
  @case('target')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <circle cx="12" cy="12" r="8"></circle>
      <circle cx="12" cy="12" r="4"></circle>
      <path d="M12 2v2"></path>
      <path d="M12 20v2"></path>
      <path d="M2 12h2"></path>
      <path d="M20 12h2"></path>
    </svg>
    @break

  @case('campaign')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M4 11V6a2 2 0 0 1 2-2h7l5 4v3"></path>
      <path d="M14 4v4h4"></path>
      <path d="M5 15h9"></path>
      <path d="M5 19h7"></path>
      <path d="M18 14v7"></path>
      <path d="M15 18h6"></path>
    </svg>
    @break

  @case('story')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v17.5a.5.5 0 0 1-.8.4L17 19.2a1 1 0 0 0-1.2 0l-2.2 1.7a1 1 0 0 1-1.2 0l-2.2-1.7a1 1 0 0 0-1.2 0L6.8 20.9a.5.5 0 0 1-.8-.4z"></path>
      <path d="M8 7h8"></path>
      <path d="M8 11h8"></path>
      <path d="M8 15h5"></path>
    </svg>
    @break

  @case('calendar')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <rect x="3" y="5" width="18" height="16" rx="2"></rect>
      <path d="M16 3v4"></path>
      <path d="M8 3v4"></path>
      <path d="M3 10h18"></path>
      <path d="M8 14h3v3H8z"></path>
    </svg>
    @break

  @case('book')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
      <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
      <path d="M9 7h7"></path>
      <path d="M9 11h7"></path>
    </svg>
    @break

  @case('users')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"></path>
      <circle cx="9.5" cy="7" r="3"></circle>
      <path d="M20 21v-2a4 4 0 0 0-3-3.87"></path>
      <path d="M16 4.13a3 3 0 0 1 0 5.74"></path>
    </svg>
    @break

  @case('mail')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <rect x="3" y="5" width="18" height="14" rx="2"></rect>
      <path d="m4 7 8 6 8-6"></path>
      <path d="M8 13 4 17"></path>
      <path d="m20 17-4-4"></path>
    </svg>
    @break

  @case('chart')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M3 3v18h18"></path>
      <path d="M7 14l4-4 3 3 5-6"></path>
      <path d="M19 7h-4"></path>
    </svg>
    @break

  @case('shield')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M12 3l7 3v6c0 5-3.5 8-7 9-3.5-1-7-4-7-9V6z"></path>
      <path d="m9.5 12 1.7 1.7 3.3-3.4"></path>
    </svg>
    @break

  @default
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M12 3 14.8 8.2 20 11l-5.2 2.8L12 19l-2.8-5.2L4 11l5.2-2.8z"></path>
      <path d="M19 4v4"></path>
      <path d="M21 6h-4"></path>
    </svg>
@endswitch
