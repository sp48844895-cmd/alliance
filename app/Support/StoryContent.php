<?php

namespace App\Support;

use Illuminate\Support\Str;

class StoryContent
{
    private const ALLOWED_TAGS = '<p><br><strong><b><em><i><u><ul><ol><li><h2><h3><h4><h5><h6><blockquote><a><img><span><figure><figcaption><hr>';

    public static function sanitize(?string $html): string
    {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }

        if (str_contains($html, '&lt;')) {
            $decoded = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            if (str_contains($decoded, '<')) {
                $html = $decoded;
            }
        }

        $html = strip_tags($html, self::ALLOWED_TAGS);
        $html = preg_replace('/\s*on\w+\s*=\s*("|\').*?\1/iu', '', $html) ?? $html;
        $html = preg_replace('/\s*on\w+\s*=\s*[^\s>]*/iu', '', $html) ?? $html;
        $html = preg_replace('/javascript\s*:/iu', '', $html) ?? $html;
        $html = preg_replace('/\sstyle\s*=\s*("|\').*?\1/iu', '', $html) ?? $html;
        $html = self::rewriteImgSrcAttributes($html);

        return trim($html);
    }

    public static function isEmpty(?string $html): bool
    {
        $text = trim(strip_tags((string) $html));

        return $text === '';
    }

    private static function rewriteImgSrcAttributes(string $html): string
    {
        return preg_replace_callback(
            '/<img\b[^>]*>/iu',
            function (array $m): string {
                $tag = $m[0];
                if (! preg_match('/src\s*=\s*(["\'])([^"\']*)\1/i', $tag, $sm)) {
                    return $tag;
                }
                $normalized = htmlspecialchars(self::normalizePublicSrc($sm[2]), ENT_QUOTES, 'UTF-8');

                return preg_replace(
                    '/src\s*=\s*(["\'])[^"\']*\1/i',
                    'src="'.$normalized.'"',
                    $tag,
                    1
                ) ?? $tag;
            },
            $html
        ) ?? $html;
    }

    private static function normalizePublicSrc(string $src): string
    {
        $src = trim($src);
        if ($src === '') {
            return '';
        }
        if (preg_match('#^(https?:)?//#i', $src) || str_starts_with(strtolower($src), 'data:')) {
            return $src;
        }

        $path = preg_replace('#^(\.\./)+#', '', $src) ?? $src;
        $path = ltrim($path, '/');
        $filename = basename($path);

        if ($filename !== '' && self::isStoryUploadPath($path)) {
            $resolved = MediaUrl::tryResolve('story', $filename);
            if ($resolved !== null) {
                return self::encodeUrlFilename($resolved);
            }

            $legacy = rtrim((string) config('media.legacy_base'), '/');
            if ($legacy !== '') {
                return $legacy.'/stories/uploads/story/'.rawurlencode($filename);
            }
        }

        if ($path !== '' && is_file(public_path($path))) {
            return self::encodeUrlFilename(asset($path));
        }

        return self::encodeUrlFilename(asset($path));
    }

    private static function isStoryUploadPath(string $path): bool
    {
        return Str::contains(strtolower($path), [
            'uploads/story',
            'storage/story',
            'stories/uploads/story',
        ]);
    }

    private static function encodeUrlFilename(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (! is_string($path) || $path === '') {
            return $url;
        }

        $filename = basename($path);
        if ($filename === '') {
            return $url;
        }

        $encodedName = rawurlencode(rawurldecode($filename));

        return str_replace($filename, $encodedName, $url);
    }
}
