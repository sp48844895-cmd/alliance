@php $s = $pageSections['marquee'] ?? []; $items = $s['items'] ?? []; @endphp
<div class="marquee" aria-hidden="true">
  <div class="marquee-track">
    @foreach (array_merge($items, $items) as $item)
    <span class="marquee-item"><span class="num">{{ $item['num'] }}</span>&nbsp;{{ $item['label'] }}</span>
    <span class="marquee-item marquee-sep">·</span>
    @endforeach
  </div>
</div>
