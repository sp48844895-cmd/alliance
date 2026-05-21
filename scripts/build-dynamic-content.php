<?php

function section(string $key, string $type, array $content, int $sort): array
{
    return ['key' => $key, 'type' => $type, 'content' => $content, 'sort' => $sort];
}

function linkRoute(string $name): array
{
    return ['type' => 'route', 'name' => $name];
}

function linkUrl(string $url, bool $external = false): array
{
    return ['type' => 'url', 'url' => $url, 'external' => $external];
}

function linkAnchor(string $hash): array
{
    return ['type' => 'anchor', 'hash' => $hash];
}

$home = [
    section('meta', 'meta', [
        'meta_description' => 'ChhattisgarhABC is a community platform where youth, professionals, civil society and government come together to share experiences and advance Social and Behaviour Change Communication (SBC) across Chhattisgarh.',
    ], 1),
    section('hero', 'hero', [
        'video_poster' => 'https://www.chhattisgarhabc.org/stories/uploads/banner/1714379236.png',
        'video_src' => 'assets/videos/hero.mp4',
        'video_fallback' => 'https://videos.pexels.com/video-files/3045163/3045163-hd_1920_1080_30fps.mp4',
        'chapter_num' => '01',
        'chapter_label' => 'Welcome',
        'headline_html' => '<span class="line">Social &amp;</span>'."\n          ".'<span class="line line-nowrap"><span class="underline"><em>Behaviour Change</em></span></span>'."\n          ".'<span class="line">Communication for all.</span>',
        'lede_html' => 'ChhattisgarhABC is a community platform where <b>youth, professionals, civil society and government</b> come together to share experiences and understand <em>Social &amp; Behaviour Change Communication</em> across Chhattisgarh.',
        'ctas' => [
            ['label' => 'Explore work', 'class' => 'btn btn-primary', 'link' => linkRoute('campaigns'), 'show_arrow' => true],
            ['label' => 'Join the alliance', 'class' => 'btn btn-ghost', 'link' => linkRoute('get-involved')],
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
    ], 3),
    section('intro', 'intro', [
        'chapter_num' => '02',
        'chapter_label' => 'What is SBC?',
        'heading_html' => 'A practice that begins with <em>communication</em>.',
        'lede_html' => 'A powerful approach that uses communication strategies to bring about <b>behaviour change</b> and improve health outcomes. It goes beyond traditional advertising — coordinating messaging across channels to reach individuals, communities and policymakers, grounded in evidence-based research.',
        'pillars' => [
            ['num' => 'i.', 'title' => 'Define the issue', 'text' => 'Identify the specific health or social issue, gather data, and frame the problem before designing.', 'aos_delay' => null],
            ['num' => 'ii.', 'title' => 'Apply SBC theory', 'text' => 'Frame the programme using evidence-based behaviour-change theory — not assumptions.', 'aos_delay' => 80],
            ['num' => 'iii.', 'title' => 'Coordinate channels', 'text' => 'Reach individuals, communities and policymakers — radio, print, video, and field outreach in sync.', 'aos_delay' => 160],
            ['num' => 'iv.', 'title' => 'Measure &amp; iterate', 'text' => 'Track outcomes, share learnings across the alliance, and refine messaging every cycle.', 'aos_delay' => 240],
        ],
    ], 4),
    section('programs', 'programs', [
        'chapter_num' => '03',
        'chapter_label' => 'Programs & Initiatives',
        'heading_html' => 'Community-led work that turns <em>trust</em> into action.',
        'lede_html' => 'A focused view of flagship SBC initiatives across Chhattisgarh, from youth volunteer networks to local learning resources and community-led health campaigns.',
        'cards' => [
            [
                'featured' => true,
                'tag' => 'Dantewada · Nutrition & Health',
                'title' => 'Bapi Na Uwat',
                'lede' => 'Bapi Na Uwat is an innovative community-led SBC initiative launched in Dantewada by the district administration and UNICEF to reduce malnutrition and improve health behaviours in tribal communities.',
                'paragraphs' => [
                    'The initiative uses trusted elderly women, known as “Bapis,” to spread awareness on nutrition, breastfeeding, maternal care, and child health through local traditions and conversations. Implemented across 143 gram panchayats, the programme combines traditional wisdom with behaviour change communication tools such as local language videos, chaupals, and community engagement activities.',
                    'Supported by village volunteers and frontline workers, the campaign has helped strengthen trust, awareness, and community participation around health and nutrition practices in remote areas of Bastar.',
                ],
                'aos_delay' => null,
            ],
            [
                'tag' => 'Youth movement',
                'title' => 'Yuvoday',
                'paragraphs' => [
                    'Yuvoday is a youth-led volunteer movement launched in Chhattisgarh with support from district administrations and UNICEF to strengthen community participation and behaviour change. Meaning “Rise of the Youth,” the initiative has built a network of over 12,000 volunteers who work across villages, urban wards, and tribal communities.',
                    'Yuvoday volunteers support campaigns related to health, nutrition, sanitation, education, mental health, social protection, and COVID-19 awareness. By connecting youth with communities and frontline workers, the programme promotes local leadership, trust-building, and people-centered development.',
                ],
                'aos_delay' => 80,
            ],
            [
                'modifier' => 'program-card--teal',
                'tag' => 'Bijapur · Youth messengers',
                'title' => 'BijaDuteer',
                'paragraphs' => [
                    'BijaDuteer is a youth volunteer initiative in Bijapur supported by the District Administration, UNICEF, and Chhattisgarh Agricon Samiti. In the local dialect, “Bija” refers to Bijapur and “Duteer” means messenger, representing a youth brigade working as community messengers for positive change.',
                    'In this naxal-affected and remote district, BijaDuteer volunteers help bridge the gap between communities and governance by promoting awareness on health, nutrition, education, child rights, mental wellbeing, and positive parenting.',
                ],
                'aos_delay' => 160,
            ],
            [
                'modifier' => 'program-card--ochre',
                'tag' => 'Jashpur · Youth leadership',
                'title' => 'JAY HO!',
                'paragraphs' => [
                    'JAY HO, the Jashpur Alliance of Youth for Hope and Opportunity, is a youth empowerment initiative in Jashpur launched by the District Administration and UNICEF to support adolescent wellbeing and positive behaviour change.',
                    'The initiative focuses on adolescent health, life skills, online safety, safe migration, mental wellbeing, anaemia, child marriage, and substance abuse through youth-led engagement, peer learning, and community participation.',
                ],
                'aos_delay' => 240,
            ],
            [
                'modifier' => 'program-card--leaf',
                'tag' => 'Knowledge sharing',
                'title' => 'Learning Corners',
                'paragraphs' => [
                    'The Learning Corner is a shared resource space under Alliance for Behaviour Change where IEC materials, training modules, toolkits, campaign resources, and communication materials are made accessible for learning, knowledge sharing, community mobilization, and awareness generation.',
                    'The platform encourages individuals and organizations to freely explore, download, and use these resources to strengthen Social and Behaviour Change Communication efforts across communities.',
                ],
                'aos_delay' => 320,
            ],
        ],
    ], 5),
    section('champions', 'champions', [
        'chapter_num' => '04',
        'chapter_label' => 'Recent Stories',
        'heading_html' => 'Voices from the <em>field</em>.',
        'side_text' => 'A rolling chronicle of behaviour-change work — campaigns, milestones, and human stories from across Chhattisgarh.',
        'stories_link' => linkRoute('stories'),
        'stories_link_label' => 'Read all stories →',
        'items' => [
            ['image' => 'https://www.chhattisgarhabc.org/stories/uploads/story/69b2a3de375ea.png', 'pill' => 'Campaign', 'pill_style' => null, 'where' => '12 March 2026 · ChhattisgarhABC', 'title' => 'Chhattisgarh launches youth-led dashboard and "Aaj Mauka Hai" campaign', 'blurb' => 'A youth-led dashboard tracking behaviour-change action, alongside the "Aaj Mauka Hai" outreach campaign rolling across districts.'],
            ['image' => 'https://www.chhattisgarhabc.org/stories/uploads/story/69a086247a963.png', 'pill' => 'Education', 'pill_style' => 'background:var(--leaf); color:var(--paper);', 'where' => '26 February 2026 · ChhattisgarhABC', 'title' => 'एक सवाल, बड़ा बदलाव — \'आज क्या सीखा?\' ने जशपुर में…', 'blurb' => 'A simple daily question reshaping classroom conversations across Jashpur — peer-led and teacher-supported.'],
            ['image' => 'https://www.chhattisgarhabc.org/stories/uploads/story/69974fe1da143.png', 'pill' => 'Workshop', 'pill_style' => 'background:var(--terracotta); color:var(--paper);', 'where' => '19 February 2026 · ChhattisgarhABC', 'title' => 'UNICEF Human-Centered Design Workshop, Bilaspur', 'blurb' => 'Tackling disability stigma through participatory HCD methods, co-led with UNICEF and local champions.'],
            ['image' => 'https://www.chhattisgarhabc.org/stories/uploads/story/696641b1e3114.png', 'pill' => 'Milestone', 'pill_style' => 'background:var(--teal); color:var(--paper);', 'where' => '13 January 2026 · ChhattisgarhABC', 'title' => 'Balod becomes India\'s first Child Marriage–Free District', 'blurb' => 'A multi-year SBC effort culminates in a verified district-wide milestone — community-owned, government-backed.'],
            ['image' => 'https://www.chhattisgarhabc.org/stories/uploads/story/67ce9ac3493da.png', 'pill' => 'Lab', 'pill_style' => null, 'where' => '10 March 2025 · admin', 'title' => 'Kavir Participatory Action Lab — community-driven change', 'blurb' => 'A hub for participatory research and field-tested behaviour-change interventions, run by and for the community.'],
            ['image' => 'https://www.chhattisgarhabc.org/stories/uploads/story/67909992e8e60.png', 'pill' => 'Mental Health', 'pill_style' => 'background:var(--ochre); color:var(--ink);', 'where' => '21 January 2025 · admin', 'title' => 'Yuvoday Volunteers — Championing Mental Health Inclusion', 'blurb' => 'Youth-led groups advancing mental-health inclusion across Chhattisgarh\'s panchayats — circles, conversations, referrals.'],
        ],
    ], 6),
    section('events', 'events', [
        'chapter_num' => '05',
        'chapter_label' => 'Events',
        'heading_html' => 'Where the alliance <em>meets</em>.',
        'side_text' => 'Upcoming webinars, workshops and convenings from across Chhattisgarh, all in one quick events preview.',
        'tiles' => [
            ['class' => 'tile-1', 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/event/427748.png', 'tag' => 'Featured · Webinar', 'title' => 'SBC at scale: counselling 50,000 adolescents', 'text' => 'A 90-minute live webinar with master trainers from Bilaspur, Bastar and Surguja, followed by an open Q&amp;A.', 'aos_delay' => null],
            ['class' => 'tile-2', 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/story/696641b1e3114.png', 'tag' => 'Conference · 3-day', 'title' => 'State SBC Summit 2026', 'text' => 'Six tracks, fourteen partners and one state-level showcase of behaviour-change programmes in Raipur.', 'aos_delay' => 80],
            ['class' => 'tile-3', 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/event/171026.jpeg', 'tag' => 'Workshop · 1-day', 'title' => 'Adolescent Health Bootcamp', 'text' => 'A field-day in Bilaspur focused on counselling techniques, role-play and live learning with kishori groups.', 'aos_delay' => 160],
            ['class' => 'tile-4', 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/event/397224.png', 'tag' => 'Webinar · Live', 'title' => 'Father engagement: deep-dive', 'text' => 'Why anganwadi attendance for fathers tripled in Kabirdham, and what did not work in the first phase.', 'aos_delay' => 220],
            ['class' => 'tile-5', 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/event/427748.png', 'tag' => 'Completed · Conclave', 'title' => 'Adolescent Voices Conclave', 'text' => '240 adolescents from 18 districts came together for one of the alliance\'s biggest youth-led gatherings.', 'aos_delay' => 280],
        ],
        'events_link' => linkRoute('events'),
    ], 7),
    section('hub', 'hub', [
        'chapter_num' => '06',
        'chapter_label' => 'Resource Kit',
        'heading_html' => 'Knowledge assets around <em>behaviour change</em>.',
        'side_text' => 'Open-licensed guides, toolkits, stories and participation pathways — kept simple and easy to use.',
        'resources' => [
            ['link' => linkRoute('knowledge-hub'), 'type' => 'Guides', 'title' => 'Knowledge Hub', 'meta' => 'Guides, toolkits and research', 'aos_delay' => null, 'icon' => 'layers'],
            ['link' => linkRoute('campaigns'), 'type' => 'Campaigns', 'title' => 'What We Do', 'meta' => 'Six live thematic campaigns', 'aos_delay' => 100, 'icon' => 'chart'],
            ['link' => linkRoute('stories'), 'type' => 'Stories', 'title' => 'Impact Stories', 'meta' => 'Champions, case studies and voices', 'aos_delay' => 200, 'icon' => 'grid'],
            ['link' => linkRoute('get-involved'), 'type' => 'Get Involved', 'title' => 'Join the Alliance', 'meta' => 'Volunteer, partner or support', 'aos_delay' => 300, 'icon' => 'book'],
        ],
        'library_link' => linkRoute('knowledge-hub'),
        'library_label' => 'Open the full library →',
    ], 8),
    section('cta', 'cta', [
        'chapter_num' => '07',
        'chapter_label' => 'Get in Touch',
        'heading_html' => 'For more about the <em>Alliance</em>.',
        'lede_html' => 'Whether you are a frontline worker, a CSR partner, an academic researcher, or a youth volunteer — write to us, follow our updates, or visit us at Raipur.',
        'paths' => [
            ['title' => 'Write to us', 'text' => 'info@chhttisgarhabc.org · +91 90984 98822', 'link' => linkRoute('contact')],
            ['title' => 'Follow on Facebook', 'text' => 'Daily updates from the field', 'link' => linkUrl('https://www.facebook.com/ChhattisgarhABC/', true)],
            ['title' => 'Instagram', 'text' => 'Stories, reels &amp; campaign visuals', 'link' => linkUrl('https://www.instagram.com/chhattisgarhabc/', true)],
            ['title' => 'YouTube', 'text' => 'Talk shows, workshops &amp; field documentaries', 'link' => linkUrl('https://www.youtube.com/@chhattisgarhabc', true)],
        ],
    ], 9),
];

file_put_contents(__DIR__.'/../database/seeders/content/home_sections.php', "<?php\n\nreturn ".var_export($home, true).";\n");
echo "home_sections.php written (".count($home)." sections)\n";
