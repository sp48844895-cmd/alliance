<?php

namespace App\Http\Controllers\Preview;

use App\Http\Controllers\Controller;

use App\Models\Page;
use App\Models\PageSection;
use App\Models\SbcPoolMember;

class ResourcesController extends Controller
{
    public function index()
    {
        $metaTitle = 'SBC Resource Pool · ChhattisgarhABC';
        $metaDescription = 'Meet the SBC Resource Pool supporting social and behaviour change learning across Chhattisgarh.';
        $sections = [];

        $page = Page::where('route_name', 'resources')->where('is_active', 1)->first();
        if ($page) {
            if ($page->meta_title) {
                $metaTitle = $page->meta_title;
            }
            if ($page->meta_description) {
                $metaDescription = $page->meta_description;
            }
            $sectionRows = PageSection::where('page_id', $page->id)->where('is_active', 1)->orderBy('sort_order')->get();
            foreach ($sectionRows as $row) {
                $content = json_decode((string) $row->content, true);
                $sections[$row->section_key] = is_array($content) ? $content : [];
            }
        }

        $rows = SbcPoolMember::where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $resourcePeople = [];
        foreach ($rows as $member) {
            $social = [];
            $platforms = [
                'facebook' => (string) $member->facebook,
                'twitter' => (string) $member->twitter,
                'linkedin' => (string) $member->linkedin,
                'instagram' => (string) $member->instagram,
            ];
            foreach ($platforms as $platform => $value) {
                $value = trim($value);
                if ($value === '') {
                    continue;
                }
                if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                    $url = $value;
                } else {
                    $handle = ltrim($value, '@/');
                    $url = match ($platform) {
                        'facebook' => 'https://www.facebook.com/'.$handle,
                        'instagram' => 'https://www.instagram.com/'.$handle,
                        'twitter' => 'https://twitter.com/'.$handle,
                        'linkedin' => 'https://www.linkedin.com/in/'.$handle,
                        default => null,
                    };
                }
                if ($url) {
                    $social[] = ['platform' => $platform, 'url' => $url];
                }
            }

            $name = trim((string) $member->name);
            $resourcePeople[] = [
                'name' => $name,
                'initial' => strtoupper(substr(str_replace('.', '', $name), 0, 1)),
                'email' => trim((string) $member->email),
                'image' => $member->photo ? asset('uploads/sbc-pool/'.$member->photo) : '',
                'social' => $social,
            ];
        }

        return view('preview::resources.index', compact(
            'metaTitle',
            'metaDescription',
            'sections',
            'resourcePeople'
        ));
    }
}
