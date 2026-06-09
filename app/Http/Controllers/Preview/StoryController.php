<?php

namespace App\Http\Controllers\Preview;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use App\Services\BlogStoryService;
use App\Services\PageContentService;

class StoryController extends Controller
{
    public function __construct(private BlogStoryService $stories) {}

    public function index()
    {
        $pageContent = app(PageContentService::class)->forRoute('stories');
        $metaTitle = $pageContent['meta_title'] ?? 'Impact Stories · ChhattisgarhABC';
        $metaDescription = $pageContent['meta_description'] ?? 'Stories from the field across Chhattisgarh — behaviour change told by the people who lived it.';

        $sections = [];
        $videos = [];
        $storyGrid = ['empty' => ['chapter' => '—', 'chapter_suffix' => 'No stories yet', 'text' => 'No stories match your filters.']];

        $page = Page::where('route_name', 'stories')->where('is_active', 1)->first();
        if ($page) {
            $sectionRows = PageSection::where('page_id', $page->id)->where('is_active', 1)->orderBy('sort_order')->get();
            foreach ($sectionRows as $row) {
                $content = json_decode((string) $row->content, true);
                $sections[$row->section_key] = is_array($content) ? $content : [];
            }
            $videos = $sections['videos'] ?? [];
            $storyGrid = $sections['story_grid'] ?? $storyGrid;
        }

        $filters = $this->stories->filters();
        $storyPaginator = $this->stories->paginatedListing(20);
        $storyCount = $filters['count'];

        return view('preview::stories.index', compact(
            'metaTitle',
            'metaDescription',
            'sections',
            'filters',
            'storyPaginator',
            'videos',
            'storyGrid',
            'storyCount'
        ));
    }

    public function show(string $slug)
    {
        $story = $this->stories->findStoryForPage($slug);

        abort_unless($story, 404);

        $relatedStories = $this->stories->relatedStoriesForCurrentStory($story);
        $recentStories = $this->stories->recentForStorySidebar((int) ($story['id'] ?? 0));

        return view('preview::stories.show', compact('story', 'relatedStories', 'recentStories'));
    }
}
