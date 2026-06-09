@if ($resource['link'] !== '')
  <a href="{{ $resource['link'] }}" class="lc-resource-card" target="_blank" rel="noopener noreferrer">
@else
  <article class="lc-resource-card">
@endif
  <div class="lc-resource-card__cover">
    @if ($resource['image'] !== '')
      <img src="{{ $resource['image'] }}" alt="">
    @else
      <span class="lc-resource-card__cover-label" aria-hidden="true">{{ $resource['cover_label'] }}</span>
    @endif
    <span class="lc-resource-card__type-badge">
      <i class="bi {{ $resource['type_icon'] }}" aria-hidden="true"></i> {{ $resource['type_label'] }}
    </span>
  </div>
  <div class="lc-resource-card__body">
    <h3 class="lc-resource-card__title">{{ $resource['title'] }}</h3>
    @if ($resource['content'] !== '')
      <p class="lc-resource-card__desc">{{ $resource['content'] }}</p>
    @endif
    <div class="lc-resource-card__footer">
      @if ($resource['date_label'] !== '')
        <span class="lc-resource-card__date">{{ $resource['date_label'] }}</span>
      @else
        <span></span>
      @endif
      @if ($resource['link'] !== '')
        <span class="lc-resource-card__link">Open <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span>
      @endif
    </div>
  </div>
@if ($resource['link'] !== '')
  </a>
@else
  </article>
@endif
