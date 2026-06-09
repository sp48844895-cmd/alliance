<?php

namespace App\Support;

class HomeHeroCarousel
{
    /**
     * @param  array<int, array{desktop_url: string, mobile_url: string, href: ?string, is_external: bool}>  $banners
     * @param  array<int, array{title: string, short_description: string, url: ?string, image_url: string}>  $homeSliderSlides
     * @param  array<string, mixed>  $hero
     * @return array<int, array{desktop_url: string, mobile_url: string, href: ?string, is_external: bool, title: string, short_description: string, card_image_url: string}>
     */
    public static function slides(array $banners, array $homeSliderSlides, array $hero): array
    {
        $defaultCard = $hero['impact_banner']['background_url'] ?? '';
        $defaultTitle = trim(strip_tags((string) ($hero['headline_html'] ?? '')));
        $defaultDescription = trim(strip_tags((string) ($hero['lede_html'] ?? '')));

        if ($banners !== []) {
            $slides = [];

            foreach ($banners as $index => $banner) {
                $content = $homeSliderSlides[$index] ?? null;

                $slides[] = [
                    'desktop_url' => $banner['desktop_url'],
                    'mobile_url' => $banner['mobile_url'],
                    'href' => $banner['href'],
                    'is_external' => $banner['is_external'],
                    'title' => trim((string) ($content['title'] ?? '')) !== '' ? $content['title'] : $defaultTitle,
                    'short_description' => trim((string) ($content['short_description'] ?? '')) !== '' ? $content['short_description'] : $defaultDescription,
                    'card_image_url' => trim((string) ($content['image_url'] ?? '')) !== '' ? $content['image_url'] : $defaultCard,
                ];
            }

            return $slides;
        }

        if ($homeSliderSlides === []) {
            return [];
        }

        return array_map(static function (array $slide) use ($defaultTitle, $defaultDescription, $defaultCard) {
            $image = $slide['image_url'] ?? '';

            return [
                'desktop_url' => $image,
                'mobile_url' => $image,
                'href' => $slide['url'] ?? null,
                'is_external' => ! empty($slide['url']) && str_starts_with($slide['url'], 'http'),
                'title' => trim((string) ($slide['title'] ?? '')) !== '' ? $slide['title'] : $defaultTitle,
                'short_description' => trim((string) ($slide['short_description'] ?? '')) !== '' ? $slide['short_description'] : $defaultDescription,
                'card_image_url' => $image !== '' ? $image : $defaultCard,
            ];
        }, $homeSliderSlides);
    }
}
