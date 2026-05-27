<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\LoadsPageContent;
use App\Support\MediaUrl;
use App\Services\BlogStoryService;
use App\Services\EventPageService;
use App\Services\MembershipPageService;
use App\Services\ReportsPageService;
use App\Services\SbcResourcePoolService;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PageController extends Controller
{
    use LoadsPageContent;

    public function home(BlogStoryService $blogStories, EventPageService $eventPage)
    {
        $homePrograms = Cache::remember('home.programs', 3600, fn () => DB::table('programs')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['id', 'title', 'tag', 'short_desc', 'full_desc', 'card_style', 'image'])
            ->map(function ($row) {
                $row->image_url = \App\Support\MediaUrl::tryResolve('program', (string) ($row->image ?? '')) ?? '';

                return $row;
            }));

        $homeInsights = Cache::remember('home.insights', 3600, fn () => DB::table('insights')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['id', 'title', 'tag', 'description', 'image', 'link_text', 'link_url'])
            ->map(function ($row) {
                $row->image_url = MediaUrl::tryResolve('insights', (string) ($row->image ?? '')) ?? '';

                return $row;
            }));

        $homeBanners = Cache::remember('home.banners', 3600, function () {
            return DB::table('banner')
                ->orderByDesc('id')
                ->get(['id', 'dbannerimg', 'mbannerimg', 'ytlink', 'redirect'])
                ->map(function ($row) {
                    $desktopUrl = MediaUrl::tryResolve('banner', (string) ($row->dbannerimg ?? ''));
                    $mobileUrl = MediaUrl::tryResolve('banner', (string) ($row->mbannerimg ?? ''));

                    if ($desktopUrl === null && $mobileUrl === null) {
                        return null;
                    }

                    $redirect = trim((string) ($row->redirect ?? ''));
                    $ytlink = trim((string) ($row->ytlink ?? ''));
                    $href = ($redirect !== '' && $redirect !== '#') ? $redirect : null;

                    if ($href === null && $ytlink !== '') {
                        $href = $ytlink;
                    }

                    return (object) [
                        'id' => $row->id,
                        'desktop_url' => $desktopUrl ?? $mobileUrl,
                        'mobile_url' => $mobileUrl ?? $desktopUrl,
                        'href' => $href,
                        'is_external' => $href !== null && str_starts_with($href, 'http'),
                    ];
                })
                ->filter()
                ->values();
        });

        return $this->pageView('pages.home', [
            'homeRecentStories' => $blogStories->recentForHome(6),
            'homeRecentEvents'  => $eventPage->recentForHome(5),
            'homePrograms'      => $homePrograms,
            'homeInsights'      => $homeInsights,
            'homeBanners'       => $homeBanners,
        ]);
    }

    public function about()
    {
        return $this->pageView('pages.about');
    }

    public function campaigns()
    {
        return $this->pageView('pages.campaigns');
    }

    public function stories(BlogStoryService $blogStories)
    {
        return $this->pageView('pages.stories', [
            'storyPaginator' => $blogStories->paginatedListing(20),
            'storyFilters' => $blogStories->filters(),
        ]);
    }

    public function story(string $slug, BlogStoryService $blogStories)
    {
        $story = $blogStories->findStoryForPage($slug);

        abort_unless($story, 404);

        return view('pages.story-details', [
            'story' => $story,
        ]);
    }

    public function events(EventPageService $eventPage)
    {
        $galleryTiles = $eventPage->galleryTiles();

        $latestEvent = DB::table('event')
            ->where('event_status', 1)
            ->whereNotNull('start_date')
            ->where('start_date', '!=', '')
            ->orderByDesc('start_date')
            ->value('start_date');

        $latestEventMonth = null;
        if ($latestEvent) {
            try {
                $latestEventMonth = Carbon::parse($latestEvent)->format('Y-m');
            } catch (\Throwable) {}
        }

        return $this->pageView('pages.events', [
            'eventsBoard' => array_merge($this->pageSection('events', 'board'), $eventPage->boardData()),
            'eventsUpcomingCard' => $eventPage->upcomingCard(),
            'eventsPastCards' => $eventPage->pastCards(),
            'eventsGalleryTiles' => $galleryTiles,
            'eventsGalleryTotal' => count($galleryTiles),
            'latestEventMonth' => $latestEventMonth ?? now()->format('Y-m'),
        ]);
    }

    public function event(string $slug, EventPageService $eventPage)
    {
        $event = $eventPage->findPublishedBySlug($slug);

        abort_unless($event, 404);

        return view('pages.event-details', [
            'event' => $eventPage->formatForDetail($event),
            'relatedEvents' => $eventPage->relatedEvents((int) $event->id),
        ]);
    }

    public function calendarData(Request $request, EventPageService $eventPage)
    {
        return response()->json(
            $eventPage->calendarPayload($request->query('month'))
        );
    }

    public function programsAndInitiatives()
    {
        $programs = Cache::remember('home.programs', 3600, fn () => DB::table('programs')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['id', 'title', 'tag', 'short_desc', 'full_desc', 'card_style', 'image'])
            ->map(function ($row) {
                $row->image_url = \App\Support\MediaUrl::tryResolve('program', (string) ($row->image ?? '')) ?? '';

                return $row;
            }));

        return $this->pageView('pages.programs-and-initiatives', [
            'homePrograms' => $programs,
        ]);
    }

    public function getInvolved()
    {
        return $this->pageView('pages.get-involved');
    }

    public function members(Request $request, MembershipPageService $membershipPage)
    {
        $activeMemberFilters = [
            'district' => $request->string('district')->toString() ?: 'all',
            'type' => $request->string('type')->toString() ?: 'all',
            'search' => $request->string('search')->toString(),
        ];

        return $this->pageView('pages.members', [
            'membersPageFilters' => $membershipPage->filters(),
            'memberPaginator' => $membershipPage->paginatedListing(20, $activeMemberFilters),
            'activeMemberFilters' => $activeMemberFilters,
        ]);
    }

    public function resources(SbcResourcePoolService $sbcPool)
    {
        return $this->pageView('pages.resources', [
            'resourcePeople' => $sbcPool->listingFromDatabase(),
        ]);
    }

    public function contact()
    {
        return $this->pageView('pages.contact');
    }

    public function learningCorner()
    {
        $allCats = DB::table('learning_cat')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('cat_name')
            ->get();

        $counts = DB::table('learning_corner')
            ->selectRaw('cat_id, count(*) as total')
            ->groupBy('cat_id')
            ->pluck('total', 'cat_id');

        $categoryTree = $this->buildLcTree($allCats, $counts);

        return $this->pageView('pages.learning-corner', [
            'categoryTree' => $categoryTree,
        ]);
    }

    public function learningCornerCategoryAjax($id)
    {
        $cat = DB::table('learning_cat')->where('id', $id)->where('status', 1)->first();

        if (! $cat) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $allCatIds = $this->getLcDescendantIds((int) $id);

        $resources = DB::table('learning_corner')
            ->join('learning_cat', 'learning_corner.cat_id', '=', 'learning_cat.id')
            ->whereIn('learning_corner.cat_id', $allCatIds)
            ->where('learning_cat.status', 1)
            ->where('learning_corner.status', 1)
            ->orderByDesc('learning_corner.date')
            ->orderByDesc('learning_corner.id')
            ->get([
                'learning_corner.id',
                'learning_corner.title',
                'learning_corner.content',
                'learning_corner.m_type',
                'learning_corner.link',
                'learning_corner.image',
                'learning_corner.date',
                'learning_corner.cat_id',
                'learning_cat.cat_name',
                'learning_cat.cat_icon',
            ]);

        $resources = $resources->map(function ($row) {
            $row->image_url = \App\Support\MediaUrl::tryResolve('learning', (string) ($row->image ?? '')) ?? '';
            return $row;
        });

        return response()->json([
            'category' => [
                'id'          => $cat->id,
                'name'        => $cat->cat_name,
                'icon'        => $cat->cat_icon,
                'description' => $cat->description ?? '',
            ],
            'breadcrumb' => $this->getLcBreadcrumb((int) $id),
            'resources'  => $resources->values(),
            'total'      => $resources->count(),
        ]);
    }

    private function buildLcTree($allCats, $counts, $parentId = null): array
    {
        return $allCats
            ->where('parent_id', $parentId)
            ->values()
            ->map(function ($cat) use ($allCats, $counts) {
                $children = $this->buildLcTree($allCats, $counts, $cat->id);
                $directCount = (int) ($counts[$cat->id] ?? 0);
                $totalCount = $directCount + collect($children)->sum('total_count');

                return (object) [
                    'id'          => $cat->id,
                    'cat_name'    => $cat->cat_name,
                    'cat_icon'    => $cat->cat_icon ?? 'icon-folder',
                    'description' => $cat->description ?? '',
                    'children'    => $children,
                    'count'       => $directCount,
                    'total_count' => $totalCount,
                    'depth'       => 0,
                ];
            })
            ->all();
    }

    private function getLcDescendantIds(int $parentId): array
    {
        $ids = [$parentId];
        $children = DB::table('learning_cat')
            ->where('parent_id', $parentId)
            ->where('status', 1)
            ->pluck('id');

        foreach ($children as $childId) {
            $ids = array_merge($ids, $this->getLcDescendantIds((int) $childId));
        }

        return $ids;
    }

    private function getLcBreadcrumb(int $catId): array
    {
        $crumbs = [];
        $currentId = $catId;

        for ($i = 0; $i < 10 && $currentId; $i++) {
            $cat = DB::table('learning_cat')->where('id', $currentId)->first();
            if (! $cat) {
                break;
            }
            array_unshift($crumbs, ['id' => $cat->id, 'name' => $cat->cat_name]);
            $currentId = (int) ($cat->parent_id ?? 0);
        }

        return $crumbs;
    }

    public function reports(ReportsPageService $reportsPage)
    {
        $header = $this->pageSection('reports', 'page_header', [
            'pageTitle' => 'Reports and Insights',
            'pageLede' => 'Read reports, newsletters and success stories that document social and behaviour change work across Chhattisgarh.',
        ]);

        $reports = $reportsPage->listingForPage();

        if ($reports === []) {
            $reportsSection = $this->pageSection('reports', 'reports_grid', [
                'reports' => [],
            ]);

            $reports = collect($reportsSection['reports'] ?? [])
                ->map(function (array $report) {
                    $cover = ltrim((string) ($report['cover'] ?? ''), '/');
                    $preview = ltrim((string) ($report['preview'] ?? ''), '/');
                    $download = ltrim((string) ($report['download'] ?? ''), '/');
                    $report['cover'] = $cover;
                    $report['preview'] = $preview;
                    $report['download'] = $download;
                    $report['cover_exists'] = $cover !== '' && file_exists(public_path($cover));
                    $report['preview_exists'] = $preview !== '' && file_exists(public_path($preview));
                    $report['download_exists'] = $download !== '' && file_exists(public_path($download));
                    $report['preview_url'] = $report['preview_exists'] ? asset($preview) : '#reports-title';
                    $report['download_url'] = $report['download_exists'] ? asset($download) : '#reports-title';
                    $report['flipbook_slug'] = '';
                    $report['flipbook_pages'] = 0;
                    $report['has_flipbook'] = false;

                    return $report;
                })
                ->all();
        }

        return $this->pageView('pages.reports', [
            'pageTitle' => $header['pageTitle'],
            'pageLede'  => $header['pageLede'],
            'reports'   => $reports,
        ]);
    }

    public function magazine(string $slug)
    {
        $report = DB::table('reports')
            ->where('status', 1)
            ->where('flipbook_slug', $slug)
            ->first();

        abort_unless($report, 404);

        $pagesDir = public_path('report-flipbooks/' . $slug . '/pages');
        abort_unless(is_dir($pagesDir), 404);

        $total = (int) ($report->flipbook_pages ?? 0);
        if ($total <= 0) {
            abort(404);
        }

        $pages = [];
        for ($i = 1; $i <= $total; $i++) {
            $relative = 'report-flipbooks/' . $slug . '/pages/' . $i . '.jpg';
            if (file_exists(public_path($relative))) {
                $pages[] = asset($relative);
            }
        }

        abort_if($pages === [], 404);

        return view('pages.magazine-viewer', [
            'magazineTitle' => $report->title,
            'magazineType' => $report->type ?: 'Report',
            'magazineSlug' => $slug,
            'magazinePages' => $pages,
            'magazineDownload' => $report->download_path && file_exists(public_path(ltrim($report->download_path, '/')))
                ? asset(ltrim($report->download_path, '/'))
                : null,
        ]);
    }

    public function newsletterSubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:191',
        ]);

        $email = strtolower(trim((string) $request->input('email')));
        $exists = DB::table('newsletter_subscribers')->where('email', $email)->exists();

        if (! $exists) {
            DB::table('newsletter_subscribers')->insert([
                'email'         => $email,
                'ip_address'    => $request->ip(),
                'subscribed_at' => now(),
            ]);
        }

        $refererPath = parse_url((string) $request->headers->get('referer', ''), PHP_URL_PATH) ?: '';
        $homePath = parse_url(route('home'), PHP_URL_PATH) ?: '/';
        $redirect = $refererPath === $homePath
            ? redirect()->route('home')->withFragment('newsletter')
            : back();

        $message = $exists
            ? 'You are already subscribed to our newsletter.'
            : 'Thanks for subscribing! We will be in touch.';

        return $redirect->with('status', $message);
    }
}
