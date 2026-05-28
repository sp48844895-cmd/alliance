<?php

namespace App\Providers;

use App\Http\View\Composers\AppSettingsComposer;
use App\Support\LoginPortals;
use App\Services\BlogStoryService;
use App\Services\EventPageService;
use App\Services\KnowledgeHubPageService;
use App\Services\MembershipPageService;
use App\Services\PageContentService;
use App\Services\ReportsPageService;
use App\Services\SbcResourcePoolService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PageContentService::class);
        $this->app->singleton(BlogStoryService::class);
        $this->app->singleton(EventPageService::class);
        $this->app->singleton(MembershipPageService::class);
        $this->app->singleton(SbcResourcePoolService::class);
        $this->app->singleton(KnowledgeHubPageService::class);
        $this->app->singleton(ReportsPageService::class);
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        if ($this->app->environment('local') && ! $this->app->runningInConsole()) {
            $root = request()->getSchemeAndHttpHost();
            if ($root !== '') {
                URL::forceRootUrl($root);
            }
        }

        Paginator::defaultView('pagination.admin');
        Paginator::defaultSimpleView('pagination.admin');

        View::composer('layouts.app', AppSettingsComposer::class);

        View::composer('layouts.app', function ($view) {
            $view->with('loginPortals', LoginPortals::forNav());
        });

        View::composer(['layouts.app', 'pages.*', 'partials.page-jumbotron'], function ($view) {
            $routeName = request()->route()?->getName();
            $pageContent = app(PageContentService::class)->forRoute($routeName);

            $view->with([
                'pageContent' => $pageContent,
                'pageSections' => $pageContent['sections'] ?? [],
            ]);
        });

        View::composer('layouts.admin', function ($view) {
            $limit = ini_get('upload_max_filesize') ?: '2M';
            $bytes = self::iniSizeToBytes($limit);

            $view->with([
                'phpUploadLimit' => $limit,
                'phpUploadLimitLow' => $bytes < 10 * 1024 * 1024,
            ]);
        });
    }

    private static function iniSizeToBytes(string $value): int
    {
        $value = trim($value);
        if ($value === '') {
            return 0;
        }

        $unit = strtolower(substr($value, -1));
        $number = (int) $value;

        return match ($unit) {
            'g' => $number * 1024 * 1024 * 1024,
            'm' => $number * 1024 * 1024,
            'k' => $number * 1024,
            default => (int) $value,
        };
    }
}
