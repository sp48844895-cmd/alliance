<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PageContentService
{
    private const CACHE_KEY = 'page_content.all';

    public function forRoute(?string $routeName): ?array
    {
        if ($routeName === null || $routeName === '') {
            return null;
        }

        $pages = $this->all();

        return $pages[$routeName] ?? null;
    }

    public function section(string $routeName, string $sectionKey, array $default = []): array
    {
        $page = $this->forRoute($routeName);

        if ($page === null) {
            return $default;
        }

        return $page['sections'][$sectionKey] ?? $default;
    }

    public function jumbotron(string $routeName, array $overrides = []): ?array
    {
        $jumbotron = $this->section($routeName, 'jumbotron');

        if ($jumbotron === []) {
            return null;
        }

        return array_replace_recursive($jumbotron, $overrides);
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    private function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            $rows = DB::table('pages')
                ->leftJoin('page_sections', function ($join) {
                    $join->on('page_sections.page_id', '=', 'pages.id')
                        ->where('page_sections.is_active', true);
                })
                ->where('pages.is_active', true)
                ->orderBy('pages.sort_order')
                ->orderBy('page_sections.sort_order')
                ->get([
                    'pages.id as page_id',
                    'pages.slug',
                    'pages.route_name',
                    'pages.title',
                    'pages.meta_title',
                    'pages.meta_description',
                    'page_sections.section_key',
                    'page_sections.section_type',
                    'page_sections.content',
                    'page_sections.sort_order as section_sort',
                ]);

            $pages = [];

            foreach ($rows as $row) {
                $routeName = $row->route_name ?? $row->slug;

                if (! isset($pages[$routeName])) {
                    $pages[$routeName] = [
                        'id' => $row->page_id,
                        'slug' => $row->slug,
                        'route_name' => $row->route_name,
                        'title' => $row->title,
                        'meta_title' => $row->meta_title,
                        'meta_description' => $row->meta_description,
                        'sections' => [],
                    ];
                }

                if ($row->section_key === null) {
                    continue;
                }

                $content = json_decode((string) $row->content, true);

                $pages[$routeName]['sections'][$row->section_key] = is_array($content) ? $content : [];
            }

            return $pages;
        });
    }
}
