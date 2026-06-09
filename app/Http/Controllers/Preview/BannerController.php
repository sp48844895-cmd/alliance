<?php

namespace App\Http\Controllers\Preview;

use App\Http\Controllers\Controller;

use App\Models\Banner;

class BannerController extends Controller
{
    public function index()
    {
        $rows = Banner::orderByDesc('id')->get();

        $banners = [];
        foreach ($rows as $banner) {
            $banners[] = [
                'desktop_image' => $banner->dbannerimg ? asset('uploads/banners/'.$banner->dbannerimg) : '',
                'mobile_image' => $banner->mbannerimg ? asset('uploads/banners/'.$banner->mbannerimg) : '',
                'link' => $banner->redirect ?: $banner->ytlink,
            ];
        }

        return view('preview::banners.index', compact('banners'));
    }
}
