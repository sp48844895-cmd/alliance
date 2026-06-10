<?php

namespace App\Support;

final class ShareImage
{
    public const MAX_WIDTH = 1200;

    public const JPEG_QUALITY = 82;

    /**
     * @var array<int, string>
     */
    private const STORY_FOLDERS = ['uploads/story', 'storage/story', 'uploads/blogs', 'uploads/stories'];

    public static function storyOgUrl(?string $image): ?string
    {
        $filename = self::storyFilename($image);

        if ($filename === null) {
            return SocialMeta::absoluteUrl($image);
        }

        return SocialMeta::absoluteUrl(route('share.image.story', ['filename' => $filename]));
    }

    public static function storySourcePath(string $filename): ?string
    {
        $filename = basename($filename);

        if ($filename === '' || ! preg_match('/^[a-zA-Z0-9._-]+$/', $filename)) {
            return null;
        }

        foreach (self::STORY_FOLDERS as $folder) {
            $path = public_path($folder.'/'.$filename);

            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    public static function storyCachePath(string $filename): string
    {
        return storage_path('app/share-cache/story/'.basename($filename).'.jpg');
    }

    public static function ensureStoryCache(string $sourcePath, string $cachePath): bool
    {
        $cacheDir = dirname($cachePath);

        if (! is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        if (is_file($cachePath) && filemtime($cachePath) >= filemtime($sourcePath)) {
            return true;
        }

        if (! extension_loaded('gd')) {
            return is_file($cachePath);
        }

        $info = @getimagesize($sourcePath);

        if ($info === false) {
            return false;
        }

        $source = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG => @imagecreatefrompng($sourcePath),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : false,
            default => false,
        };

        if ($source === false) {
            return false;
        }

        $width = imagesx($source);
        $height = imagesy($source);
        $targetWidth = min($width, self::MAX_WIDTH);

        if ($targetWidth < $width) {
            $targetHeight = (int) round($height * ($targetWidth / $width));
            $resized = imagecreatetruecolor($targetWidth, $targetHeight);

            if ($resized === false) {
                imagedestroy($source);

                return false;
            }

            imagecopyresampled($resized, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);
            imagedestroy($source);
            $source = $resized;
        }

        $saved = imagejpeg($source, $cachePath, self::JPEG_QUALITY);
        imagedestroy($source);

        return $saved;
    }

    private static function storyFilename(?string $image): ?string
    {
        if ($image === null || trim($image) === '') {
            return null;
        }

        $path = parse_url($image, PHP_URL_PATH) ?? $image;
        $path = '/'.ltrim((string) $path, '/');

        foreach (self::STORY_FOLDERS as $folder) {
            $prefix = '/'.$folder.'/';

            if (str_starts_with($path, $prefix)) {
                return basename($path);
            }
        }

        return null;
    }
}
