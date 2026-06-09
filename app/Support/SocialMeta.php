<?php

namespace App\Support;

use Illuminate\Support\Str;

final class SocialMeta
{
    public const SITE_NAME = 'ChhattisgarhABC';

    public const DEFAULT_TITLE = 'ChhattisgarhABC · Alliance for Behaviour Change Chhattisgarh';

    public const DEFAULT_DESCRIPTION = 'ChhattisgarhABC is a community platform where youth, professionals, civil society and government come together to share experiences and advance Social and Behaviour Change Communication (SBC) across Chhattisgarh.';

    public const DEFAULT_IMAGE = 'assets/img/site-logo.png';

    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $image,
        public readonly string $url,
        public readonly string $type = 'website',
        public readonly ?int $imageWidth = null,
        public readonly ?int $imageHeight = null,
    ) {}

    public static function make(
        ?string $title = null,
        ?string $description = null,
        ?string $image = null,
        ?string $url = null,
        string $type = 'website',
    ): self {
        $resolvedImage = self::absoluteUrl($image) ?? self::absoluteUrl(self::DEFAULT_IMAGE);
        $resolvedUrl = self::absoluteUrl($url) ?? self::canonicalPageUrl();
        [$imageWidth, $imageHeight] = self::imageDimensions($resolvedImage);

        return new self(
            title: self::cleanText($title) ?: self::DEFAULT_TITLE,
            description: Str::limit(self::cleanText($description) ?: self::DEFAULT_DESCRIPTION, 200, '…'),
            image: $resolvedImage ?? '',
            url: $resolvedUrl,
            type: $type !== '' ? $type : 'website',
            imageWidth: $imageWidth,
            imageHeight: $imageHeight,
        );
    }

    public function twitterCard(): string
    {
        return $this->image !== '' ? 'summary_large_image' : 'summary';
    }

    public static function absoluteUrl(?string $url): ?string
    {
        if ($url === null) {
            return null;
        }

        $url = trim($url);

        if ($url === '') {
            return null;
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return self::canonicalizePublicUrl($url);
        }

        if (str_starts_with($url, '//')) {
            return self::canonicalizePublicUrl(request()->getScheme().':'.$url);
        }

        return self::canonicalizePublicUrl(self::appRootUrl().'/'.ltrim($url, '/'));
    }

    public static function canonicalPageUrl(): string
    {
        return self::canonicalizePublicUrl(self::appRootUrl().request()->getRequestUri());
    }

    private static function appRootUrl(): string
    {
        $configured = rtrim((string) config('app.url'), '/');

        if ($configured !== '' && ! self::isLocalHost($configured)) {
            return $configured;
        }

        return rtrim((string) url('/'), '/');
    }

    private static function canonicalizePublicUrl(string $url): string
    {
        $appUrl = rtrim((string) config('app.url'), '/');

        if ($appUrl === '' || self::isLocalHost($appUrl) || ! self::isLocalHost($url)) {
            return $url;
        }

        $path = parse_url($url, PHP_URL_PATH) ?? '/';
        $query = parse_url($url, PHP_URL_QUERY);
        $canonical = $appUrl.$path;

        if (is_string($query) && $query !== '') {
            $canonical .= '?'.$query;
        }

        return $canonical;
    }

    private static function isLocalHost(string $url): bool
    {
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        return in_array($host, ['localhost', '127.0.0.1', '0.0.0.0', '[::1]'], true)
            || str_ends_with($host, '.local')
            || str_ends_with($host, '.test');
    }

    /**
     * @return array{0: ?int, 1: ?int}
     */
    private static function imageDimensions(?string $absoluteUrl): array
    {
        if ($absoluteUrl === null || $absoluteUrl === '') {
            return [null, null];
        }

        $path = parse_url($absoluteUrl, PHP_URL_PATH);

        if (! is_string($path) || $path === '') {
            return [null, null];
        }

        $fullPath = public_path($path);

        if (! is_file($fullPath)) {
            return [null, null];
        }

        $size = @getimagesize($fullPath);

        if ($size === false) {
            return [null, null];
        }

        return [(int) $size[0], (int) $size[1]];
    }

    private static function cleanText(?string $text): ?string
    {
        if ($text === null) {
            return null;
        }

        $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/u', ' ', $text) ?? '';

        $text = trim($text);

        return $text !== '' ? $text : null;
    }
}
