<?php

namespace App\Http\Controllers\Preview;

use App\Http\Controllers\Controller;

use App\Models\Page;
use App\Models\PageSection;

class GetInvolvedController extends Controller
{
    public function index()
    {
        $metaTitle = 'Get Involved · ChhattisgarhABC';
        $metaDescription = 'Four pathways to join the alliance — guest, intern, fellowship or organisation partner.';
        $sections = [];

        $page = Page::where('route_name', 'get-involved')->where('is_active', 1)->first();

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

        $guestUrl = route('preview.register.guest');
        $internUrl = route('preview.register.intern');
        $fellowUrl = route('preview.register.fellow');
        $contactUrl = route('preview.contact');

        return view('preview::get-involved.index', compact(
            'sections',
            'metaTitle',
            'metaDescription',
            'guestUrl',
            'internUrl',
            'fellowUrl',
            'contactUrl'
        ));
    }
}
