<?php

require __DIR__.'/../vendor/autoload.php';

$output = "<?php\n\nreturn [\n    'home' => ".var_export(buildHome(), true).",\n    'about' => ".var_export(buildAbout(), true).",\n    'campaigns' => ".var_export(buildCampaigns(), true).",\n];\n";

file_put_contents(__DIR__.'/../database/seeders/content/dynamic_pages.php', $output);
echo "Wrote dynamic_pages.php\n";

function buildHome(): array
{
    return [
        section('meta', 'meta', [
            'meta_description' => 'ChhattisgarhABC is a community platform where youth, professionals, civil society and government come together to share experiences and advance Social and Behaviour Change Communication (SBC) across Chhattisgarh.',
        ], 1),
        section('hero', 'hero', [
            'video_poster' => 'https://www.chhattisgarhabc.org/stories/uploads/banner/1714379236.png',
            'video_src' => 'assets/videos/hero.mp4',
            'video_fallback' => 'https://videos.pexels.com/video-files/3045163/3045163-hd_1920_1080_30fps.mp4',
            'chapter_num' => '01',
            'chapter_label' => 'Welcome',
            'headline_html' => '<span class="line">Social &amp;</span><span class="line line-nowrap"><span class="underline"><em>Behaviour Change</em></span></span><span class="line">Communication for all.</span>',
            'lede_html' => 'ChhattisgarhABC is a community platform where <b>youth, professionals, civil society and government</b> come together to share experiences and understand <em>Social &amp; Behaviour Change Communication</em> across Chhattisgarh.',
            'ctas' => [
                ['label' => 'Explore work', 'style' => 'btn-primary', 'link' => ['type' => 'route', 'name' => 'campaigns'], 'arrow' => true],
                ['label' => 'Join the alliance', 'style' => 'btn-ghost', 'link' => ['type' => 'route', 'name' => 'get-involved']],
            ],
            'panels' => [
                ['class' => 'panel-1', 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/banner/1714379236.png'],
                ['class' => 'panel-2', 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/banner/1714379202.png'],
            ],
            'panel_tag' => ['kicker' => 'District coverage', 'value' => '33/33', 'small' => 'Districts engaged'],
            'panel_stat' => ['kicker' => 'Beneficiaries reached', 'value' => '1.4M', 'small' => 'Across households, schools and panchayats'],
        ], 2),
        section('marquee', 'marquee', [
            'items' => [
                ['num' => '5,000', 'label' => 'volunteers'],
                ['num' => '15', 'label' => 'firms/bodies'],
                ['num' => '144', 'label' => 'NGO/CSO'],
                ['num' => '35', 'label' => 'academia'],
            ],
            'duplicate' => true,
        ], 3),
        section('intro', 'intro', [
            'chapter_num' => '02',
            'chapter_label' => 'What is SBC?',
            'heading_html' => 'A practice that begins with <em>communication</em>.',
            'lede_html' => 'A powerful approach that uses communication strategies to bring about <b>behaviour change</b> and improve health outcomes. It goes beyond traditional advertising — coordinating messaging across channels to reach individuals, communities and policymakers, grounded in evidence-based research.',
            'pillars' => [
                ['num' => 'i.', 'title' => 'Define the issue', 'text' => 'Identify the specific health or social issue, gather data, and frame the problem before designing.', 'delay' => 0],
                ['num' => 'ii.', 'title' => 'Apply SBC theory', 'text' => 'Frame the programme using evidence-based behaviour-change theory — not assumptions.', 'delay' => 80],
                ['num' => 'iii.', 'title' => 'Coordinate channels', 'text' => 'Reach individuals, communities and policymakers — radio, print, video, and field outreach in sync.', 'delay' => 160],
                ['num' => 'iv.', 'title' => 'Measure &amp; iterate', 'text' => 'Track outcomes, share learnings across the alliance, and refine messaging every cycle.', 'delay' => 240],
            ],
        ], 4),
    ];
}

function section(string $key, string $type, array $content, int $sort): array
{
    return ['key' => $key, 'type' => $type, 'content' => $content, 'sort' => $sort];
}

function buildAbout(): array
{
    return [];
}

function buildCampaigns(): array
{
    return [];
}
