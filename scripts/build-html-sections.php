<?php

$sections = [];

$map = [
    'intro' => ['file' => 'resources/views/sections/home/intro.blade.php', 'open' => '<section class="intro container-x" aria-labelledby="intro-h">'],
    'programs' => ['file' => 'resources/views/sections/home/programs.blade.php', 'open' => '<section class="programs-section" id="programs-initiatives" aria-labelledby="programs-h">'],
    'champions' => ['file' => 'resources/views/sections/home/champions.blade.php', 'open' => '<section class="champions-section" aria-labelledby="ch-h">'],
    'events' => ['file' => 'resources/views/sections/home/events.blade.php', 'open' => '<section class="container-x section" aria-labelledby="events-home-h">'],
    'hub' => ['file' => 'resources/views/sections/home/hub.blade.php', 'open' => '<section class="hub container-x" aria-labelledby="hub-h">'],
    'cta' => ['file' => 'resources/views/sections/home/cta.blade.php', 'open' => '<section class="cta" aria-labelledby="cta-h">'],
];

foreach ($map as $key => $info) {
    $full = __DIR__.'/../'.$info['file'];
    $raw = file_get_contents($full);
    $raw = preg_replace('/^@php.*?\?>\s*/s', '', $raw);
    preg_match('/'.preg_quote($info['open'], '/').'\s*(.*)\s*<\/section>/s', $raw, $m);
    $html = trim($m[1] ?? '');
    $sections[$key] = $html;

    $defaultPath = __DIR__.'/../resources/views/sections/defaults/home/'.$key.'.blade.php';
    if (! is_dir(dirname($defaultPath))) {
        mkdir(dirname($defaultPath), 0777, true);
    }
    file_put_contents($defaultPath, $html);

    $partial = '@php $s = $pageSections['.var_export($key, true).'] ?? []; @endphp'."\n"
        .$info['open']."\n"
        ."@if(!empty(\$s['html']))\n{!! \$s['html'] !!}\n@else\n@include('sections.defaults.home.$key')\n@endif\n"
        ."</section>\n";
    file_put_contents($full, $partial);
    echo "OK $key\n";
}

file_put_contents(__DIR__.'/../database/seeders/content/home_html.php', '<?php return '.var_export($sections, true).";\n");
