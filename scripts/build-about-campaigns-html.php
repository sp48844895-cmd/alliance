<?php

function processPage(array $map, string $page): array
{
    $htmlSections = [];

    foreach ($map as $key => $info) {
        $full = __DIR__.'/../'.$info['file'];
        $raw = file_get_contents($full);
        $raw = preg_replace('/^@php.*?\?>\s*/s', '', $raw);
        preg_match('/'.preg_quote($info['open'], '/').'\s*(.*)\s*<\/section>/s', $raw, $m);
        $html = trim($m[1] ?? '');
        $htmlSections[$key] = $html;

        $defaultPath = __DIR__.'/../resources/views/sections/defaults/'.$page.'/'.$key.'.blade.php';
        if (! is_dir(dirname($defaultPath))) {
            mkdir(dirname($defaultPath), 0777, true);
        }
        file_put_contents($defaultPath, $html);

        $partial = '@php $s = $pageSections['.var_export($key, true).'] ?? []; @endphp'."\n"
            .$info['open']."\n"
            ."@if(!empty(\$s['html']))\n{!! \$s['html'] !!}\n@else\n@include('sections.defaults.$page.$key')\n@endif\n"
            ."</section>\n";
        file_put_contents($full, $partial);
        echo "OK $page/$key\n";
    }

    return $htmlSections;
}

$aboutMap = [
    'hero' => ['file' => 'resources/views/sections/about/hero.blade.php', 'open' => '<section class="ab-hero" aria-labelledby="ab-hero-h">'],
    'vision_mission' => ['file' => 'resources/views/sections/about/vision_mission.blade.php', 'open' => '<section class="ab-vm container-x" aria-labelledby="ab-vm-h">'],
    'approach' => ['file' => 'resources/views/sections/about/approach.blade.php', 'open' => '<section class="ab-approach container-x" aria-labelledby="ab-app-h">'],
    'voices' => ['file' => 'resources/views/sections/about/voices.blade.php', 'open' => '<section class="st-voices" id="st-voices" aria-labelledby="st-voices-h">'],
    'partners' => ['file' => 'resources/views/sections/about/partners.blade.php', 'open' => '<section class="ab-partners" aria-labelledby="ab-p-h">'],
];

$campaignsMap = [
    'hero' => ['file' => 'resources/views/sections/campaigns/hero.blade.php', 'open' => '<section class="cmp-hero" aria-labelledby="cmp-hero-h">'],
    'filters' => ['file' => 'resources/views/sections/campaigns/filters.blade.php', 'open' => '<section class="cmp-filters" aria-label="Filter campaigns by theme and district">'],
    'grid' => ['file' => 'resources/views/sections/campaigns/grid.blade.php', 'open' => '<section class="cmp-grid container-x" id="cmp-grid" aria-labelledby="cmp-grid-h">'],
    'timelines' => ['file' => 'resources/views/sections/campaigns/timelines.blade.php', 'open' => '<section class="cmp-tl-section" aria-labelledby="cmp-tl-h">'],
    'cta' => ['file' => 'resources/views/sections/campaigns/cta.blade.php', 'open' => '<section class="cmp-cta" aria-labelledby="cmp-cta-h">'],
];

$aboutHtml = processPage($aboutMap, 'about');
$campaignsHtml = processPage($campaignsMap, 'campaigns');

file_put_contents(__DIR__.'/../database/seeders/content/about_html.php', '<?php return '.var_export($aboutHtml, true).";\n");
file_put_contents(__DIR__.'/../database/seeders/content/campaigns_html.php', '<?php return '.var_export($campaignsHtml, true).";\n");

function section(string $key, string $type, array $content, int $sort): array
{
    return ['key' => $key, 'type' => $type, 'content' => $content, 'sort' => $sort];
}

$about = [
    section('meta', 'meta', [
        'meta_description' => 'ChhattisgarhABC is an open, non-financial alliance of youth, professionals, civil society and government — co-creating Social & Behaviour Change Communication across Chhattisgarh, with a deep focus on PVTG villages.',
    ], 1),
];
$sort = 2;
foreach (array_keys($aboutMap) as $key) {
    $about[] = section($key, $key, ['html' => $aboutHtml[$key]], $sort++);
}

$campaigns = [
    section('meta', 'meta', [
        'meta_description' => 'Six in-progress SBC campaigns across Chhattisgarh — Role of Males, Children & Education, Life Cycle Nutrition, Gender & Behaviour, Adolescent Health, and Community Participation. Filter by theme, district, and stage.',
    ], 1),
];
$sort = 2;
foreach (array_keys($campaignsMap) as $key) {
    $campaigns[] = section($key, $key, ['html' => $campaignsHtml[$key]], $sort++);
}

file_put_contents(__DIR__.'/../database/seeders/content/about_sections.php', '<?php return '.var_export($about, true).";\n");
file_put_contents(__DIR__.'/../database/seeders/content/campaigns_sections.php', '<?php return '.var_export($campaigns, true).";\n");

$home = require __DIR__.'/../database/seeders/content/home_sections.php';
$homeHtml = require __DIR__.'/../database/seeders/content/home_html.php';
foreach ($home as &$section) {
    if (isset($homeHtml[$section['key']])) {
        $section['content']['html'] = $homeHtml[$section['key']];
    }
}
file_put_contents(__DIR__.'/../database/seeders/content/home_sections.php', '<?php return '.var_export($home, true).";\n");

echo "All section files written\n";
