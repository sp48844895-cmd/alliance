<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoryPublicationService
{
    public function __construct(private BlogStoryService $blogStories)
    {
    }

    public function publish(object $story): void
    {
        DB::transaction(function () use ($story) {
            $author = DB::table('users')
                ->select('fname', 'lname', 'email')
                ->where('id', $story->created_by)
                ->first();

            $authorName = trim(($author->fname ?? '') . ' ' . ($author->lname ?? ''));
            if ($authorName === '') {
                $authorName = ! empty($author->email)
                    ? Str::before($author->email, '@')
                    : 'Contributor';
            }

            $blogId = (int) ($story->published_blog_id ?? 0);
            $title = $this->uniqueBlogTitle((string) $story->title, $blogId > 0 ? $blogId : null);

            $blogData = [
                'cat_id'       => $this->resolveCategoryId((string) $story->category),
                'title'        => $title,
                'content'      => (string) $story->content,
                'tag'          => (string) ($story->tag ?? ''),
                'location'     => (string) ($story->location ?? ''),
                'admin'        => $authorName,
                'user_id'      => (int) ($story->created_by ?? 1),
                'status'       => 1,
                'image'        => $this->blogImageName((string) ($story->thumbnail_path ?? '')),
                'date_updated' => now(),
            ];

            if ($blogId > 0 && DB::table('blog')->where('id', $blogId)->exists()) {
                DB::table('blog')->where('id', $blogId)->update($blogData);
            } else {
                $blogData['rate'] = 0;
                $blogData['views'] = '0';
                $blogData['date_created'] = $story->approved_at
                    ? Carbon::parse($story->approved_at)->toDateString()
                    : now()->toDateString();
                $blogId = (int) DB::table('blog')->insertGetId($blogData);
                DB::table('stories')->where('id', $story->id)->update([
                    'published_blog_id' => $blogId,
                    'updated_at'        => now(),
                ]);
            }

            $this->blogStories->clearPublishedCache();
        });
    }

    public function unpublish(?int $blogId): void
    {
        if (! $blogId) {
            return;
        }

        DB::table('blog')->where('id', $blogId)->update([
            'status'       => 0,
            'date_updated' => now(),
        ]);

        $this->blogStories->clearPublishedCache();
    }

    public function syncApprovedWithoutBlog(): int
    {
        $rows = DB::table('stories')
            ->where('approval_status', 'approved')
            ->where('category', '!=', 'Events')
            ->whereNull('published_blog_id')
            ->get();

        foreach ($rows as $story) {
            $this->publish($story);
        }

        return $rows->count();
    }

    private function resolveCategoryId(string $categoryName): int
    {
        $id = DB::table('categories')
            ->where('category_name', $categoryName)
            ->where('status', 1)
            ->value('id');

        if ($id) {
            return (int) $id;
        }

        $fallback = DB::table('categories')
            ->where('status', 1)
            ->orderBy('id')
            ->value('id');

        return (int) ($fallback ?? 1);
    }

    private function uniqueBlogTitle(string $title, ?int $exceptBlogId): string
    {
        $base = Str::limit(trim($title), 255, '');
        $candidate = $base;
        $suffix = 2;

        while (true) {
            $query = DB::table('blog')->where('title', $candidate);
            if ($exceptBlogId) {
                $query->where('id', '!=', $exceptBlogId);
            }
            if (! $query->exists()) {
                return $candidate;
            }
            $candidate = Str::limit($base . ' (' . $suffix . ')', 255, '');
            $suffix++;
        }
    }

    private function blogImageName(string $thumbnailPath): string
    {
        $thumbnailPath = trim($thumbnailPath);
        if ($thumbnailPath === '') {
            return '';
        }

        if (Str::contains($thumbnailPath, '/')) {
            return basename($thumbnailPath);
        }

        return $thumbnailPath;
    }
}
