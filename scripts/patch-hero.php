<?php

$path = __DIR__.'/../resources/views/sections/home/hero.blade.php';
$lines = file($path);
$head = array_slice($lines, 0, 49);
$tail = <<<'BLADE'

        <p class="hero-lede fade-up" data-delay="3">{!! $s['lede_html'] ?? 'ChhattisgarhABC is a community platform where <b>youth, professionals, civil society and government</b> come together to share experiences and understand <em>Social &amp; Behaviour Change Communication</em> across Chhattisgarh.' !!}</p>

        <div class="hero-cta fade-up" data-delay="4">
          @foreach ($s['ctas'] ?? [['label' => 'Explore work', 'class' => 'btn btn-primary', 'link' => ['type' => 'route', 'name' => 'campaigns'], 'show_arrow' => true], ['label' => 'Join the alliance', 'class' => 'btn btn-ghost', 'link' => ['type' => 'route', 'name' => 'get-involved']]] as $cta)
          @php $link = $cta['link'] ?? []; $ctaUrl = match ($link['type'] ?? 'route') { 'route' => route($link['name'] ?? 'home'), 'anchor' => $link['hash'] ?? '#', default => $link['url'] ?? '#' }; @endphp
          <a class="{{ $cta['class'] }}" href="{{ $ctaUrl }}" @if(!empty($link['external'])) target="_blank" rel="noopener" @endif>
            {{ $cta['label'] }}
            @if (!empty($cta['show_arrow']))
            <svg class="arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
            @endif
          </a>
          @endforeach
        </div>

      </div>

      <div class="hero-visual fade-up" data-delay="3" aria-hidden="true">
        @foreach ($s['panels'] ?? [['class' => 'panel-1', 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/banner/1714379236.png'], ['class' => 'panel-2', 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/banner/1714379202.png']] as $panel)
        <motion class="panel {{ $panel['class'] }}" style="background-image:url('{{ $panel['image'] }}');"></div>
        @endforeach
        @php $tag = $s['panel_tag'] ?? ['kicker' => 'District coverage', 'value' => '33/33', 'small' => 'Districts engaged']; $stat = $s['panel_stat'] ?? ['kicker' => 'Beneficiaries reached', 'value' => '1.4M', 'small' => 'Across households, schools and panchayats']; @endphp
        <div class="panel-tag">
          <span class="kicker">{{ $tag['kicker'] }}</span>
          <strong>{{ $tag['value'] }}</strong>
          <small>{{ $tag['small'] }}</small>
        </div>
        <div class="panel-stat">
          <span class="kicker">{{ $stat['kicker'] }}</span>
          <div class="big">{{ $stat['value'] }}</div>
          <small>{{ $stat['small'] }}</small>
        </div>
      </div>
    </div>
  </div>
</section>
BLADE;

$tail = str_replace('<motion class="panel', '<div class="panel', $tail);

file_put_contents($path, implode('', $head).$tail);
echo "patched\n";
