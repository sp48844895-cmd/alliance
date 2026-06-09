<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PageSection;

class CampaignsController extends Controller
{
    public function index()
    {
        $metaTitle = 'Campaigns · ChhattisgarhABC';
        $metaDescription = 'Campaigns and behaviour change initiatives from ChhattisgarhABC across Chhattisgarh.';
        $sections = [];

        $page = Page::where('route_name', 'campaigns')->where('is_active', 1)->first();

        if ($page) {
            if ($page->meta_title) {
                $metaTitle = $page->meta_title;
            }
            if ($page->meta_description) {
                $metaDescription = $page->meta_description;
            }

            $rows = PageSection::where('page_id', $page->id)
                ->where('is_active', 1)
                ->orderBy('sort_order')
                ->get();

            foreach ($rows as $row) {
                $content = json_decode((string) $row->content, true);
                $sections[$row->section_key] = is_array($content) ? $content : [];
            }
        }

        return view('campaigns.index', compact('sections', 'metaTitle', 'metaDescription'));
    }
}
