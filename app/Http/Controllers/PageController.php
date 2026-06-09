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
        extract($this->preparePrograms($this->pageSection('home', 'programs')));

        $homeInsights = $this->cachedHomeInsights();
        $homeBanners = $this->cachedHomeBanners();
        $homeRecentStories = $blogStories->recentForHome(6);
        $homeRecentEvents = $eventPage->recentForHome(5);

        return $this->pageView('pages.home')->with(compact(
            'homeRecentStories',
            'homeRecentEvents',
            'programsUseSlider',
            'programsHeader',
            'programsCards',
            'programsDetailsUrl',
            'homeInsights',
            'homeBanners',
        ));
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
        $storyPaginator = $blogStories->paginatedListing(20);
        $storyFilters = $blogStories->filters();

        return $this->pageView('pages.stories')->with(compact('storyPaginator', 'storyFilters'));
    }

    public function story(string $slug, BlogStoryService $blogStories)
    {
        $story = $blogStories->findStoryForPage($slug);

        abort_unless($story, 404);

        $storyUrl = route('stories.show', $story['slug']);
        $relatedStories = $blogStories->relatedStoriesForCurrentStory($story, 2);
        $excludeUrls = array_merge(
            [$storyUrl],
            array_map(fn (array $item) => route('stories.show', $item['slug']), $relatedStories)
        );

        $recentStories = array_values(array_filter(
            $blogStories->recentForStorySidebar((int) ($story['id'] ?? 0), 12),
            fn (array $item) => ! in_array($item['url'] ?? '', $excludeUrls, true)
        ));

        $recentStories = array_slice($recentStories, 0, 5);

        return view('pages.story-details')->with(compact('story', 'recentStories', 'relatedStories'));
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

        $eventsBoard = array_merge($this->pageSection('events', 'board'), $eventPage->boardData());
        $eventsUpcomingCard = $eventPage->upcomingCard();
        $eventsPastCards = $eventPage->pastCards();
        $eventsGalleryTiles = $galleryTiles;
        $eventsGalleryTotal = count($galleryTiles);
        $latestEventMonth = $latestEventMonth ?? now()->format('Y-m');

        return $this->pageView('pages.events')->with(compact(
            'eventsBoard',
            'eventsUpcomingCard',
            'eventsPastCards',
            'eventsGalleryTiles',
            'eventsGalleryTotal',
            'latestEventMonth',
        ));
    }

    public function event(string $slug, EventPageService $eventPage)
    {
        $eventRecord = $eventPage->findPublishedBySlug($slug);

        abort_unless($eventRecord, 404);

        $event = $eventPage->formatForDetail($eventRecord);
        $relatedEvents = $eventPage->relatedEvents((int) $eventRecord->id);

        return view('pages.event-details')->with(compact('event', 'relatedEvents'));
    }

    public function calendarData(Request $request, EventPageService $eventPage)
    {
        return response()->json(
            $eventPage->calendarPayload($request->query('month'))
        );
    }

    public function programsAndInitiatives()
    {
        extract($this->preparePrograms($this->pageSection('programs', 'programs')));

        return $this->pageView('pages.programs-and-initiatives')->with(compact(
            'programsUseSlider',
            'programsHeader',
            'programsCards',
            'programsDetailsUrl',
        ));
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

        $membersPageFilters = $membershipPage->filters();
        $memberPaginator = $membershipPage->paginatedListing(20, $activeMemberFilters);

        return $this->pageView('pages.members')->with(compact(
            'membersPageFilters',
            'memberPaginator',
            'activeMemberFilters',
        ));
    }

    public function resources(SbcResourcePoolService $sbcPool)
    {
        $resourcePeople = $sbcPool->listingFromDatabase();

        return $this->pageView('pages.resources')->with(compact('resourcePeople'));
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
            ->where('status', 1)
            ->selectRaw('cat_id, count(*) as total')
            ->groupBy('cat_id')
            ->pluck('total', 'cat_id');

        $mainCategories = $this->buildLcTree($allCats, $counts);

        $cms = $this->pageSection('learning-corner', 'jumbotron');
        $lcEyebrow = $cms['eyebrow'] ?? 'Learning space';
        $lcTitle = $cms['title'] ?? 'Learning <em>Corner</em>';
        $lcLede = $cms['lede'] ?? 'Explore short modules, videos, posters, flipbooks and training material for strengthening SBC practice.';
        $lcStats = [
            ['value' => count($mainCategories), 'label' => 'Topics'],
            ['value' => collect($mainCategories)->sum(fn ($main) => count($main->children)), 'label' => 'Subtopics'],
            ['value' => array_sum($counts->all()), 'label' => 'Resources'],
        ];

        return $this->pageView('pages.learning-corner.index')->with(compact(
            'mainCategories',
            'lcEyebrow',
            'lcTitle',
            'lcLede',
            'lcStats',
        ));
    }

    public function learningCornerMain(int $main)
    {
        $mainCat = DB::table('learning_cat')
            ->where('id', $main)
            ->whereNull('parent_id')
            ->where('status', 1)
            ->first();

        if (! $mainCat) {
            abort(404);
        }

        $resourceCounts = DB::table('learning_corner')
            ->where('status', 1)
            ->selectRaw('cat_id, count(*) as total')
            ->groupBy('cat_id')
            ->pluck('total', 'cat_id');

        $subcategories = DB::table('learning_cat')
            ->where('parent_id', $main)
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('cat_name')
            ->get()
            ->map(function ($sub) use ($resourceCounts) {
                $sub->resource_count = (int) ($resourceCounts[$sub->id] ?? 0);

                return $sub;
            });

        $main = $mainCat;
        $lcEyebrow = 'Learning space';
        $lcTitle = e($main->cat_name);
        $lcLede = trim((string) ($main->description ?? '')) ?: 'Choose a subtopic to browse learning resources.';
        $lcStats = [
            ['value' => $subcategories->count(), 'label' => 'Subtopics'],
            ['value' => $subcategories->sum('resource_count'), 'label' => 'Resources'],
        ];

        return $this->pageView('pages.learning-corner.subcategories')->with(compact(
            'main',
            'subcategories',
            'lcEyebrow',
            'lcTitle',
            'lcLede',
            'lcStats',
        ));
    }

    public function learningCornerSub(int $main, int $sub)
    {
        $mainCat = DB::table('learning_cat')
            ->where('id', $main)
            ->whereNull('parent_id')
            ->where('status', 1)
            ->first();

        $subCat = DB::table('learning_cat')
            ->where('id', $sub)
            ->where('parent_id', $main)
            ->where('status', 1)
            ->first();

        if (! $mainCat || ! $subCat) {
            abort(404);
        }

        $resources = DB::table('learning_corner')
            ->join('learning_cat', 'learning_corner.cat_id', '=', 'learning_cat.id')
            ->where('learning_corner.cat_id', $sub)
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
            $row->image_url = MediaUrl::tryResolve('learning', (string) ($row->image ?? '')) ?? '';

            return $row;
        });

        $main = $mainCat;
        $sub = $subCat;

        $lcEyebrow = $main->cat_name;
        $lcTitle = e($sub->cat_name);
        $lcLede = trim((string) ($sub->description ?? '')) ?: 'Learning resources for '.$sub->cat_name.'.';
        $lcStats = [
            ['value' => $resources->count(), 'label' => 'Resources'],
        ];

        return $this->pageView('pages.learning-corner.resources')->with(compact(
            'main',
            'sub',
            'resources',
            'lcEyebrow',
            'lcTitle',
            'lcLede',
            'lcStats',
        ));
    }

    private function buildLcTree($allCats, $counts): array
    {
        return $allCats
            ->whereNull('parent_id')
            ->values()
            ->map(function ($main) use ($allCats, $counts) {
                $children = $allCats
                    ->where('parent_id', $main->id)
                    ->values()
                    ->map(function ($sub) use ($counts) {
                        $subCount = (int) ($counts[$sub->id] ?? 0);

                        return (object) [
                            'id' => $sub->id,
                            'cat_name' => $sub->cat_name,
                            'cat_icon' => $sub->cat_icon ?? 'icon-folder',
                            'description' => $sub->description ?? '',
                            'children' => [],
                            'count' => $subCount,
                            'total_count' => $subCount,
                            'depth' => 1,
                        ];
                    })
                    ->all();

                $totalCount = collect($children)->sum('total_count');

                return (object) [
                    'id' => $main->id,
                    'cat_name' => $main->cat_name,
                    'cat_icon' => $main->cat_icon ?? 'icon-folder',
                    'description' => $main->description ?? '',
                    'children' => $children,
                    'count' => 0,
                    'total_count' => $totalCount,
                    'depth' => 0,
                ];
            })
            ->all();
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

        $pageTitle = $header['pageTitle'];
        $pageLede = $header['pageLede'];

        return $this->pageView('pages.reports')->with(compact('pageTitle', 'pageLede', 'reports'));
    }

    public function reportPreview(int $report)
    {
        $row = DB::table('reports')
            ->where('id', $report)
            ->where('status', 1)
            ->first();

        abort_unless($row, 404);

        $preview = ltrim((string) $row->preview_path, '/');
        abort_unless($preview !== '' && file_exists(public_path($preview)), 404);

        $reportTitle = $row->title;
        $reportType = $row->type ?: 'Report';
        $previewSrc = asset($preview);

        return view('pages.report-preview')->with(compact('reportTitle', 'reportType', 'previewSrc'));
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

        $magazineTitle = $report->title;
        $magazineType = $report->type ?: 'Report';
        $magazineSlug = $slug;
        $magazinePages = $pages;
        $magazineDownload = $report->download_path && file_exists(public_path(ltrim($report->download_path, '/')))
            ? asset(ltrim($report->download_path, '/'))
            : null;

        return view('pages.magazine-viewer')->with(compact(
            'magazineTitle',
            'magazineType',
            'magazineSlug',
            'magazinePages',
            'magazineDownload',
        ));
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

    /** Programs section — database, CMS, ya default cards tayyar karo */
    private function preparePrograms(array $cms = []): array
    {
        $programsUseSlider = false;
        $programsCards = [];
        $programsHeader = $this->programsHeader($cms);
        $programsDetailsUrl = route('programs');

        $accents = ['grad', 'orange', 'black'];
        $placeholders = [
            'https://www.chhattisgarhabc.org//public/uploads/programs/bapi-na-uwat.jpg',
            'https://www.chhattisgarhabc.org//public/uploads/programs/dhar-bhasha-kendra.jpg',
            'https://www.chhattisgarhabc.org//public/uploads/programs/shilp-khadi-kendra.jpg',
            'https://www.chhattisgarhabc.org//public/uploads/programs/yuvak-kendras.jpg',
            'https://www.chhattisgarhabc.org//public/uploads/programs/vikas-mitra.jpg',
        ];

        $rows = Cache::remember('home.programs', 3600, fn () => DB::table('programs')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['id', 'title', 'short_desc', 'full_desc', 'image']));

        if ($rows->count() > 0) {
            $programsUseSlider = true;

            foreach ($rows as $index => $program) {
                $short = trim((string) ($program->short_desc ?? ''));
                $full = trim((string) ($program->full_desc ?? ''));
                $description = $short;

                if ($full !== '' && $full !== $short) {
                    $description = $short !== '' ? $short.' '.$full : $full;
                }

                $imageUrl = MediaUrl::tryResolve('program', (string) ($program->image ?? '')) ?? '';
                if ($imageUrl === '') {
                    $imageUrl = $this->programPlaceholderImage($placeholders, $index);
                }

                $programsCards[] = [
                    'title' => $program->title ?? '',
                    'description' => $description,
                    'image_url' => $imageUrl,
                    'accent' => $accents[$index % 3],
                    'is_active' => $index === 0,
                    'delay' => 0,
                ];
            }
        } 
        // elseif (! empty($cms['cards'])) {
        //     $programsUseSlider = true;

        //     foreach ($cms['cards'] as $index => $card) {
        //         $description = trim((string) ($card['lede'] ?? ''));

        //         if (! empty($card['paragraphs'])) {
        //             $extra = trim(implode(' ', array_map('trim', $card['paragraphs'])));
        //             $description = $description !== '' ? $description.' '.$extra : $extra;
        //         }

        //         $programsCards[] = [
        //             'title' => $card['title'] ?? '',
        //             'description' => $description,
        //             'image_url' => $card['image_url'] ?? $this->programPlaceholderImage($placeholders, $index),
        //             'accent' => $accents[$index % 3],
        //             'is_active' => $index === 0,
        //             'delay' => (int) ($card['aos_delay'] ?? $index * 80),
        //         ];
        //     }
        // } else {
        //     foreach (config('media.home_programs.defaults', []) as $index => $item) {
        //         $imgIndex = (int) ($item['placeholder_index'] ?? $index);

        //         $programsCards[] = [
        //             'title' => $item['title'] ?? '',
        //             'description' => $item['description'] ?? '',
        //             'image_url' => $this->programPlaceholderImage($placeholders, $imgIndex),
        //             'accent' => $item['accent'] ?? $accents[$index % 3],
        //             'is_active' => false,
        //             'delay' => $index * 80,
        //         ];
        //     }
        // }

        return compact('programsUseSlider', 'programsHeader', 'programsCards', 'programsDetailsUrl');
    }

    /** Placeholder image — empty array pe crash na ho */
    private function programPlaceholderImage(array $placeholders, int $index): string
    {
        $total = count($placeholders);
        if ($total === 0) {
            return '';
        }

        return $placeholders[$index % $total] ?? '';
    }

    /** Programs section ke liye common header values */
    private function programsHeader(array $cms = []): array
    {
        $defaultLede = 'A focused view of flagship SBC initiatives across Chhattisgarh — from youth volunteer networks to local learning resources and community led campaigns.';

        return [
            'chapter_num' => $cms['chapter_num'] ?? '03',
            'chapter_label' => $cms['chapter_label'] ?? 'Programs & Initiatives',
            'heading_html' => $cms['heading_html'] ?? 'Programs &amp; Initiatives',
            'lede_text' => strip_tags($cms['lede_html'] ?? $defaultLede),
            'explore_url' => $cms['explore_url'] ?? route('programs'),
            'explore_label' => $cms['explore_label'] ?? 'Explore More',
        ];
    }

    private function cachedHomeInsights()
    {
        $rows = Cache::remember('home.insights', 3600, fn () => DB::table('insights')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['id', 'title', 'tag', 'description', 'image', 'link_text', 'link_url']));

        return $rows->map(function ($row) {
            $row->image_url = MediaUrl::tryResolve('insights', (string) ($row->image ?? '')) ?? '';

            return $row;
        });
    }

    private function cachedHomeBanners()
    {
        $rows = Cache::remember('home.banners', 3600, fn () => DB::table('banner')
            ->orderByDesc('id')
            ->get(['id', 'dbannerimg', 'mbannerimg', 'ytlink', 'redirect']));

        return $rows->map(function ($row) {
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
    }
}
