<?php

namespace App\Support;

use Illuminate\Support\Str;

class PageRoute
{
    public static function is(string ...$patterns): bool
    {
        if (request()->routeIs(...$patterns)) {
            return true;
        }

        $name = request()->route()?->getName();
        if ($name === null || ! str_starts_with($name, 'preview.')) {
            return false;
        }

        $logical = substr($name, strlen('preview.'));

        foreach ($patterns as $pattern) {
            if (Str::is($pattern, $logical)) {
                return true;
            }
        }

        return false;
    }

    public static function isPreview(): bool
    {
        $name = request()->route()?->getName();

        return $name !== null && str_starts_with($name, 'preview.');
    }

    public static function logicalName(): ?string
    {
        $name = request()->route()?->getName();
        if ($name === null) {
            return null;
        }

        if (str_starts_with($name, 'preview.')) {
            return substr($name, strlen('preview.'));
        }

        return $name;
    }

    public static function named(string $name): string
    {
        return self::isPreview() ? 'preview.'.$name : $name;
    }
}
