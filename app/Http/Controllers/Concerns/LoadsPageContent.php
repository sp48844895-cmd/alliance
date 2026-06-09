<?php

namespace App\Http\Controllers\Concerns;

use App\Services\PageContentService;
use Illuminate\Contracts\View\View;

trait LoadsPageContent
{
    protected function pageView(string $view): View
    {
        return view($view);
    }

    protected function pageSection(string $routeName, string $key, array $default = []): array
    {
        return app(PageContentService::class)->section($routeName, $key, $default);
    }
}
