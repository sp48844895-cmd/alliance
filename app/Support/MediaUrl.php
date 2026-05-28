<?php

namespace App\Support;

use Illuminate\Support\Str;

class MediaUrl
{
    public static function resolve(string $preset, string $filename, ?string $remoteFallback = null): string
    {
        $found = self::tryResolve($preset, $filename);

        if ($found !== null) {
            return $found;
        }

        if ($remoteFallback !== null && $remoteFallback !== '') {
            return $remoteFallback;
        }

        $config = config("media.presets.{$preset}", []);

        return (string) ($config['fallback'] ?? '');
    }

    public static function tryResolve(string $preset, string $filename): ?string
    {
        $filename = trim($filename);

        if ($filename === '') {
            return null;
        }

        if (Str::startsWith($filename, ['http://', 'https://'])) {
            return $filename;
        }

        $folders = (array) (config("media.presets.{$preset}.folders") ?? []);

        foreach ($folders as $folder) {
            if (is_file(public_path($folder.'/'.$filename))) {
                return self::publicRelativePath($folder, $filename);
            }
        }

        $pattern = config("media.presets.{$preset}.remote_pattern");

        if (is_string($pattern) && $pattern !== '') {
            return str_replace('{file}', $filename, $pattern);
        }

        return null;
    }

    public static function uploadPath(string $preset): string
    {
        $folder = config("media.presets.{$preset}.upload_folder", 'storage/uploads');

        $path = public_path($folder);

        if (! is_dir($path)) {
            mkdir($path, 0755, true);
        }

        return $path;
    }

    public static function publicRelativePath(string $folder, string $filename): string
    {
        return '/'.trim($folder, '/').'/'.ltrim($filename, '/');
    }

    public static function delete(string $preset, string $filename): void
    {
        $filename = trim($filename);

        if ($filename === '') {
            return;
        }

        $folders = (array) (config("media.presets.{$preset}.folders") ?? []);

        foreach ($folders as $folder) {
            $path = public_path($folder.'/'.$filename);

            if (is_file($path)) {
                @unlink($path);
            }
        }
    }
}
