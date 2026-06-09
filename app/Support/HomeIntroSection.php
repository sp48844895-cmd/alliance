<?php

namespace App\Support;

class HomeIntroSection
{
    private const ICONS = ['target', 'eye', 'users', 'chart'];

    private const DEFAULT_PILLARS = [
        [
            'num' => 'i.',
            'title' => 'Define the issue',
            'text' => 'Identify the specific health or social issue, gather data, and frame the problem before designing.',
            'icon' => 'target',
            'aos_delay' => null,
        ],
        [
            'num' => 'ii.',
            'title' => 'Apply SBC theory',
            'text' => 'Frame the programme using evidence-based behaviour-change theory — not assumptions.',
            'icon' => 'eye',
            'aos_delay' => 80,
        ],
        [
            'num' => 'iii.',
            'title' => 'Coordinate channels',
            'text' => 'Reach individuals, communities and policymakers — radio, print, video, and field outreach in sync.',
            'icon' => 'users',
            'aos_delay' => 160,
        ],
        [
            'num' => 'iv.',
            'title' => 'Measure & iterate',
            'text' => 'Track outcomes, share learnings across the alliance, and refine messaging every cycle.',
            'icon' => 'chart',
            'aos_delay' => 240,
        ],
    ];

    public static function build(array $section = []): array
    {
        $hashtag = $section['hashtag'] ?? null;
        if ($hashtag === null || $hashtag === '') {
            $hashtag = ltrim((string) ($section['chapter_label'] ?? 'SBCMatters'), '#');
        }

        $lede = $section['lede'] ?? $section['lede_html'] ?? 'A powerful approach that uses Strategic Communication and Community Engagement to shape behavior into practices. It goes beyond traditional advertisement, coordinating channels to measure and iterate.';

        return [
            'chapter_num' => $section['chapter_num'] ?? '02',
            'hashtag' => $hashtag,
            'lede' => strip_tags($lede),
            'pillars' => self::normalizePillars($section['pillars'] ?? null),
        ];
    }

    private static function normalizePillars(?array $cmsPillars): array
    {
        if (! is_array($cmsPillars) || $cmsPillars === []) {
            return self::DEFAULT_PILLARS;
        }

        $pillars = [];
        foreach (array_values($cmsPillars) as $index => $pillar) {
            if (! is_array($pillar)) {
                continue;
            }

            $default = self::DEFAULT_PILLARS[$index] ?? null;

            $pillars[] = [
                'num' => $pillar['num'] ?? ($default['num'] ?? ''),
                'title' => html_entity_decode(strip_tags($pillar['title'] ?? ($default['title'] ?? ''))),
                'text' => $pillar['text'] ?? ($default['text'] ?? ''),
                'icon' => $pillar['icon'] ?? self::ICONS[$index % count(self::ICONS)],
                'aos_delay' => array_key_exists('aos_delay', $pillar)
                    ? $pillar['aos_delay']
                    : ($default['aos_delay'] ?? $index * 80),
            ];
        }

        return $pillars !== [] ? $pillars : self::DEFAULT_PILLARS;
    }
}
