<?php

namespace App\Http\View\Composers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AppSettingsComposer
{
    public function compose(View $view): void
    {
        $settings = Cache::remember('app.settings', 3600, function () {
            return DB::table('settings')
                ->pluck('value', 'key')
                ->all();
        });

        $view->with('settings', $settings);
    }
}
