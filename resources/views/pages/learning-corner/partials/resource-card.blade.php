@php
  $href = !empty($resource->link) ? $resource->link : '';
  $typeLabels = ['book' => 'Book', 'posters' => 'Poster', 'mobile kunji' => 'Kunji', 'video' => 'Video'];
  $typeIcons = ['book' => 'bi-book', 'posters' => 'bi-image', 'mobile kunji' => 'bi-phone', 'video' => 'bi-play-circle'];
  $typeLabel = $typeLabels[$resource->m_type] ?? 'Resource';
  $typeIcon = $typeIcons[$resource->m_type] ?? 'bi-file-earmark';
  $initials = strtoupper(substr($resource->m_type ?? 'LC', 0, 2));
  $dateLabel = !empty($resource->date) ? \Illuminate\Support\Carbon::parse($resource->date)->format('d M Y') : '';
@endphp

@if ($href !== '')
  <a href="{{ $href }}" class="lc-resource-card" target="_blank" rel="noopener noreferrer">
@else
  <article class="lc-resource-card">
@endif
  <div class="lc-resource-card__cover">
    @if (!empty($resource->image_url))
      <img src="{{ $resource->image_url }}" alt="">
    @else
      <span class="lc-resource-card__cover-label" aria-hidden="true">{{ $initials }}</span>
    @endif
    <span class="lc-resource-card__type-badge">
      <i class="bi {{ $typeIcon }}" aria-hidden="true"></i> {{ $typeLabel }}
    </span>
  </div>
  <div class="lc-resource-card__body">
    @if (!empty($resource->cat_name))
      <span class="lc-resource-card__tag">{{ $resource->cat_name }}</span>
    @endif
    <h3 class="lc-resource-card__title">{{ $resource->title }}</h3>
    @if (!empty($resource->content))
      <p class="lc-resource-card__desc">{{ $resource->content }}</p>
    @endif
    <div class="lc-resource-card__footer">
      @if ($dateLabel !== '')
        <span class="lc-resource-card__date">{{ $dateLabel }}</span>
      @else
        <span></span>
      @endif
      @if ($href !== '')
        <span class="lc-resource-card__link">Open <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span>
      @endif
    </div>
  </div>
@if ($href !== '')
  </a>
@else
  </article>
@endif
