<?php

$legacyBase = rtrim((string) env('LEGACY_ASSET_URL', 'https://www.chhattisgarhabc.org'), '/');

return [
    'legacy_base' => $legacyBase,

    'presets' => [
        'story' => [
            'folders' => ['uploads/story', 'storage/story', 'uploads/blogs', 'uploads/stories'],
            'upload_folder' => 'uploads/story',
            'fallback' => $legacyBase.'/images/home/1.jpg',
            'remote_pattern' => $legacyBase.'/stories/uploads/story/{file}',
        ],
        'event' => [
            'folders' => ['uploads/events', 'storage/event'],
            'upload_folder' => 'uploads/events',
            'fallback' => '',
            'remote_pattern' => $legacyBase.'/stories/uploads/event/{file}',
        ],
        'membership' => [
            'folders' => ['uploads/memberships', 'storage/logos'],
            'upload_folder' => 'uploads/memberships',
            'fallback' => '',
        ],
        'insights' => [
            'folders' => ['uploads/insights', 'storage/insights'],
            'upload_folder' => 'uploads/insights',
            'fallback' => '',
        ],
        'sbc-pool' => [
            'folders' => ['uploads/sbc-pool', 'storage/sbc-pool'],
            'upload_folder' => 'uploads/sbc-pool',
            'fallback' => '',
        ],
        'banner' => [
            'folders' => ['uploads/banners'],
            'upload_folder' => 'uploads/banners',
            'fallback' => '',
        ],
        'learning' => [
            'folders' => ['uploads/learning'],
            'upload_folder' => 'uploads/learning',
            'fallback' => '',
        ],
        'program' => [
            'folders' => ['uploads/programs', 'storage/programs'],
            'upload_folder' => 'uploads/programs',
            'fallback' => $legacyBase.'/images/home/1.jpg',
        ],
        'home-slider' => [
            'folders' => ['uploads/home-slider'],
            'upload_folder' => 'uploads/home-slider',
            'fallback' => '',
        ],
        'user' => [
            'folders' => ['uploads/users'],
            'upload_folder' => 'uploads/users',
            'fallback' => '',
        ],
        'site' => [
            'folders' => ['uploads/site'],
            'upload_folder' => 'uploads/site',
            'fallback' => '',
        ],
        'story-draft' => [
            'folders' => ['uploads/stories'],
            'upload_folder' => 'uploads/stories',
            'fallback' => '',
        ],
        'story-content' => [
            'folders' => ['uploads/stories/content'],
            'upload_folder' => 'uploads/stories/content',
            'fallback' => '',
        ],
    ],

    'home_programs' => [
        'accents' => ['grad', 'orange', 'black'],
        'placeholders' => [
            $legacyBase.'/images/home/1.jpg',
            $legacyBase.'/images/home/2.jpg',
            $legacyBase.'/images/home/3.jpg',
            $legacyBase.'/images/home/4.jpg',
            $legacyBase.'/images/home/5.jpg',
        ],
        'defaults' => [
            [
                'title' => 'Bapi Na Uwat',
                'description' => 'Bapi Na Uwat is an innovative community-led SBC initiative launched in Dantewada by the district administration and UNICEF to reduce malnutrition and improve health behaviours in tribal communities.',
                'placeholder_index' => 0,
                'accent' => 'grad',
            ],
            [
                'title' => 'Yuvoday',
                'description' => 'Yuvoday is a youth-led volunteer movement launched in Chhattisgarh with support from district administrations and UNICEF to strengthen community participation and behaviour change.',
                'placeholder_index' => 1,
                'accent' => 'orange',
            ],
            [
                'title' => 'BijaDuteer',
                'description' => 'BijaDuteer is a youth volunteer initiative in Bijapur supported by the District Administration, UNICEF, and Chhattisgarh Agricon Samiti.',
                'placeholder_index' => 2,
                'accent' => 'black',
            ],
            [
                'title' => 'JAY HO!',
                'description' => 'JAY HO, the Jashpur Alliance of Youth for Hope and Opportunity, is a youth empowerment initiative in Jashpur launched by the District Administration and UNICEF.',
                'placeholder_index' => 3,
                'accent' => 'grad',
            ],
            [
                'title' => 'Learning Corners',
                'description' => 'The Learning Corner is a shared resource space under Alliance for Behaviour Change where IEC materials, training modules, toolkits, and campaign resources are made accessible.',
                'placeholder_index' => 4,
                'accent' => 'orange',
            ],
        ],
    ],
];
