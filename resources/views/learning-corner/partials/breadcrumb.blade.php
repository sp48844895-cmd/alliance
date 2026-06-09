<nav class="lc-breadcrumb" aria-label="Category path">
  <ol class="lc-breadcrumb__list">
    @foreach ($items as $item)
      <li class="lc-breadcrumb__item">
        @if (!empty($item['url']))
          <a href="{{ $item['url'] }}" class="lc-breadcrumb__link">{{ $item['label'] }}</a>
        @else
          {{ $item['label'] }}
        @endif
      </li>
    @endforeach
  </ol>
</nav>
