<?php

function extractSection(string $html, string $pattern, string $key, string $name): void
{
    if (! preg_match($pattern, $html, $m)) {
        fwrite(STDERR, "FAIL: $name\n");
        return;
    }

    $dir = dirname($name);
    if (! is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    file_put_contents($name, '@php $s = $pageSections['.var_export($key, true).'] ?? []; @endphp'."\n".$m[0]);
    echo "OK $name (".strlen($m[0]).")\n";
}

$home = file_get_contents(__DIR__.'/../resources/views/pages/home.blade.php');
$about = file_get_contents(__DIR__.'/../resources/views/pages/about.blade.php');
$cmp = file_get_contents(__DIR__.'/../resources/views/pages/campaigns.blade.php');

extractSection($home, '/<section class="hero has-video">.*?<\/section>/s', 'hero', __DIR__.'/../resources/views/sections/home/hero.blade.php');
extractSection($home, '/<div class="marquee"[^>]*>.*?<\/div>\s*/s', 'marquee', __DIR__.'/../resources/views/sections/home/marquee.blade.php');
extractSection($home, '/<section class="intro container-x"[^>]*>.*?<\/section>/s', 'intro', __DIR__.'/../resources/views/sections/home/intro.blade.php');
extractSection($home, '/<section class="programs-section"[^>]*>.*?<\/section>/s', 'programs', __DIR__.'/../resources/views/sections/home/programs.blade.php');
extractSection($home, '/<section class="champions-section"[^>]*>.*?<\/section>/s', 'champions', __DIR__.'/../resources/views/sections/home/champions.blade.php');
extractSection($home, '/<section class="container-x section" aria-labelledby="events-home-h">.*?<\/section>/s', 'events', __DIR__.'/../resources/views/sections/home/events.blade.php');
extractSection($home, '/<section class="hub container-x"[^>]*>.*?<\/section>/s', 'hub', __DIR__.'/../resources/views/sections/home/hub.blade.php');
extractSection($home, '/<section class="cta"[^>]*>.*?<\/section>/s', 'cta', __DIR__.'/../resources/views/sections/home/cta.blade.php');

extractSection($about, '/<section class="ab-hero"[^>]*>.*?<\/section>/s', 'hero', __DIR__.'/../resources/views/sections/about/hero.blade.php');
extractSection($about, '/<section class="ab-vm container-x"[^>]*>.*?<\/section>/s', 'vision_mission', __DIR__.'/../resources/views/sections/about/vision_mission.blade.php');
extractSection($about, '/<section class="ab-approach container-x"[^>]*>.*?<\/section>/s', 'approach', __DIR__.'/../resources/views/sections/about/approach.blade.php');
extractSection($about, '/<section class="st-voices"[^>]*>.*?<\/section>/s', 'voices', __DIR__.'/../resources/views/sections/about/voices.blade.php');
extractSection($about, '/<section class="ab-partners"[^>]*>.*?<\/section>/s', 'partners', __DIR__.'/../resources/views/sections/about/partners.blade.php');

extractSection($cmp, '/<section class="cmp-hero"[^>]*>.*?<\/section>/s', 'hero', __DIR__.'/../resources/views/sections/campaigns/hero.blade.php');
extractSection($cmp, '/<section class="cmp-filters"[^>]*>.*?<\/section>/s', 'filters', __DIR__.'/../resources/views/sections/campaigns/filters.blade.php');
extractSection($cmp, '/<section class="cmp-grid container-x"[^>]*>.*?<\/section>/s', 'grid', __DIR__.'/../resources/views/sections/campaigns/grid.blade.php');
extractSection($cmp, '/<section class="cmp-tl-section"[^>]*>.*?<\/section>/s', 'timelines', __DIR__.'/../resources/views/sections/campaigns/timelines.blade.php');
extractSection($cmp, '/<section class="cmp-cta"[^>]*>.*?<\/section>/s', 'cta', __DIR__.'/../resources/views/sections/campaigns/cta.blade.php');
