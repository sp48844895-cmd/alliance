<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banner';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'small_title',
        'dbannerimg',
        'mbannerimg',
        'front_image',
        'url',
        'sort_order',
        'status',
        'ytlink',
        'redirect',
        'created_at',
    ];

    /**
     * @param  array<string, mixed>  $heroDefaults
     * @return array<int, array{
     *     desktop_url: string,
     *     mobile_url: string,
     *     title: string,
     *     short_description: string,
     *     small_title: string,
     *     card_image_url: string,
     *     card_url: ?string,
     *     is_external: bool
     * }>
     */
    public static function activeForHome(array $heroDefaults = []): array
    {
        $defaultCard = (string) ($heroDefaults['impact_banner']['background_url'] ?? '');
        $defaultTitle = trim(strip_tags((string) ($heroDefaults['headline_html'] ?? '')));
        $defaultDescription = trim(strip_tags((string) ($heroDefaults['lede_html'] ?? '')));
        $defaultSmallTitle = trim((string) ($heroDefaults['chapter_label'] ?? 'Welcome'));

        return static::query()
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get()
            ->map(function (self $banner) use ($defaultCard, $defaultTitle, $defaultDescription, $defaultSmallTitle) {
                $desktop = $banner->dbannerimg !== ''
                    ? asset('uploads/banners/'.$banner->dbannerimg)
                    : '';
                $mobile = $banner->mbannerimg !== ''
                    ? asset('uploads/banners/'.$banner->mbannerimg)
                    : '';

                if ($desktop === '' && $mobile === '') {
                    return null;
                }

                $cardUrl = trim((string) $banner->url);
                if ($cardUrl === '' || $cardUrl === '#') {
                    $cardUrl = null;
                }

                $frontImage = $banner->front_image !== ''
                    ? asset('uploads/banners/'.$banner->front_image)
                    : '';

                return [
                    'desktop_url' => $desktop !== '' ? $desktop : $mobile,
                    'mobile_url' => $mobile !== '' ? $mobile : $desktop,
                    'title' => trim((string) $banner->title) !== '' ? $banner->title : $defaultTitle,
                    'short_description' => trim((string) $banner->description) !== ''
                        ? $banner->description
                        : $defaultDescription,
                    'small_title' => trim((string) $banner->small_title) !== ''
                        ? $banner->small_title
                        : $defaultSmallTitle,
                    'card_image_url' => $frontImage !== '' ? $frontImage : $defaultCard,
                    'card_url' => $cardUrl,
                    'is_external' => $cardUrl !== null && str_starts_with($cardUrl, 'http'),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }
}
