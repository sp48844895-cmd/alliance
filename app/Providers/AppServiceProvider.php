<?php

namespace App\Providers;

use App\Http\View\Composers\AppSettingsComposer;
use App\Services\BlogStoryService;
use App\Services\EventPageService;
use App\Services\KnowledgeHubPageService;
use App\Services\MembershipPageService;
use App\Services\PageContentService;
use App\Services\ReportsPageService;
use App\Services\SbcResourcePoolService;
use Illuminate\Pagination\Paginator;
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
        Paginator::defaultView('pagination.admin');
        Paginator::defaultSimpleView('pagination.admin');

        View::composer('layouts.app', AppSettingsComposer::class);

        View::composer(['layouts.app', 'pages.*', 'partials.page-jumbotron'], function ($view) {
            $routeName = request()->route()?->getName();
            $pageContent = app(PageContentService::class)->forRoute($routeName);

            $view->with([
                'pageContent' => $pageContent,
                'pageSections' => $pageContent['sections'] ?? [],
            ]);
        });
    }
}
