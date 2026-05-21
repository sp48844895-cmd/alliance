<?php

namespace App\Services;

use App\Support\MediaUrl;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KnowledgeHubPageService
{
    private const CACHE_KEY = 'knowledge_hub.resources';

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function gridResources(): array
    {
        return Cache::remember(self::CACHE_KEY, 3600, fn () => $this->buildGridResources());
    }

    public function totalCount(): int
    {
        return count($this->gridResources());
    }

    public function featuredResource(): ?array
    {
        $grid = $this->gridResources();

        return $grid[0] ?? null;
    }

    private function buildGridResources(): array
    {
        $rows = DB::table('learning_corner')
            ->join('learning_cat', 'learning_corner.cat_id', '=', 'learning_cat.id')
            ->where('learning_cat.status', 1)
            ->orderByDesc('learning_corner.date')
            ->orderByDesc('learning_corner.id')
            ->get([
                'learning_corner.id',
                'learning_corner.title',
                'learning_corner.content',
                'learning_corner.image',
                'learning_corner.m_type',
                'learning_corner.link',
                'learning_corner.date',
                'learning_corner.admin',
                'learning_cat.cat_name',
            ]);

        $resources = [];

        foreach ($rows as $row) {
            $resources[] = $this->mapRow($row);
        }

        return $resources;
    }

    private function mapRow(object $row): array
    {
        $category = $this->categoryForMaterial((string) $row->m_type);
        $cover = MediaUrl::tryResolve('learning', (string) $row->image) ?? '';
        $link = trim((string) $row->link);
        $isCanva = $link !== '' && stripos($link, 'canva') !== false;
        $published = $this->formatDate((string) $row->date);
        $format = $this->formatLabel((string) $row->m_type, $isCanva);
        $keywords = Str::lower(implode(' ', array_filter([
            $row->title,
            $row->content,
            $row->cat_name,
            $row->m_type,
            $category,
        ])));

        return [
            'id' => 'lc-'.$row->id,
            'category' => $category,
            'keywords' => $keywords,
            'cover' => $cover,
            'format' => $format,
            'title' => $row->title,
            'description' => trim((string) $row->content) !== '' ? $row->content : $row->title,
            'meta' => array_values(array_filter([
                $row->cat_name,
                $published !== '' ? $published : null,
                $row->admin ?: null,
            ])),
            'canva' => $isCanva,
            'canva_url' => $isCanva ? $link : null,
            'download_url' => $link !== '' ? $link : '#',
            'ribbon' => $isCanva,
            'pages' => '',
            'size' => '',
            'langs' => '',
            'published' => $published,
            'downloads' => '',
            'detail' => [
                'title' => $row->title,
                'description' => trim((string) $row->content) !== '' ? $row->content : $row->title,
                'published' => $published,
                'canva' => $isCanva,
            ],
        ];
    }

    private function categoryForMaterial(string $mType): string
    {
        return match ($mType) {
            'book' => 'guide',
            'posters' => 'iec',
            'mobile kunji' => 'toolkit',
            'video' => 'report',
            default => 'guide',
        };
    }

    private function formatLabel(string $mType, bool $isCanva): string
    {
        if ($isCanva) {
            return 'CANVA';
        }

        return match ($mType) {
            'video' => 'VIDEO',
            'mobile kunji' => 'ZIP',
            default => 'PDF',
        };
    }

    private function formatDate(string $date): string
    {
        if ($date === '') {
            return '';
        }

        try {
            return Carbon::parse($date)->format('d M Y');
        } catch (\Throwable) {
            return '';
        }
    }
}
