<?php

namespace App\Services;

use App\Support\MediaUrl;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BlogStoryService
{
    private ?array $cachedDistrictNames = null;

    public function publishedCount(): int
    {
        return Cache::remember('blog.published_count', 300, fn () => (int) DB::table('blog')->where('status', 1)->count());
    }

    public function filters(): array
    {
        return Cache::remember('blog.filters', 300, function () {
            $categories = DB::table('blog')
                ->join('categories', 'categories.id', '=', 'blog.cat_id')
                ->where('blog.status', 1)
                ->select('categories.category_name')
                ->distinct()
                ->orderBy('categories.category_name')
                ->pluck('category_name');

            $categoryOptions = [['value' => 'all', 'label' => 'All stories']];

            foreach ($categories as $name) {
                $categoryOptions[] = [
                    'value' => $this->categoryValue($name),
                    'label' => $name,
                ];
            }

            return [
                'categories' => $categoryOptions,
                'districts' => [
                    ['value' => 'all', 'label' => 'All districts'],
                ],
                'count' => $this->publishedCount(),
            ];
        });
    }

    public function paginatedListing(int $perPage = 20): LengthAwarePaginator
    {
        $paginator = $this->publishedQuery()->paginate($perPage)->withQueryString();
        $startIndex = ($paginator->currentPage() - 1) * $perPage;

        $cards = $paginator->getCollection()->values()->map(function ($row, $index) use ($startIndex) {
            return $this->formatCard($row, $startIndex + $index);
        });

        return $paginator->setCollection($cards);
    }

    public function recentForSidebar(int $limit = 6): array
    {
        return Cache::remember('blog.recent.sidebar.'.$limit, 300, fn () => $this->publishedQuery()
            ->limit($limit)
            ->get([
                'blog.id',
                'blog.title',
                'blog.date_created',
                'categories.category_name',
                'blog.admin',
                'users.email as author_email',
            ])
            ->map(fn ($row) => $this->formatRecent($row))
            ->all());
    }

    public function recentForHome(int $limit = 6): array
    {
        return Cache::remember('blog.recent.home.'.$limit, 300, fn () => $this->publishedQuery()
            ->limit($limit)
            ->get()
            ->values()
            ->map(fn ($row, $index) => $this->formatHomeChampion($row, $index))
            ->all());
    }

    private function publishedQuery()
    {
        return DB::table('blog')
            ->join('categories', 'categories.id', '=', 'blog.cat_id')
            ->leftJoin('users', 'users.id', '=', 'blog.user_id')
            ->where('blog.status', 1)
            ->orderByDesc('blog.date_created')
            ->orderByDesc('blog.id')
            ->select([
                'blog.id',
                'blog.title',
                'blog.content',
                'blog.tag',
                'blog.image',
                'blog.admin',
                'blog.date_created',
                'categories.category_name',
                'users.email as author_email',
            ]);
    }

    public function findPublishedBySlug(string $slug): ?object
    {
        if (preg_match('/^(\d+)(?:-|$)/', $slug, $matches)) {
            $row = DB::table('blog')
                ->join('categories', 'categories.id', '=', 'blog.cat_id')
                ->leftJoin('users', 'users.id', '=', 'blog.user_id')
                ->where('blog.id', (int) $matches[1])
                ->where('blog.status', 1)
                ->first([
                    'blog.id',
                    'blog.title',
                    'blog.content',
                    'blog.tag',
                    'blog.image',
                    'blog.admin',
                    'blog.date_created',
                    'categories.category_name',
                    'users.email as author_email',
                ]);

            if ($row && $this->slugForRow($row) === $slug) {
                return $row;
            }
        }

        $candidates = DB::table('blog')
            ->join('categories', 'categories.id', '=', 'blog.cat_id')
            ->leftJoin('users', 'users.id', '=', 'blog.user_id')
            ->where('blog.status', 1)
            ->get([
                'blog.id',
                'blog.title',
                'blog.content',
                'blog.tag',
                'blog.image',
                'blog.admin',
                'blog.date_created',
                'categories.category_name',
                'users.email as author_email',
            ]);

        return $candidates->first(fn ($row) => $this->slugForRow($row) === $slug);
    }

    public function slugForRow(object $row): string
    {
        return $row->id.'-'.Str::slug($row->title);
    }

    public function findStoryForPage(string $slug): ?array
    {
        $blogRow = $this->findPublishedBySlug($slug);

        if ($blogRow) {
            return $this->formatStoryForDetail($blogRow);
        }

        $archivedRow = $this->findArchivedStoryRow($slug);

        if ($archivedRow) {
            return $this->resolveArchivedStoryRecord($archivedRow);
        }

        $fallback = $this->fallbackStoryPages();

        return $fallback[$slug] ?? null;
    }

    public function relatedStoriesForPage(array $slugs): array
    {
        $related = [];

        foreach ($slugs as $slug) {
            $story = $this->findStoryForPage($slug);

            if ($story) {
                $related[] = $story;
            }
        }

        return $related;
    }

    public function formatStoryForDetail(object $row): array
    {
        $publishedDate = Carbon::parse($row->date_created);
        $content = trim(strip_tags(html_entity_decode((string) $row->content, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
        $lede = Str::limit($content !== '' ? $content : $row->title, 180);
        $readMinutes = max(3, (int) ceil(str_word_count($content) / 180));
        $author = $this->authorName($row);
        $tags = $this->tagList((string) $row->tag);

        return [
            'slug' => $this->slugForRow($row),
            'category' => $row->category_name,
            'theme' => $tags[0] ?? $row->category_name,
            'district' => 'Chhattisgarh',
            'published_label' => $publishedDate->format('F Y'),
            'read_time' => $readMinutes.'-min read',
            'title' => $row->title,
            'lede' => $lede,
            'hero_image' => $this->imageUrl((string) $row->image),
            'author' => $author,
            'author_initials' => $this->authorInitials($author),
            'author_role' => 'Story archive',
            'hero_caption' => $row->category_name,
            'why_it_matters' => $lede,
            'intro' => $content !== '' ? $content : $lede,
            'highlights' => [
                'Published in '.$row->category_name.'.',
                'Added on '.$publishedDate->format('d M Y').'.',
            ],
            'stats' => [
                ['value' => $publishedDate->format('d M'), 'label' => 'Published'],
                ['value' => $readMinutes.' min', 'label' => 'Estimated read'],
                ['value' => $row->category_name, 'label' => 'Category'],
            ],
            'quote' => [
                'text' => $lede,
                'cite' => $author,
            ],
            'sections' => [
                [
                    'title' => $row->title,
                    'paragraphs' => [$content !== '' ? $content : $lede],
                ],
            ],
            'timeline' => [
                [
                    'label' => $publishedDate->format('M Y'),
                    'title' => 'Published',
                    'text' => 'This story was added to the public archive.',
                ],
            ],
            'related_slugs' => [],
        ];
    }

    private function formatCard(object $row, int $index): array
    {
        $content = trim(strip_tags(html_entity_decode((string) $row->content, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
        $lede = Str::limit($content !== '' ? $content : $row->title, 140);
        $date = Carbon::parse($row->date_created);
        $readMinutes = max(3, (int) ceil(str_word_count($content) / 180));
        $author = $this->authorName($row);
        $tags = $this->tagList((string) $row->tag);
        $themeSlug = ! empty($tags) ? Str::slug($tags[0]) : $this->categoryValue($row->category_name);

        return [
            'classes' => $this->cardClass($index),
            'aos_delay' => $index % 4 === 0 ? null : (string) (($index % 4) * 60),
            'category' => $this->categoryValue($row->category_name),
            'theme' => $themeSlug,
            'district' => 'all',
            'date' => $date->format('Y-m-d'),
            'image' => $this->imageUrl((string) $row->image),
            'cat_label' => $row->category_name,
            'meta' => e($author).' &nbsp;·&nbsp; '.$readMinutes.'-min read',
            'title' => e($row->title),
            'lede' => $lede,
            'tags' => $tags,
            'url' => route('stories.show', $this->slugForRow($row)),
        ];
    }

    private function formatRecent(object $row): array
    {
        $date = Carbon::parse($row->date_created);
        $author = $this->authorName($row);

        return [
            'title' => $row->title,
            'url' => route('stories.show', $this->slugForRow($row)),
            'author' => $author,
            'category' => $row->category_name,
            'day' => $date->format('d'),
            'month' => Str::upper($date->format('M')),
            'date_iso' => $date->toDateString(),
        ];
    }

    private function formatHomeChampion(object $row, int $index): array
    {
        $content = trim(strip_tags(html_entity_decode((string) $row->content, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
        $blurb = Str::limit($content !== '' ? $content : $row->title, 160);
        $date = Carbon::parse($row->date_created);

        return [
            'image' => $this->imageUrl((string) $row->image),
            'pill' => $date->format('j F Y'),
            'pill_style' => null,
            'where' => 'Filed: '.Str::title(strtolower($this->storyLocation($row))),
            'title' => $row->title,
            'blurb' => $blurb,
            'url' => route('stories.show', $this->slugForRow($row)),
        ];
    }

    private function storyLocation(object $row): string
    {
        $location = trim((string) ($row->location ?? ''));
        if ($location !== '') {
            return $location;
        }

        $haystack = strtoupper(
            (string) $row->title.' '.
            strip_tags(html_entity_decode((string) ($row->content ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8')).' '.
            (string) ($row->tag ?? '')
        );

        foreach ($this->districtNames() as $name) {
            if (str_contains($haystack, strtoupper($name))) {
                return $name;
            }
        }

        return 'Chhattisgarh';
    }

    private function districtNames(): array
    {
        if ($this->cachedDistrictNames !== null) {
            return $this->cachedDistrictNames;
        }

        $this->cachedDistrictNames = Cache::remember('blog.district_names', 86400, fn () => DB::table('district')
            ->where('status', 1)
            ->orderByRaw('CHAR_LENGTH(district_name) DESC')
            ->pluck('district_name')
            ->all());

        return $this->cachedDistrictNames;
    }

    private function cardClass(int $index): string
    {
        $variants = [
            'st-card st-card--med st-card--nutrition',
            'st-card st-card--small st-card--children',
            'st-card st-card--small st-card--males',
            'st-card st-card--med st-card--gender',
        ];

        return $variants[$index % count($variants)];
    }

    private function categoryValue(string $name): string
    {
        return Str::slug($name);
    }

    private function imageUrl(string $image): string
    {
        return MediaUrl::resolve('story', $image);
    }

    private function findArchivedStoryRow(string $slug): ?object
    {
        if (! $this->archivedStoriesTableExists()) {
            return null;
        }

        $query = DB::table('stories')
            ->leftJoin('users', 'users.id', '=', 'stories.created_by')
            ->where('stories.slug', $slug)
            ->where('stories.category', '!=', 'Events');

        $this->scopePublicArchivedStories($query);

        return $query->first([
            'stories.title',
            'stories.slug',
            'stories.category',
            'stories.content',
            'stories.tag',
            'stories.thumbnail_path',
            'stories.payload',
            'stories.created_at',
            'users.email as author_email',
        ]);
    }

    private function resolveArchivedStoryRecord(object $row): array
    {
        $payload = json_decode((string) ($row->payload ?? ''), true);

        if (is_array($payload) && isset($payload['title'])) {
            return $payload;
        }

        return $this->formatArchivedStoryRow($row);
    }

    private function formatArchivedStoryRow(object $story): array
    {
        $publishedDate = Carbon::parse($story->created_at);
        $content = trim(strip_tags((string) $story->content));
        $lede = Str::limit($content !== '' ? $content : $story->title, 180);
        $readMinutes = max(3, (int) ceil(str_word_count($content) / 180));
        $author = ! empty($story->author_email)
            ? Str::before($story->author_email, '@')
            : 'ChhattisgarhABC';

        return [
            'slug' => $story->slug,
            'category' => $story->category,
            'theme' => ! empty($story->tag) ? $story->tag : $story->category,
            'district' => 'Chhattisgarh',
            'published_label' => $publishedDate->format('F Y'),
            'read_time' => $readMinutes.'-min read',
            'title' => $story->title,
            'lede' => $lede,
            'hero_image' => MediaUrl::resolve('story', (string) ($story->thumbnail_path ?? '')),
            'author' => $author,
            'author_initials' => $this->authorInitials($author),
            'author_role' => 'Story archive',
            'hero_caption' => 'Recent story from the ChhattisgarhABC archive.',
            'why_it_matters' => $lede,
            'intro' => $content !== '' ? $content : $lede,
            'highlights' => [
                'Published in '.$story->category.'.',
                'Added to the public story archive on '.$publishedDate->format('d M Y').'.',
            ],
            'stats' => [
                ['value' => $publishedDate->format('d M'), 'label' => 'Published'],
                ['value' => $readMinutes.' min', 'label' => 'Estimated read'],
                ['value' => 'Story', 'label' => 'Archive type'],
            ],
            'quote' => [
                'text' => $lede,
                'cite' => 'ChhattisgarhABC story archive',
            ],
            'sections' => [
                [
                    'title' => 'Story note',
                    'paragraphs' => [$content !== '' ? $content : $lede],
                ],
            ],
            'timeline' => [
                [
                    'label' => $publishedDate->format('M Y'),
                    'title' => 'Published in the archive',
                    'text' => 'This story was added for readers following behaviour-change work across Chhattisgarh.',
                ],
            ],
            'related_slugs' => [],
        ];
    }

    private function archivedStoriesTableExists(): bool
    {
        return Cache::remember('schema.stories.exists', 86400, fn () => Schema::hasTable('stories'));
    }

    private function scopePublicArchivedStories($query)
    {
        $hasApproval = Cache::remember('schema.stories.has_approval', 86400, fn () => Schema::hasColumn('stories', 'approval_status'));

        if ($hasApproval) {
            $query->where('stories.approval_status', 'approved');
        }

        return $query->whereIn('stories.status', ['active', '1']);
    }

    private function fallbackStoryPages(): array
    {
        $path = database_path('seeders/data/story_pages.php');

        if (! is_file($path)) {
            return [];
        }

        $stories = require $path;

        return is_array($stories) ? $stories : [];
    }

    private function authorName(object $row): string
    {
        if (! empty($row->author_email)) {
            return Str::before($row->author_email, '@');
        }

        if (! empty($row->admin)) {
            return $row->admin;
        }

        return 'ChhattisgarhABC';
    }

    private function authorInitials(string $author): string
    {
        $parts = preg_split('/\s+/', trim($author)) ?: [];

        if (count($parts) >= 2) {
            return Str::upper(Str::substr($parts[0], 0, 1).Str::substr($parts[1], 0, 1));
        }

        return Str::upper(Str::substr($author, 0, 2));
    }

    private function tagList(string $tag): array
    {
        if ($tag === '') {
            return [];
        }

        return Collection::make(explode(',', $tag))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}
