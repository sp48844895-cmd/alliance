<?php

namespace App\Support;

class StoryContributor
{
    public const TYPES = ['volunteer', 'intern', 'ngo'];

    public static function types(): array
    {
        return self::TYPES;
    }

    public static function canAccess(?string $type): bool
    {
        return in_array($type ?? '', self::TYPES, true);
    }

    public static function portalLabel(?string $type): string
    {
        return match ($type) {
            'volunteer' => 'Individual Volunteer',
            'intern'    => 'Intern',
            'ngo'       => 'CSO / NGO / Firm / Organization',
            default     => 'Story portal',
        };
    }

    public static function homeRoute(?string $type): string
    {
        return match ($type) {
            'intern' => 'intern.dashboard',
            default => 'author.dashboard',
        };
    }
}
