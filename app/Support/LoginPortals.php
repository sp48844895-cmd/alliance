<?php

namespace App\Support;

class LoginPortals
{
    public static function order(): array
    {
        return ['guest', 'intern', 'fellow', 'ngo', 'admin'];
    }

    public static function slugs(): array
    {
        return self::order();
    }

    public static function types(): array
    {
        return [
            'guest' => [
                'label'      => 'Guest',
                'switcher_label' => 'Guest',
                'headline'   => 'Welcome back, guest.',
                'lede'       => 'Sign in to submit stories for review and share your field work with the community.',
                'identifier' => 'email',
                'idLabel'    => 'Email',
                'idType'     => 'email',
                'chapter'    => 'Guest access',
                'redirect'   => 'author.dashboard',
                'icon'       => 'bi-person-heart',
                'nav_subtitle' => 'Stories & field activities',
            ],
            'intern' => [
                'label'      => 'Intern',
                'switcher_label' => 'Intern',
                'headline'   => 'Welcome, intern.',
                'lede'       => 'Sign in to log daily work, track hours, and submit stories for admin review.',
                'identifier' => 'email',
                'idLabel'    => 'Email',
                'idType'     => 'email',
                'chapter'    => 'Intern access',
                'redirect'   => 'intern.dashboard',
                'icon'       => 'bi-mortarboard',
                'nav_subtitle' => 'Work log & story posting',
            ],
            'fellow' => [
                'label'      => 'Fellow (Fellowship)',
                'switcher_label' => 'Fellow',
                'headline'   => 'Welcome, fellow.',
                'lede'       => 'Sign in to access your fellowship portal after your application has been approved.',
                'identifier' => 'email',
                'idLabel'    => 'Email',
                'idType'     => 'email',
                'chapter'    => 'Fellowship access',
                'redirect'   => 'fellow.dashboard',
                'icon'       => 'bi-award',
                'nav_subtitle' => 'Fellowship portal access',
            ],
            'ngo' => [
                'label'      => 'CSO / NGO / Firm / Organization',
                'switcher_label' => 'Organisation',
                'headline'   => 'Welcome, partner organisation.',
                'lede'       => 'Sign in to submit stories about your programmes and community impact.',
                'identifier' => 'email',
                'idLabel'    => 'Organisation email',
                'idType'     => 'email',
                'chapter'    => 'Organisation access',
                'redirect'   => 'author.dashboard',
                'icon'       => 'bi-buildings',
                'nav_subtitle' => 'Partner orgs & stories',
            ],
            'admin' => [
                'label'      => 'Admin',
                'switcher_label' => 'Admin',
                'headline'   => 'Admin sign-in.',
                'lede'       => 'Restricted area for alliance staff. Manage every module of the ABC Chhattisgarh platform.',
                'identifier' => 'email',
                'idLabel'    => 'Admin email',
                'idType'     => 'email',
                'chapter'    => 'Admin access',
                'redirect'   => 'admin.dashboard',
                'icon'       => 'bi-shield-lock',
                'nav_subtitle' => 'Platform administration',
            ],
        ];
    }

    public static function config(string $type): ?array
    {
        return self::types()[$type] ?? null;
    }

    public static function isValid(string $type): bool
    {
        return isset(self::types()[$type]);
    }

    public static function redirectRoute(?string $type): ?string
    {
        $config = self::config($type ?? '');

        return $config['redirect'] ?? null;
    }

    public static function forHub(): array
    {
        $portals = [];

        foreach (self::order() as $type) {
            $config = self::types()[$type];
            $portals[] = [
                'type'    => $type,
                'slug'    => $type,
                'label'   => $config['label'],
                'short'   => $config['label'],
                'lede'    => $config['lede'],
                'chapter' => $config['chapter'],
                'icon'    => $config['icon'],
            ];
        }

        return $portals;
    }

    public static function forSwitcher(): array
    {
        $portals = [];

        foreach (self::order() as $type) {
            $config = self::types()[$type];
            $portals[] = [
                'slug'           => $type,
                'type'           => $type,
                'label'          => $config['label'],
                'switcher_label' => $config['switcher_label'] ?? $config['label'],
            ];
        }

        return $portals;
    }

    public static function forNav(): array
    {
        $portals = [];

        foreach (self::order() as $type) {
            $config = self::types()[$type];
            $portals[] = [
                'slug'     => $type,
                'label'    => $config['label'],
                'subtitle' => $config['nav_subtitle'],
                'is_admin' => $type === 'admin',
            ];
        }

        return $portals;
    }
}
