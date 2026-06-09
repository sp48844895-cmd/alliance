<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSliderSlide extends Model
{
    protected $table = 'home_slider_slides';

    public static function activeForHome(): array
    {
        return static::query()
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (self $slide) => [
                'title' => $slide->title,
                'short_description' => $slide->short_description,
                'url' => $slide->url !== '' ? $slide->url : null,
                'image_url' => $slide->image !== '' ? asset('uploads/home-slider/'.$slide->image) : '',
            ])
            ->filter(fn (array $slide) => $slide['image_url'] !== '')
            ->values()
            ->all();
    }
}
