@php
    $link = $resource['link'] ?? [];
    $href = '#';
    if (($link['type'] ?? 'route') === 'route') {
        $base = route($link['name'] ?? 'home', $link['params'] ?? []);
        $query = $link['query'] ?? '';
        $fragment = $link['fragment'] ?? '';
        $href = $base
            . ($query !== '' ? (str_contains($base, '?') ? '&' : '?') . $query : '')
            . ($fragment !== '' ? '#' . ltrim($fragment, '#') : '');
    } elseif (($link['type'] ?? '') === 'anchor') {
        $href = $link['hash'] ?? '#';
    } else {
        $href = $link['url'] ?? '#';
    }
    $delay = $resource['aos_delay'] ?? null;
    $icon = $resource['icon'] ?? 'layers';
@endphp
<a href="{{ $href }}" class="resource" data-aos="fade-up"@if($delay !== null && $delay !== '') data-aos-delay="{{ $delay }}"@endif>
  <div class="resource-icon" aria-hidden="true">
    @if ($icon === 'book')
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h12a4 4 0 0 1 4 4v12H8a4 4 0 0 1-4-4z"/><path d="M4 4v12a4 4 0 0 0 4 4"/></svg>
    @elseif ($icon === 'chart')
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 14l4-4 3 3 5-6"/></svg>
    @elseif ($icon === 'users')
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    @else
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M2 7l10-4 10 4-10 4z"/><path d="M2 7v6c0 .5 4 4 10 4s10-3.5 10-4V7"/></svg>
    @endif
  </div>
  <span class="resource-type">{{ $resource['type'] ?? '' }}</span>
  <h4>{{ $resource['title'] ?? '' }}</h4>
  <div class="resource-meta">
    <span>{{ $resource['meta'] ?? '' }}</span>
    <span aria-hidden="true">→</span>
  </div>
</a>
