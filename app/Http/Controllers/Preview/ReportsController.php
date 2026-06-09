<?php

namespace App\Http\Controllers\Preview;

use App\Http\Controllers\Controller;

use App\Models\Page;
use App\Models\PageSection;
use App\Models\Report;
use App\Support\SocialMetaResolver;

class ReportsController extends Controller
{
    public function index()
    {
        $metaTitle = 'Reports and Insights · ChhattisgarhABC';
        $metaDescription = 'Reports, newsletters and success stories documenting social and behaviour change work across Chhattisgarh.';
        $pageTitle = 'Reports and Insights';
        $pageLede = 'Read reports, newsletters and success stories that document social and behaviour change work across Chhattisgarh.';

        $page = Page::where('route_name', 'reports')->where('is_active', 1)->first();
        if ($page) {
            if ($page->meta_title) {
                $metaTitle = $page->meta_title;
            }
            if ($page->meta_description) {
                $metaDescription = $page->meta_description;
            }
            $header = PageSection::where('page_id', $page->id)->where('section_key', 'page_header')->where('is_active', 1)->first();
            if ($header) {
                $cms = json_decode((string) $header->content, true);
                if (is_array($cms)) {
                    $pageTitle = $cms['pageTitle'] ?? $pageTitle;
                    $pageLede = $cms['pageLede'] ?? $pageLede;
                }
            }
        }

        $rows = Report::where('status', 1)->orderBy('sort_order')->orderBy('id')->get();
        $reports = [];

        foreach ($rows as $row) {
            $cover = ltrim((string) $row->cover_path, '/');
            $preview = ltrim((string) $row->preview_path, '/');
            $download = ltrim((string) $row->download_path, '/');
            $flipbookSlug = trim((string) ($row->flipbook_slug ?? ''));
            $flipbookPages = (int) ($row->flipbook_pages ?? 0);
            $hasFlipbook = $flipbookSlug !== ''
                && $flipbookPages > 0
                && is_dir(public_path('report-flipbooks/'.$flipbookSlug.'/pages'));

            $coverExists = $cover !== '' && file_exists(public_path($cover));
            $previewExists = $preview !== '' && file_exists(public_path($preview));
            $downloadExists = $download !== '' && file_exists(public_path($download));

            if ($hasFlipbook) {
                $previewUrl = route('preview.magazine', ['slug' => $flipbookSlug]);
                $previewExists = true;
            } else {
                $previewUrl = $previewExists ? route('preview.reports.preview', ['report' => $row->id]) : '#reports-title';
            }

            $reports[] = [
                'id' => $row->id,
                'title' => $row->title,
                'type' => $row->type ?: 'Report',
                'cover' => $coverExists ? asset($cover) : '',
                'preview_url' => $previewUrl,
                'download_url' => $downloadExists ? asset($download) : '#reports-title',
                'has_flipbook' => $hasFlipbook,
                'flipbook_pages' => $hasFlipbook ? $flipbookPages : 0,
                'cover_exists' => $coverExists,
                'preview_exists' => $previewExists,
                'download_exists' => $downloadExists,
            ];
        }

        if ($reports === [] && $page) {
            $grid = PageSection::where('page_id', $page->id)->where('section_key', 'reports_grid')->where('is_active', 1)->first();
            if ($grid) {
                $cms = json_decode((string) $grid->content, true);
                foreach ($cms['reports'] ?? [] as $item) {
                    $cover = ltrim((string) ($item['cover'] ?? ''), '/');
                    $preview = ltrim((string) ($item['preview'] ?? ''), '/');
                    $download = ltrim((string) ($item['download'] ?? ''), '/');
                    $reports[] = [
                        'title' => $item['title'] ?? '',
                        'type' => $item['type'] ?? 'Report',
                        'cover' => $cover !== '' && file_exists(public_path($cover)) ? asset($cover) : '',
                        'preview_url' => $preview !== '' && file_exists(public_path($preview)) ? asset($preview) : '#reports-title',
                        'download_url' => $download !== '' && file_exists(public_path($download)) ? asset($download) : '#reports-title',
                        'has_flipbook' => false,
                        'cover_exists' => $cover !== '' && file_exists(public_path($cover)),
                        'preview_exists' => $preview !== '' && file_exists(public_path($preview)),
                        'download_exists' => $download !== '' && file_exists(public_path($download)),
                    ];
                }
            }
        }

        return view('preview::reports.index', compact('metaTitle', 'metaDescription', 'pageTitle', 'pageLede', 'reports'));
    }

    public function preview(int $report)
    {
        $row = Report::where('id', $report)->where('status', 1)->first();
        abort_unless($row, 404);

        $preview = ltrim((string) $row->preview_path, '/');
        abort_unless($preview !== '' && file_exists(public_path($preview)), 404);

        $reportTitle = $row->title;
        $reportType = $row->type ?: 'Report';
        $previewSrc = asset($preview);
        $cover = ltrim((string) $row->cover_path, '/');
        $reportCover = $cover !== '' && file_exists(public_path($cover)) ? asset($cover) : '';

        $socialMeta = app(SocialMetaResolver::class)->resolve(request(), compact(
            'reportTitle',
            'reportType',
            'previewSrc',
            'reportCover',
        ));

        return view('preview::reports.preview', compact('reportTitle', 'reportType', 'previewSrc', 'reportCover', 'socialMeta'));
    }

    public function magazine(string $slug)
    {
        $row = Report::where('status', 1)->where('flipbook_slug', $slug)->first();
        abort_unless($row, 404);

        $pagesDir = public_path('report-flipbooks/'.$slug.'/pages');
        abort_unless(is_dir($pagesDir), 404);

        $total = (int) ($row->flipbook_pages ?? 0);
        abort_if($total <= 0, 404);

        $magazinePages = [];
        for ($i = 1; $i <= $total; $i++) {
            $relative = 'report-flipbooks/'.$slug.'/pages/'.$i.'.jpg';
            if (file_exists(public_path($relative))) {
                $magazinePages[] = asset($relative);
            }
        }

        abort_if($magazinePages === [], 404);

        $downloadPath = ltrim((string) $row->download_path, '/');
        $magazineTitle = $row->title;
        $magazineType = $row->type ?: 'Report';
        $magazineSlug = $slug;
        $magazineDownload = $downloadPath !== '' && file_exists(public_path($downloadPath))
            ? asset($downloadPath)
            : null;

        $socialMeta = app(SocialMetaResolver::class)->resolve(request(), compact(
            'magazineTitle',
            'magazineType',
            'magazineSlug',
            'magazinePages',
            'magazineDownload',
        ));

        return view('preview::reports.magazine', compact(
            'magazineTitle',
            'magazineType',
            'magazineSlug',
            'magazinePages',
            'magazineDownload',
            'socialMeta',
        ));
    }
}
