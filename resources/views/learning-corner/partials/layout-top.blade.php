@php
    $lcEyebrow = $lcEyebrow ?? 'Learning space';
    $lcTitle = $lcTitle ?? 'Learning <em>Corner</em>';
    $lcLede = $lcLede ?? 'Explore short modules, videos, posters, flipbooks and training material for strengthening SBC practice.';
    $lcStats = $lcStats ?? [];
@endphp

<section class="lc-page-hero">
  <div class="container-x">
    <div class="lc-page-hero__inner">
      <p class="lc-eyebrow">{{ $lcEyebrow }}</p>
      <h2 class="lc-page-hero__title">{!! $lcTitle !!}</h2>
      <p class="lc-page-hero__lede">{{ $lcLede }}</p>

      @if (! empty($lcStats))
        <ul class="lc-hero-stats">
          @foreach ($lcStats as $stat)
            <li>
              <strong>{{ $stat['value'] ?? '' }}</strong>
              <span>{{ $stat['label'] ?? '' }}</span>
            </li>
          @endforeach
        </ul>
      @endif
    </div>
  </div>
</section>

<div class="lc-shell">
  <div class="container-x">
