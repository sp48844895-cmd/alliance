<?php

$replacements = [
    'resources/views/sections/home/hero.blade.php' => [
        'poster="https://www.chhattisgarhabc.org/stories/uploads/banner/1714379236.png"' => 'poster="{{ $s[\'video_poster\'] ?? \'https://www.chhattisgarhabc.org/stories/uploads/banner/1714379236.png\' }}"',
        '{{ asset("assets/videos/hero.mp4") }}' => '{{ asset($s[\'video_src\'] ?? \'assets/videos/hero.mp4\') }}',
        'https://videos.pexels.com/video-files/3045163/3045163-hd_1920_1080_30fps.mp4' => '{{ $s[\'video_fallback\'] ?? \'https://videos.pexels.com/video-files/3045163/3045163-hd_1920_1080_30fps.mp4\' }}',
        '<b>01</b> · Welcome' => '<b>{{ $s[\'chapter_num\'] ?? \'01\' }}</b> · {{ $s[\'chapter_label\'] ?? \'Welcome\' }}',
        '<span class="line">Social &amp;</span>
          <span class="line line-nowrap"><span class="underline"><em>Behaviour Change</em></span></span>
          <span class="line">Communication for all.</span>' => '{!! $s[\'headline_html\'] ?? \'<span class="line">Social &amp;</span><span class="line line-nowrap"><span class="underline"><em>Behaviour Change</em></span></span><span class="line">Communication for all.</span>\' !!}',
        'ChhattisgarhABC is a community platform where <b>youth, professionals, civil society and government</b> come together to share experiences and understand <em>Social &amp; Behaviour Change Communication</em> across Chhattisgarh.' => '{!! $s[\'lede_html\'] ?? \'ChhattisgarhABC is a community platform where <b>youth, professionals, civil society and government</b> come together to share experiences and understand <em>Social &amp; Behaviour Change Communication</em> across Chhattisgarh.\' !!}',
    ],
];

foreach ($replacements as $file => $map) {
    $path = __DIR__.'/../'.$file;
    $content = file_get_contents($path);
    foreach ($map as $from => $to) {
        $content = str_replace($from, $to, $content);
    }
    file_put_contents($path, $content);
    echo "Updated $file\n";
}
