<?php

namespace Database\Seeders\Concerns;

trait SeedsStoriesEventsKnowledgeGetInvolved
{
    private function storiesPageSections(): array
    {
        return [
            $this->section('hero', 'hero', [
                'chapter' => '04',
                'issue' => 'Issue 04 · Spring 2026',
                'title_lines' => ['The slow, stubborn', 'arc of <em>change.</em>'],
                'lede' => 'Twenty-three districts. Hundreds of villages. A few stubborn women, a few patient sarpanches, a master teacher and a kishori who learned to ask the question first. <b>These are their pages.</b>',
                'meta_rows' => [
                    ['label' => 'In this issue', 'value' => '<b>12</b> stories · <b>4</b> categories · <b>7</b> districts'],
                    ['label' => 'Cover story', 'value' => 'Kavita Sahu\'s notebook, Balod →', 'link_route' => 'stories.show', 'link_slug' => 'sarpanch-kavitas-notebook', 'link_class' => 'st-hero-meta-link'],
                ],
                'cta_primary' => ['label' => 'Read the issue', 'href' => '#st-grid'],
                'cta_secondary' => ['label' => 'Listen to voices', 'href' => '#st-voices'],
                'collage' => [
                    ['class' => 'st-hero-tile--1', 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/story/67909992e8e60.png'],
                    ['class' => 'st-hero-tile--2', 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/story/696641b1e3114.png', 'tag' => 'Champion · Balod'],
                    ['class' => 'st-hero-tile--3', 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/story/69a086247a963.png'],
                    ['class' => 'st-hero-tile--4', 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/story/67ce9ac3493da.png'],
                ],
                'quote' => ['text' => '"Beti, padhna —<br/>poori dheere se."', 'attr' => '— A father, Kabirdham'],
            ], 3),
            $this->section('filters', 'filters', [
                'categories' => [
                    ['value' => 'all', 'label' => 'All stories'],
                ],
                'districts' => [
                    ['value' => 'all', 'label' => 'All districts'],
                ],
                'count' => 0,
            ], 4),
            $this->section('story_grid', 'story_grid', [
                'empty' => [
                    'chapter' => '—',
                    'chapter_suffix' => 'No stories yet',
                    'text' => 'No stories match this combination of filters. Try a wider category, district, or date range, or',
                ],
                'cards' => [],
            ], 5),
            $this->section('recent', 'recent', [
                'chapter' => '04',
                'title' => 'Recent stories',
                'description' => 'Fresh updates from the alliance archive, arranged for quick reading by date and author.',
            ], 6),
            $this->section('videos', 'videos', [
                'chapter' => '05',
                'title' => 'Voices, in <em>their own</em> frames.',
                'subtitle' => 'Four short films, each under three minutes — recorded by community members on their own phones, in their own dialect.',
                'items' => [
                    ['video_id' => 'dQw4w9WgXcQ', 'title' => 'Anita, 16 — Bilaspur', 'aos_delay' => null, 'poster' => 'https://www.chhattisgarhabc.org/stories/uploads/event/427748.png', 'length' => '02:14', 'category' => 'Adolescent Health · Bilaspur', 'heading' => '"Pehli baar, <em>khulkar</em>"', 'description' => 'Anita, 16, on the day she asked her first health question in class.'],
                    ['video_id' => 'dQw4w9WgXcQ', 'title' => 'Sarpanch Manoj — Balod', 'aos_delay' => 80, 'poster' => 'https://www.chhattisgarhabc.org/stories/uploads/story/696641b1e3114.png', 'length' => '02:48', 'category' => 'Gender & Behaviour · Balod', 'heading' => 'The <em>signature</em> on every pledge', 'description' => 'Sarpanch Manoj on why he countersigns each girl\'s "18-tak-padhungi" promise.'],
                    ['video_id' => 'dQw4w9WgXcQ', 'title' => 'Phulwa Bai — Surguja', 'aos_delay' => 160, 'poster' => 'https://www.chhattisgarhabc.org/stories/uploads/story/67ce9ac3493da.png', 'length' => '01:52', 'category' => 'Life Cycle Nutrition · Surguja', 'heading' => '"Teen rang ki <em>thali</em>"', 'description' => 'Phulwa Bai cooks while she counsels — green, yellow, red, every meal.'],
                    ['video_id' => 'dQw4w9WgXcQ', 'title' => 'Hemant Sahu — Kabirdham', 'aos_delay' => 240, 'poster' => 'https://www.chhattisgarhabc.org/stories/uploads/story/67909992e8e60.png', 'length' => '02:31', 'category' => 'Role of Males · Kabirdham', 'heading' => 'The <em>weighing</em> mornings', 'description' => 'Hemant on why he stopped letting his wife go to the anganwadi alone.'],
                ],
            ], 7),
            $this->section('before_after', 'before_after', [
                'chapter' => '06',
                'title' => 'Two stories, <em>same village.</em>',
                'subtitle' => 'Not numbers — sentences. The same hamlet, same family, four years apart.',
                'items' => [
                    [
                        'location' => 'Sundarpur, Bastar · 2020 → 2024',
                        'heading' => 'The morning Phulwa Bai started weighing fathers\' children',
                        'before' => ['stamp' => 'Before · Sept 2020', 'text' => '"Fathers came to the anganwadi on the day of the polio drop. They came once a year, looked at the floor, signed the register, and walked out. Most of them couldn\'t tell me their child\'s last weight. Most of them had never held the scale."', 'attr' => '— field-notes, Phulwa Bai, anganwadi worker'],
                        'after' => ['stamp' => 'After · Sept 2024', 'text' => '"Today, eleven fathers stayed past 8 a.m. Two argued about the right way to position the cloth on the scale. Hemant brought a thermos. He asked me, \'Bai, kya rakam protein chahiye?\' I almost cried in the corner."', 'attr' => '— field-notes, same anganwadi, four years later'],
                    ],
                    [
                        'location' => 'Hatkachora, Balod · 2020 → 2026',
                        'heading' => 'What the panchayat used to whisper, and now writes down',
                        'before' => ['stamp' => 'Before · Apr 2020', 'text' => '"In the village register, marriages were rounded up. A 16-year-old became 18 with a pen-stroke. Nobody asked. Nobody wrote it down. The teachers knew, the sarpanch knew, everybody knew — and nobody had a notebook."', 'attr' => '— anonymous interview, Hatkachora'],
                        'after' => ['stamp' => 'After · Jan 2026', 'text' => '"The notebook now lives at the anganwadi. Every adolescent girl, by name. Every birth-date, verified by Aadhaar. Every quarterly check-in, signed by the sarpanch and the headmaster. Twelve months. Zero. We had to write it down to make it true."', 'attr' => '— Sarpanch Kavita Sahu'],
                    ],
                ],
            ], 8),
            $this->section('voices', 'voices', [
                'chapter' => '07',
                'title' => 'A wall of <em>quiet</em> evidence.',
                'subtitle' => 'Pause the marquee, read what they said.',
                'quotes' => [
                    ['quote' => '"Mein Class 9 mein hoon. Mere bhai ne pehli baar mere notebook mein dekha."', 'name' => 'Anita, 16', 'role' => 'Bilaspur · Adolescent', 'modifier' => ''],
                    ['quote' => '"Anganwadi sirf bachchon ka nahi, <em>baap ka bhi</em> kaam hai."', 'name' => 'Hemant Sahu', 'role' => 'Kabirdham · Father of two', 'modifier' => 'st-quote-card--ochre'],
                    ['quote' => '"Hum decide karte hain. Alliance sirf likhta hai."', 'name' => 'Sheela Bai', 'role' => 'Surguja · Gram sabha', 'modifier' => 'st-quote-card--leaf'],
                    ['quote' => '"Iss saal panchayat mein <em>zero</em> shaadi hui."', 'name' => 'Sarpanch Manoj', 'role' => 'Balod · Gender', 'modifier' => 'st-quote-card--teal'],
                    ['quote' => '"Jab maine pehli baar \'aaj kya seekha?\' poocha, beti chup ho gayi. Phir mujhe sikhaane lagi."', 'name' => 'Latika Devi', 'role' => 'Jashpur · Mother', 'modifier' => 'st-quote-card--saffron'],
                    ['quote' => '"Teacher ne nahi, <em>peer</em> ne sikhaaya. Phir maine peer ban kar sikhaaya."', 'name' => 'Rohit, 17', 'role' => 'Raipur · Peer leader', 'modifier' => 'st-quote-card--indigo'],
                ],
            ], 9),
            $this->section('cta', 'cta', [
                'chapter' => '08',
                'title' => 'Did one of <em>our pages</em> miss your village?',
                'lede' => 'Every issue, six new stories. If your sarpanch, your teacher, your daughter or your father did something the alliance hasn\'t yet documented — write to us. We\'ll come, we\'ll listen, we\'ll write it down.',
                'paths' => [
                    ['title' => 'Pitch a story', 'description' => 'Tell us about a champion, a hamlet or a quiet shift', 'href' => 'contact'],
                    ['title' => 'Send a 60-second video', 'description' => 'Phone-recorded, in your own dialect, in your own light', 'href' => 'contact'],
                    ['title' => 'Read the case-study archive', 'description' => 'Audit-grade reports, mid-lines & verified outcomes', 'href' => 'knowledge-hub'],
                    ['title' => 'Find a campaign to walk with', 'description' => 'Six campaigns, twenty-three districts, one quarter', 'href' => 'campaigns'],
                ],
            ], 10),
        ];
    }

    private function eventsPageSections(): array
    {
        return [
            $this->section('hero', 'hero', [
                'chapter' => '05',
                'title' => 'When the work has <em>a date.</em>',
                'lede' => 'Workshops, conferences, webinars and field bootcamps — open to volunteers, partner organisations and curious newcomers. <b>Register early. Show up early. Stay late.</b>',
                'meta_rows' => [
                    ['label' => 'This quarter', 'value' => '<b>4</b> upcoming · <b>3</b> workshops · <b>4</b> webinars'],
                    ['label' => 'Next event', 'value' => '<a href="#ev-feature" class="ev-hero-meta-link">Webinar — SBC at scale, May 22 →</a>'],
                    ['label' => 'Today', 'value' => 'Saturday, May 9, 2026'],
                ],
                'featured' => [
                    'day' => '12', 'month' => 'JUN', 'year' => '2026',
                    'tag' => 'Conference · 3-day',
                    'title' => 'State SBC Summit 2026',
                    'meta' => [
                        'Pt. R.S.S. Univ., Raipur',
                        '9:30 AM – 6:00 PM, Jun 12–14',
                        '320 / 400 seats',
                    ],
                    'status' => 'Registration open · 80 seats left',
                    'register_href' => '#ev-register-1',
                ],
            ], 3),
            $this->section('board', 'board', [
                'chapter' => '06',
                'title' => 'A month at <em>a glance</em>, a year on <em>a string.</em>',
                'subtitle' => 'Click a date on the calendar to jump to the matching event. Switch between upcoming and past events.',
                'calendar' => [
                    'month' => 'June 2026',
                    'event_count' => 5,
                    'days' => [
                        ['day' => 1, 'type' => 'empty'],
                        ['day' => 2, 'type' => 'empty'],
                        ['day' => 3, 'type' => 'has', 'date' => '2026-06-03', 'dot' => 'workshop'],
                        ['day' => 4, 'type' => 'has', 'date' => '2026-06-03', 'dot' => 'workshop'],
                        ['day' => 5, 'type' => 'has', 'date' => '2026-06-03', 'dot' => 'workshop'],
                        ['day' => 6, 'type' => 'has', 'date' => '2026-06-03', 'dot' => 'workshop'],
                        ['day' => 7, 'type' => 'has', 'date' => '2026-06-03', 'dot' => 'workshop'],
                        ['day' => 8, 'type' => 'empty'],
                        ['day' => 9, 'type' => 'empty'],
                        ['day' => 10, 'type' => 'empty'],
                        ['day' => 11, 'type' => 'empty'],
                        ['day' => 12, 'type' => 'has-big', 'date' => '2026-06-12', 'dot' => 'conf'],
                        ['day' => 13, 'type' => 'has-big', 'date' => '2026-06-12', 'dot' => 'conf'],
                        ['day' => 14, 'type' => 'has-big', 'date' => '2026-06-12', 'dot' => 'conf'],
                        ['day' => 15, 'type' => 'empty'],
                        ['day' => 16, 'type' => 'empty'],
                        ['day' => 17, 'type' => 'empty'],
                        ['day' => 18, 'type' => 'empty'],
                        ['day' => 19, 'type' => 'has', 'date' => '2026-06-19', 'dot' => 'webinar'],
                        ['day' => 20, 'type' => 'empty'],
                        ['day' => 21, 'type' => 'empty'],
                        ['day' => 22, 'type' => 'empty'],
                        ['day' => 23, 'type' => 'empty'],
                        ['day' => 24, 'type' => 'has', 'date' => '2026-06-24', 'dot' => 'workshop'],
                        ['day' => 25, 'type' => 'has', 'date' => '2026-06-24', 'dot' => 'workshop'],
                        ['day' => 26, 'type' => 'has', 'date' => '2026-06-24', 'dot' => 'workshop'],
                        ['day' => 27, 'type' => 'empty'],
                        ['day' => 28, 'type' => 'has', 'date' => '2026-06-28', 'dot' => 'workshop'],
                        ['day' => 29, 'type' => 'empty'],
                        ['day' => 30, 'type' => 'empty'],
                        ['day' => 1, 'type' => 'out'],
                        ['day' => 2, 'type' => 'out'],
                        ['day' => 3, 'type' => 'out'],
                        ['day' => 4, 'type' => 'out'],
                        ['day' => 5, 'type' => 'out'],
                    ],
                ],
                'timeline' => [
                    ['status' => 'upcoming', 'date' => '2026-05-22', 'day' => '22', 'month' => 'MAY', 'tag' => 'Webinar · Live', 'tag_type' => 'webinar', 'title' => 'SBC at scale: counselling 50,000 adolescents', 'description' => 'A 90-minute live webinar with three master trainers from Bilaspur, Bastar and Surguja.', 'link' => '#', 'link_text' => 'Register · 4:00 PM IST →'],
                    ['status' => 'upcoming', 'date' => '2026-06-03', 'day' => '03', 'month' => 'JUN', 'tag' => 'Workshop · 5-day', 'tag_type' => 'workshop', 'title' => 'Master Trainer Programme — Cohort 14', 'description' => 'Five days of curriculum design, role-plays, field visits and a closing pitch on counselling-at-scale.', 'link' => '#', 'link_text' => 'Apply by May 28 →'],
                    ['status' => 'upcoming', 'date' => '2026-06-12', 'day' => '12', 'month' => 'JUN', 'featured' => true, 'tag' => 'Conference · 3-day', 'tag_type' => 'conf', 'title' => 'State SBC Summit 2026', 'description' => 'Three days, six tracks, fourteen partner organisations. Plenary by the Hon\'ble State Health Secretary.', 'link' => '#ev-register-1', 'link_text' => 'Register · 80 seats left →'],
                    ['status' => 'today', 'label' => 'TODAY · MAY 9'],
                    ['status' => 'past', 'date' => '2026-04-18', 'day' => '18', 'month' => 'APR', 'tag' => 'Conference · Completed', 'tag_type' => 'conf', 'title' => 'Adolescent Voices Conclave', 'description' => '240 adolescents, 18 districts, 1 day. Outcomes recorded and published in the alliance journal.', 'link' => '#ev-reports', 'link_text' => 'View report →'],
                    ['status' => 'past', 'date' => '2026-03-14', 'day' => '14', 'month' => 'MAR', 'tag' => 'Webinar · Replay', 'tag_type' => 'webinar', 'title' => 'PVTG nutrition: lessons from four hamlets', 'description' => 'Phulwa Bai, Sheela Bai and three frontline workers in conversation with the alliance research team.', 'link' => '#', 'link_text' => 'Watch replay →'],
                    ['status' => 'past', 'date' => '2026-03-06', 'day' => '06', 'month' => 'MAR', 'tag' => 'Conference · Completed', 'tag_type' => 'conf', 'title' => 'Gender & Behaviour Conference', 'description' => 'Hosted in Balod — the district that became India\'s first child-marriage-free district.', 'link' => '#ev-reports', 'link_text' => 'View recap →'],
                ],
            ], 4),
            $this->section('upcoming', 'upcoming', [
                'chapter' => '07',
                'title' => 'Pencil it in. <em>Block</em> the day.',
                'subtitle' => 'Four events open for registration. Lunch, training material and travel reimbursement (within state) included for all alliance members.',
                'featured' => [
                    'image' => 'https://www.chhattisgarhabc.org/stories/uploads/event/427748.png',
                    'tag' => 'Featured · Next event',
                    'meta' => 'May 22, 2026 · 4:00 PM IST · Online',
                    'title' => 'SBC at scale: counselling <em>50,000</em> adolescents',
                    'description' => 'A 90-minute live webinar with three master trainers from Bilaspur, Bastar and Surguja, sharing the playbook that got 50,000 adolescents through quarterly health counselling. Q&A with the alliance research lead. Replay sent to all registrants.',
                    'pills' => [
                        ['label' => 'Format', 'value' => 'Webinar · Zoom'],
                        ['label' => 'Duration', 'value' => '90 min · live + replay'],
                        ['label' => 'Speakers', 'value' => '3 master trainers · 1 lead'],
                        ['label' => 'Capacity', 'value' => '500 / 1,000'],
                        ['label' => 'Cost', 'value' => 'Free · alliance members'],
                    ],
                    'fill_note' => 'Filling fast — 500 of 1,000 registered',
                ],
                'cards' => [
                    ['classes' => 'ev-card--conf', 'day' => '12', 'month' => 'JUN', 'tag' => 'Conference · 3-day', 'tag_type' => 'conf', 'title' => 'State SBC Summit 2026', 'description' => 'Six tracks, fourteen partners, one state-level showcase of behaviour-change programmes.', 'meta' => ['Pt. R.S.S. Univ., Raipur', 'Jun 12–14 · 9:30 AM', '320 / 400 seats'], 'status' => 'Registration open', 'status_class' => 'ev-card-status--open'],
                    ['classes' => 'ev-card--workshop', 'aos_delay' => 80, 'day' => '28', 'month' => 'JUN', 'tag' => 'Workshop · 1-day', 'tag_type' => 'workshop', 'title' => 'Adolescent Health Bootcamp', 'description' => 'A field-day in Bilaspur — counselling techniques, role-play, and a live 1:1 lab with kishori groups.', 'meta' => ['Govt. Higher Sec., Bilaspur', 'Jun 28 · 9:00 AM', '40 / 60 seats'], 'status' => 'Filling fast · 20 left', 'status_class' => 'ev-card-status--filling'],
                    ['classes' => 'ev-card--webinar', 'aos_delay' => 160, 'day' => '19', 'month' => 'JUN', 'tag' => 'Webinar · Live', 'tag_type' => 'webinar', 'title' => 'Father engagement: deep-dive', 'description' => 'Why anganwadi attendance for fathers tripled in Kabirdham — and what didn\'t work in the first three months.', 'meta' => ['Online · Zoom', 'Jun 19 · 6:30 PM IST', '0 / 500 capacity'], 'status' => 'Registration open', 'status_class' => 'ev-card-status--open'],
                ],
            ], 5),
            $this->section('workshops', 'workshops', [
                'chapter' => '08',
                'title' => 'Multi-day labs <em>that change the room.</em>',
                'subtitle' => 'Curriculum-led, residential or hybrid. Each programme blends field work, peer review, and a final pitch — graded by the alliance.',
                'programs' => [
                    [
                        'tag' => 'Cohort 14 · 5 days',
                        'title' => 'Master Trainer Programme',
                        'description' => 'The flagship — for SBC practitioners who will train 200+ frontline workers in their home district.',
                        'meta' => [
                            ['label' => 'Dates', 'value' => 'Jun 3 – Jun 7, 2026'],
                            ['label' => 'Where', 'value' => 'Alliance HQ, Raipur · residential'],
                            ['label' => 'Cost', 'value' => 'Free · seats by application'],
                            ['label' => 'Apply by', 'value' => 'May 28, 2026'],
                        ],
                        'days' => [
                            ['day' => 'Day 1', 'label' => 'Foundations of behaviour design'],
                            ['day' => 'Day 2', 'label' => 'Counselling craft & role-play'],
                            ['day' => 'Day 3', 'label' => 'Field day — Bilaspur'],
                            ['day' => 'Day 4', 'label' => 'Curriculum build & peer review'],
                            ['day' => 'Day 5', 'label' => 'Pitch · graded by 3 evaluators'],
                        ],
                    ],
                ],
            ], 6),
            $this->section('webinars', 'webinars', [
                'chapter' => '09',
                'title' => 'Online sessions, <em>open replays.</em>',
                'subtitle' => 'Free for everyone, recorded for the rest of us. Subscribe to be notified when the next one goes live.',
                'items' => [
                    ['classes' => 'ev-web-card--live', 'poster' => 'https://www.chhattisgarhabc.org/stories/uploads/event/427748.png', 'badge' => '● Live · May 22', 'badge_class' => 'ev-web-card-badge--live', 'meta' => '90 min · 4:00 PM IST · Zoom', 'title' => 'SBC at scale', 'description' => 'Counselling 50,000 adolescents — three master trainers in conversation.', 'link_text' => 'Register →'],
                ],
            ], 7),
            $this->section('past', 'past', [
                'chapter' => '10',
                'title' => 'What we did, <em>what we learned.</em>',
                'subtitle' => 'Every alliance event is followed by a recap report — outcomes, attendance, audited learnings.',
                'cards' => [
                    ['image' => 'https://www.chhattisgarhabc.org/stories/uploads/event/427748.png', 'meta' => 'Apr 18, 2026 · Bilaspur · Conference', 'title' => 'Adolescent Voices Conclave', 'description' => '240 adolescents, 18 districts, 1 day. The largest under-18 conclave the alliance has hosted.', 'stats' => [['value' => '240', 'label' => 'attendees'], ['value' => '18', 'label' => 'districts'], ['value' => '4.7', 'suffix' => '/5', 'label' => 'satisfaction']]],
                    ['image' => 'https://www.chhattisgarhabc.org/stories/uploads/story/696641b1e3114.png', 'meta' => 'Mar 06, 2026 · Balod · Conference', 'title' => 'Gender & Behaviour Conference', 'description' => 'Hosted by India\'s first child-marriage-free district. Outcome paper published in Apr 2026.', 'stats' => [['value' => '180', 'label' => 'attendees'], ['value' => '9', 'label' => 'partner orgs'], ['value' => '1', 'label' => 'paper published']], 'aos_delay' => 100],
                    ['image' => 'https://www.chhattisgarhabc.org/stories/uploads/story/67909992e8e60.png', 'meta' => 'Feb 14, 2026 · Raipur · Conference', 'title' => 'Annual Alliance Meet 2025', 'description' => 'The closing convening for FY 2025 — 38 partner orgs, the year\'s report card, and the cohort plan for 2026.', 'stats' => [['value' => '312', 'label' => 'attendees'], ['value' => '38', 'label' => 'partner orgs'], ['value' => '14', 'label' => 'tracks']], 'aos_delay' => 200],
                ],
            ], 8),
            $this->section('gallery', 'gallery', [
                'chapter' => '11',
                'title' => 'Photographs from <em>the room.</em>',
                'subtitle' => 'Click any photograph to expand. Use ← → to step through.',
                'tiles' => [
                    ['class' => 'ev-gal-tile--1', 'index' => 0, 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/event/427748.png', 'label' => 'Adolescent Voices Conclave · Bilaspur, April 2026'],
                    ['class' => 'ev-gal-tile--2', 'index' => 1, 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/story/696641b1e3114.png', 'label' => 'Gender Conference · Balod, March 2026'],
                    ['class' => 'ev-gal-tile--3', 'index' => 2, 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/story/69a086247a963.png', 'label' => 'Master Trainer Cohort 13 · Raipur, March 2026'],
                    ['class' => 'ev-gal-tile--4', 'index' => 3, 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/story/67ce9ac3493da.png', 'label' => 'PVTG nutrition workshop · Surguja, February 2026'],
                    ['class' => 'ev-gal-tile--5', 'index' => 4, 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/story/67909992e8e60.png', 'label' => 'Sarpanch Leadership Lab · Balod, February 2026'],
                    ['class' => 'ev-gal-tile--6', 'index' => 5, 'image' => 'https://www.chhattisgarhabc.org/images/home/1.jpg', 'label' => 'Field day · Bastar, January 2026'],
                    ['class' => 'ev-gal-tile--7', 'index' => 6, 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/event/427748.png', 'label' => 'Annual Alliance Meet · Raipur, February 2026'],
                    ['class' => 'ev-gal-tile--8', 'index' => 7, 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/story/696641b1e3114.png', 'label' => 'Pledge ceremony · Balod, March 2026'],
                    ['class' => 'ev-gal-tile--9', 'index' => 8, 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/story/69a086247a963.png', 'label' => 'Closing remarks · Raipur, February 2026'],
                ],
                'total' => 9,
            ], 9),
            $this->section('reports', 'reports', [
                'chapter' => '12',
                'title' => 'Take it home. <em>Read it slowly.</em>',
                'subtitle' => 'Recap reports, presentation decks, attendee lists, and the audit-grade outcome papers — all open-licensed.',
                'items' => [
                    ['icon' => 'PDF', 'title' => 'Adolescent Voices Conclave — Full report', 'meta' => 'Published Apr 28, 2026 · 42 pages · 4.8 MB'],
                ],
            ], 10),
            $this->section('cta', 'cta', [
                'chapter' => '13',
                'title' => 'Want to <em>host an event</em> with the alliance?',
                'lede' => 'Bring your district, your subject expertise or your venue. We\'ll bring the curriculum, the speakers, the registrations and the recap report. Submit a request — we open one new partner-led event slot every quarter.',
                'paths' => [
                    ['title' => 'Submit an event request', 'description' => 'For panchayats, NGOs, CSR partners, schools', 'href' => 'contact'],
                    ['title' => 'Subscribe to event notifications', 'description' => 'One email a month · skip if registration is full', 'href' => 'contact'],
                    ['title' => 'Browse the resource hub', 'description' => 'Open guides, toolkits, reports and downloadable resources', 'href' => 'knowledge-hub'],
                    ['title' => 'Find a campaign to walk with', 'description' => 'Six campaigns, twenty-three districts, one quarter', 'href' => 'campaigns'],
                ],
            ], 11),
        ];
    }

    private function knowledgeHubPageSections(): array
    {
        return [
            $this->section('hero', 'hero', [
                'chapter' => '06',
                'title' => 'Take it. Translate it. <em>Tell it again.</em>',
                'lede' => 'Six shelves. Two hundred and forty resources. <b>One promise:</b> everything is open-licensed, every PDF has a Canva twin, and every dataset comes with the methodology. Borrow what works. Remix what doesn\'t.',
                'cta_primary' => ['label' => 'Search the library', 'href' => '#kh-search-input'],
                'cta_secondary' => ['label' => 'View Canva editables', 'href' => '#kh-canva'],
                'stats' => [
                    ['value' => 240, 'suffix' => '+', 'label' => 'resources in the library'],
                    ['value' => 65, 'suffix' => 'k', 'label' => 'downloads this year'],
                    ['value' => 18, 'label' => 'languages & dialects'],
                    ['value' => 14, 'label' => 'partner contributors'],
                ],
            ], 3),
            $this->section('search_bar', 'search_bar', [
                'placeholder' => 'Search by title, author, district, keyword…',
                'total' => 14,
                'filters' => [
                    ['value' => 'all', 'label' => 'All', 'dot' => null],
                    ['value' => 'research', 'label' => 'Research', 'dot' => 'var(--terracotta)'],
                    ['value' => 'iec', 'label' => 'IEC Materials', 'dot' => 'var(--ochre)'],
                    ['value' => 'toolkit', 'label' => 'Toolkits', 'dot' => 'var(--teal)'],
                    ['value' => 'guide', 'label' => 'Guides', 'dot' => 'var(--leaf)'],
                    ['value' => 'report', 'label' => 'Reports', 'dot' => 'var(--indigo)'],
                    ['value' => 'data', 'label' => 'Data Resources', 'dot' => 'var(--saffron)'],
                ],
            ], 4),
            $this->section('featured', 'featured', [
                'id' => 'adolescent-health-outcomes',
                'category' => 'research',
                'keywords' => 'adolescent health outcomes mid-line study research report 2026',
                'cover' => 'https://www.chhattisgarhabc.org/stories/uploads/event/427748.png',
                'flag' => 'Featured · Mid-line study',
                'format' => 'PDF',
                'title' => 'Adolescent Health Outcomes 2026 — <em>Mid-line study</em>',
                'description' => 'A 96-page mid-line study across 5 districts. Indicators, methodology, ethics statement and a foreword by the State Health Secretary. Co-authored with Pt. R.S.S. University.',
                'meta' => [
                    ['value' => '96', 'label' => 'pages'],
                    ['value' => '4.8 MB', 'label' => 'file size'],
                    ['value' => '2,847', 'label' => 'downloads'],
                    ['value' => '2', 'label' => 'languages'],
                ],
                'detail' => [
                    'title' => 'Adolescent Health Outcomes 2026 — Mid-line study',
                    'description' => 'A 96-page mid-line study of adolescent health outcomes across 5 districts, co-authored with the Pt. R.S.S. University research team. Includes raw indicators, methodology, ethics statement and a foreword by the State Health Secretary.',
                    'pages' => '96',
                    'size' => '4.8 MB',
                    'langs' => 'English · Hindi',
                    'published' => 'Apr 28, 2026',
                    'downloads' => '2,847',
                    'canva' => false,
                ],
            ], 5),
            $this->section('grid', 'grid', [
                'chapter' => '07',
                'title' => 'Six shelves. <em>One library.</em>',
                'subtitle' => 'Browse all 14 of this month\'s resources, or use the filter bar above to narrow down by shelf.',
                'resources' => $this->knowledgeHubResources(),
            ], 6),
            $this->section('canva', 'canva', [
                'chapter' => '08',
                'title' => 'Made for <em>your</em> district. <em>Your</em> dialect. <em>Your</em> Friday.',
                'lede' => 'Every poster, talk-show card, and notebook in our IEC shelf has a Canva twin. Duplicate it, swap your district name, your sarpanch\'s signature, your colour palette — and send it to print. No design skills required.',
                'button' => ['label' => 'Open the Canva folder', 'href' => '#'],
                'steps' => [
                    ['num' => '01', 'title' => 'Duplicate', 'description' => 'Click the design — it copies into your own Canva account in one click.'],
                    ['num' => '02', 'title' => 'Edit text & brand', 'description' => 'Swap the district name, the language, the colour palette, the sarpanch\'s signature.'],
                    ['num' => '03', 'title' => 'Download & print', 'description' => 'Export PDF print-ready (300 DPI, CMYK) — send straight to any local press.'],
                    ['num' => '04', 'title' => 'Send back what you made', 'description' => 'Tag us — your edit might become the next pinned template in the alliance library.'],
                ],
            ], 7),
        ];
    }

    private function knowledgeHubResources(): array
    {
        return [
            ['id' => 'father-engagement-longitudinal', 'category' => 'research', 'keywords' => 'father engagement longitudinal study research kabirdham', 'cover' => 'https://www.chhattisgarhabc.org/stories/uploads/story/696641b1e3114.png', 'format' => 'PDF', 'title' => 'Father Engagement — 4-year longitudinal', 'description' => '1,200 fathers · 12 panchayats · Kabirdham. Method, instruments, full anonymised dataset.', 'meta' => ['48 pages', '3.2 MB', '1,924 ↓'], 'canva' => false, 'pages' => '48', 'size' => '3.2 MB', 'langs' => 'English · Hindi', 'published' => 'Mar 14, 2026', 'downloads' => '1,924'],
            ['id' => 'pvtg-nutrition-audit', 'category' => 'research', 'keywords' => 'pvtg nutrition hamlet audit research surguja', 'cover' => 'https://www.chhattisgarhabc.org/stories/uploads/story/67ce9ac3493da.png', 'format' => 'PDF', 'title' => 'PVTG Nutrition Hamlet Audit', 'description' => '18 hamlets · Surguja. Audited weight-for-age data, field-team protocol, ethics statement.', 'meta' => ['32 pages', '2.1 MB', '1,318 ↓'], 'canva' => false, 'pages' => '32', 'size' => '2.1 MB', 'langs' => 'English · Chhattisgarhi', 'published' => 'Feb 04, 2026', 'downloads' => '1,318'],
            ['id' => 'adolescent-poster-pack', 'category' => 'iec', 'keywords' => 'adolescent awareness poster pack iec hindi chhattisgarhi canva', 'cover' => 'https://www.chhattisgarhabc.org/stories/uploads/story/67909992e8e60.png', 'format' => 'CANVA', 'title' => 'Adolescent Awareness — Poster Pack', 'description' => '12 A3 posters · 3 languages. Duplicate, replace your district name, print at any local press.', 'meta' => ['12 designs', '3 languages', '6,402 ↓'], 'canva' => true, 'canva_url' => '#', 'pages' => '12', 'size' => '14 designs · A3', 'langs' => 'Hindi · Chhattisgarhi · English', 'published' => 'Apr 12, 2026', 'downloads' => '6,402', 'ribbon' => true],
            ['id' => 'iron-deficiency-cards', 'category' => 'iec', 'keywords' => 'iron deficiency talk show cards iec adolescent', 'cover' => 'https://www.chhattisgarhabc.org/stories/uploads/story/69a086247a963.png', 'format' => 'PDF', 'title' => 'Iron Deficiency Talk-Show Cards', 'description' => '24 cards · prompt + behaviour hook + follow-up. Designed by Master Trainer Cohort 12.', 'meta' => ['24 cards', '6.4 MB', '3,041 ↓'], 'canva' => false, 'pages' => '24', 'size' => '6.4 MB', 'langs' => 'Hindi · Chhattisgarhi', 'published' => 'Mar 22, 2026', 'downloads' => '3,041'],
            ['id' => 'fathers-notebook', 'category' => 'iec', 'keywords' => 'father notebook print ready a5 canva iec', 'cover' => 'https://www.chhattisgarhabc.org/stories/uploads/event/427748.png', 'format' => 'CANVA', 'title' => 'Father\'s Notebook — print-ready', 'description' => 'A5 notebook for anganwadi visits. Handwritten Hindi · illustrated · districts can re-brand.', 'meta' => ['32 pages', 'A5 size', '4,560 ↓'], 'canva' => true, 'canva_url' => '#', 'pages' => '32', 'size' => 'A5 · 32 pp', 'langs' => 'Hindi', 'published' => 'Jan 18, 2026', 'downloads' => '4,560', 'ribbon' => true],
            ['id' => 'master-trainer-toolkit', 'category' => 'toolkit', 'keywords' => 'master trainer curriculum toolkit zip', 'cover' => 'https://www.chhattisgarhabc.org/stories/uploads/story/696641b1e3114.png', 'format' => 'ZIP', 'title' => 'Master Trainer Curriculum Toolkit', 'description' => '5-day programme — facilitator guide · slides · scripts · rubrics. 4 modules · 6 h video.', 'meta' => ['4 modules', '68 MB', '1,128 ↓'], 'canva' => false, 'pages' => '4 modules', 'size' => '68 MB', 'langs' => 'English · Hindi', 'published' => 'Jan 11, 2026', 'downloads' => '1,128'],
            ['id' => 'sarpanch-starter-pack', 'category' => 'toolkit', 'keywords' => 'sarpanch sbc starter pack panchayat zip', 'cover' => 'https://www.chhattisgarhabc.org/stories/uploads/story/67909992e8e60.png', 'format' => 'ZIP', 'title' => 'Sarpanch SBC Starter Pack', 'description' => 'Slides + scripts + gram-sabha agenda + FY budget. Start an SBC notebook in 30 minutes.', 'meta' => ['6 files', '42 MB', '2,318 ↓'], 'canva' => true, 'canva_url' => '#', 'pages' => '6 files', 'size' => '42 MB', 'langs' => 'Hindi · Chhattisgarhi', 'published' => 'Feb 28, 2026', 'downloads' => '2,318', 'ribbon' => true],
            ['id' => 'counselling-lab-kit', 'category' => 'toolkit', 'keywords' => 'counselling lab kit adolescent health worksheets', 'cover' => 'https://www.chhattisgarhabc.org/stories/uploads/story/67ce9ac3493da.png', 'format' => 'ZIP', 'title' => 'Counselling Lab Kit — Adolescent Health', 'description' => '14 worksheets · 7 role-play scripts · 1 facilitator\'s grid. Field-day ready.', 'meta' => ['22 files', '22 MB', '1,847 ↓'], 'canva' => false, 'pages' => '22 files', 'size' => '22 MB', 'langs' => 'Hindi', 'published' => 'Mar 30, 2026', 'downloads' => '1,847'],
            ['id' => 'fathers-anganwadi-day', 'category' => 'guide', 'keywords' => 'father anganwadi day guide how to', 'cover' => 'https://www.chhattisgarhabc.org/stories/uploads/event/427748.png', 'format' => 'PDF', 'title' => 'How to run a Father\'s Anganwadi Day', 'description' => 'Pre-event checklist · day-of script · post-event grid · invitation card template.', 'meta' => ['12 pages', '1.6 MB', '3,612 ↓'], 'canva' => false, 'pages' => '12', 'size' => '1.6 MB', 'langs' => 'Hindi · English', 'published' => 'Feb 14, 2026', 'downloads' => '3,612'],
            ['id' => 'silence-map-method', 'category' => 'guide', 'keywords' => 'counselling teen girls silence map method guide', 'cover' => 'https://www.chhattisgarhabc.org/stories/uploads/story/696641b1e3114.png', 'format' => 'PDF', 'title' => 'Counselling Teen Girls — Silence-Map Method', 'description' => 'The tool that gets quieter girls in a group to surface the questions no one else asks.', 'meta' => ['16 pages', '2.8 MB', '2,206 ↓'], 'canva' => false, 'pages' => '16', 'size' => '2.8 MB', 'langs' => 'Hindi · English', 'published' => 'Apr 04, 2026', 'downloads' => '2,206'],
            ['id' => 'annual-alliance-report-2025', 'category' => 'report', 'keywords' => 'annual alliance report fy 2025 report', 'cover' => 'https://www.chhattisgarhabc.org/stories/uploads/story/67909992e8e60.png', 'format' => 'PDF', 'title' => 'FY 2025 Annual Alliance Report', 'description' => '38 partners · 23 districts · 64 campaigns. Every number traceable to source.', 'meta' => ['78 pages', '8.4 MB', '4,128 ↓'], 'canva' => false, 'pages' => '78', 'size' => '8.4 MB', 'langs' => 'English · Hindi', 'published' => 'Feb 22, 2026', 'downloads' => '4,128'],
            ['id' => 'gender-outcome-paper', 'category' => 'report', 'keywords' => 'gender conference outcome paper balod report', 'cover' => 'https://www.chhattisgarhabc.org/stories/uploads/story/696641b1e3114.png', 'format' => 'PDF', 'title' => 'Gender Conference 2026 — Outcome Paper', 'description' => 'Peer-reviewed paper from the Balod Gender Conference. India\'s first child-marriage-free district.', 'meta' => ['28 pages', '2.4 MB', '1,634 ↓'], 'canva' => false, 'pages' => '28', 'size' => '2.4 MB', 'langs' => 'English', 'published' => 'Apr 18, 2026', 'downloads' => '1,634'],
            ['id' => 'open-sbc-dataset', 'category' => 'data', 'keywords' => 'open sbc dataset 2020 2026 csv data dashboard', 'cover' => 'https://www.chhattisgarhabc.org/stories/uploads/story/67ce9ac3493da.png', 'format' => 'CSV', 'title' => 'Open SBC Dataset 2020–2026', 'description' => '250,000 rows · 23 districts · 6 years. CSV + Datawrapper dashboard + codebook.', 'meta' => ['250k rows', '34 MB', '892 ↓'], 'canva' => false, 'pages' => '250k rows', 'size' => '34 MB', 'langs' => 'English', 'published' => 'Apr 30, 2026', 'downloads' => '892'],
            ['id' => 'district-baseline-indicators', 'category' => 'data', 'keywords' => 'district level baseline indicators csv data', 'cover' => 'https://www.chhattisgarhabc.org/images/home/1.jpg', 'format' => 'CSV', 'title' => 'District-level Baseline Indicators', 'description' => '23 districts · anaemia, child marriage, AWW reach, father attendance. The reference set.', 'meta' => ['23 districts', '6 MB', '1,205 ↓'], 'canva' => false, 'pages' => '23 districts', 'size' => '6 MB', 'langs' => 'English', 'published' => 'Jan 04, 2026', 'downloads' => '1,205'],
        ];
    }

    private function getInvolvedPageSections(): array
    {
        return [
            $this->section('hero', 'hero', [
                'chapter' => '07',
                'title' => 'Roll up your sleeves. <em>The state is changing.</em>',
                'lede' => 'We have <b>four ways in</b> — pick the one that fits your Friday. <span class="gi-hand">Some change a campaign.</span> Some change a career. The good ones change both.',
                'cta_primary' => ['label' => 'Find your fit', 'href' => '#gi-join'],
                'cta_secondary' => ['label' => 'Contact us', 'href' => 'contact', 'route' => true],
                'pathways' => [
                    ['num' => '01', 'label' => 'Volunteer', 'hint' => 'Field support', 'anchor' => 'gi-volunteer'],
                    ['num' => '02', 'label' => 'Intern', 'hint' => 'Learn on the job', 'anchor' => 'gi-intern'],
                    ['num' => '03', 'label' => 'Fellowship', 'hint' => 'Structured programme', 'anchor' => 'gi-fellow'],
                    ['num' => '04', 'label' => 'Organisation', 'hint' => 'CSO / NGO / firm', 'anchor' => 'gi-partner'],
                ],
            ], 3),
            $this->section('join', 'join', [
                'head' => [
                    'chapter' => '08',
                    'chapter_label' => 'Get Involved',
                    'title' => 'Choose your <em>pathway.</em>',
                    'lede' => 'Four ways to join the alliance — pick the role that fits you and complete the registration form.',
                ],
                'options' => [
                    [
                        'slug' => 'volunteer',
                        'prefix' => 'Engage with us as',
                        'title' => 'an Individual Volunteer',
                        'description' => 'Support field outreach, local events and community learning across districts.',
                        'cta_label' => 'Register',
                        'pathway' => 'volunteer',
                        'icon' => 'volunteer',
                        'tone' => 'ochre',
                    ],
                    [
                        'slug' => 'intern',
                        'prefix' => 'Engage with us as',
                        'title' => 'an Intern',
                        'description' => 'Learn on the job through communications, research and programme support roles.',
                        'cta_label' => 'Apply',
                        'cta_url' => '/get-involved/register/intern',
                        'icon' => 'intern',
                        'tone' => 'leaf',
                    ],
                    [
                        'slug' => 'fellow',
                        'prefix' => 'Engage with us as',
                        'title' => 'a Fellow (Fellowship)',
                        'description' => 'Take on a structured fellowship to deepen SBC practice and district-level impact.',
                        'cta_label' => 'Apply',
                        'cta_url' => '/get-involved/register/fellowship',
                        'icon' => 'fellow',
                        'tone' => 'terracotta',
                    ],
                    [
                        'slug' => 'partner',
                        'prefix' => 'Partner with us as',
                        'title' => 'a CSO / NGO / Firm / Organization',
                        'description' => 'Co-design campaigns, share resources and scale behaviour change with the alliance.',
                        'cta_label' => 'Partner with us',
                        'pathway' => 'partner',
                        'icon' => 'partner',
                        'tone' => 'indigo',
                    ],
                ],
            ], 4),
            $this->section('pathways', 'pathways', [
                'chapter' => '08',
                'title' => 'Six doors. <em>Walk through one.</em>',
                'subtitle' => 'Each pathway has its own time commitment, its own kind of person, its own set of perks. Read the one that pulls you — then click Apply.',
            ], 5),
            $this->section('form', 'form', [
                'chapter' => '11',
                'title' => 'One form. <em>All six pathways.</em>',
                'lede' => 'Tell us which door you\'d like to walk through, who you are, and a paragraph on why. We get back within <b>3 working days</b> — every time, every form.',
            ], 6),
        ];
    }

}
