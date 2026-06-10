<?php

namespace App\Support;

use Illuminate\Support\Str;

final class StoryShareUrls
{
    public function __construct(
        public readonly string $url,
        public readonly string $title,
        public readonly string $description,
        public readonly string $hashtags,
    ) {}

    public static function make(
        ?string $url = null,
        ?string $title = null,
        ?string $description = null,
        ?string $hashtags = null,
    ): self {
        $resolvedUrl = SocialMeta::publicUrl($url);
        $resolvedTitle = trim((string) $title);
        $resolvedDescription = trim((string) $description);
        $resolvedHashtags = trim((string) ($hashtags ?? '#SBCMatters'));

        return new self(
            url: $resolvedUrl,
            title: $resolvedTitle,
            description: $resolvedDescription,
            hashtags: $resolvedHashtags !== '' ? $resolvedHashtags : '#SBCMatters',
        );
    }

    /**
     * @param  array<string, mixed>  $story
     */
    public static function fromStory(array $story): self
    {
        $slug = (string) ($story['slug'] ?? '');
        $fallbackUrl = $slug !== ''
            ? route(PageRoute::named('stories.show'), $slug)
            : null;

        return self::make(
            url: $story['share_url'] ?? $story['url'] ?? $fallbackUrl,
            title: $story['share_title'] ?? $story['title'] ?? '',
            description: $story['share_description'] ?? $story['lede'] ?? '',
            hashtags: $story['share_hashtags'] ?? '#SBCMatters',
        );
    }

    public function facebookUrl(): string
    {
        return 'https://www.facebook.com/sharer/sharer.php?u='.rawurlencode($this->url);
    }

    public function twitterUrl(): string
    {
        return 'https://twitter.com/intent/tweet?'.http_build_query([
            'url' => $this->url,
            'text' => $this->twitterText(),
        ], '', '&', PHP_QUERY_RFC3986);
    }

    public function whatsappUrl(): string
    {
        return 'https://wa.me/?text='.rawurlencode($this->whatsappText());
    }

    public function whatsappText(): string
    {
        $parts = array_filter([
            $this->title,
            $this->description !== '' && $this->description !== $this->title ? $this->description : null,
            $this->hashtags,
            $this->url,
        ], fn (?string $part) => is_string($part) && trim($part) !== '');

        return implode("\n\n", $parts);
    }

    public function copyText(): string
    {
        return $this->whatsappText();
    }

    private function twitterText(): string
    {
        $text = $this->title;

        $shortDescription = Str::limit($this->description, 100, '…');

        if ($shortDescription !== '' && $shortDescription !== $this->title) {
            $text .= ' — '.$shortDescription;
        }

        if ($this->hashtags !== '') {
            $text .= ' '.$this->hashtags;
        }

        return trim($text);
    }
}
