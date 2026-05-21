<?php

$legacyBase = rtrim((string) env('LEGACY_ASSET_URL', 'https://www.chhattisgarhabc.org'), '/');

return [
    'legacy_base' => $legacyBase,

    'presets' => [
        'story' => [
            'folders' => ['storage/story', 'uploads/blogs'],
            'upload_folder' => 'storage/story',
            'fallback' => $legacyBase.'/images/home/1.jpg',
        ],
        'event' => [
            'folders' => ['storage/event', 'uploads/events'],
            'upload_folder' => 'storage/event',
            'fallback' => '',
            'remote_pattern' => $legacyBase.'/stories/uploads/event/{file}',
        ],
        'membership' => [
            'folders' => ['uploads/memberships', 'storage/logos'],
            'upload_folder' => 'storage/logos',
            'fallback' => '',
        ],
        'insights' => [
            'folders' => ['storage/insights'],
            'upload_folder' => 'storage/insights',
            'fallback' => '',
        ],
        'sbc-pool' => [
            'folders' => ['storage/sbc-pool'],
            'upload_folder' => 'storage/sbc-pool',
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
        'user' => [
            'folders' => ['uploads/users'],
            'upload_folder' => 'uploads/users',
            'fallback' => '',
        ],
    ],
];
