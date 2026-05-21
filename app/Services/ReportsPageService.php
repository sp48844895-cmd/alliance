<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ReportsPageService
{
    public function listingForPage(): array
    {
        $rows = DB::table('reports')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get([
                'title',
                'type',
                'cover_path',
                'preview_path',
                'download_path',
            ]);

        if ($rows->isEmpty()) {
            return [];
        }

        $reports = [];

        foreach ($rows as $row) {
            $reports[] = $this->formatRow($row);
        }

        return $reports;
    }

    private function formatRow(object $row): array
    {
        $cover = ltrim((string) $row->cover_path, '/');
        $preview = ltrim((string) $row->preview_path, '/');
        $download = ltrim((string) $row->download_path, '/');

        $coverExists = $cover !== '' && file_exists(public_path($cover));
        $previewExists = $preview !== '' && file_exists(public_path($preview));
        $downloadExists = $download !== '' && file_exists(public_path($download));

        return [
            'title' => $row->title,
            'type' => $row->type ?: 'Report',
            'cover' => $cover,
            'preview' => $preview,
            'download' => $download,
            'cover_exists' => $coverExists,
            'preview_exists' => $previewExists,
            'download_exists' => $downloadExists,
            'preview_url' => $previewExists ? asset($preview) : '#reports-title',
            'download_url' => $downloadExists ? asset($download) : '#reports-title',
        ];
    }
}
