<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PageSection;
use App\Services\MembershipPageService;
use App\Services\PageContentService;
use Illuminate\Http\Request;

class MembersController extends Controller
{
    public function __construct(private MembershipPageService $memberships) {}

    public function index(Request $request)
    {
        $pageContent = app(PageContentService::class)->forRoute('members');
        $metaTitle = $pageContent['meta_title'] ?? 'Our Members · ChhattisgarhABC';
        $metaDescription = $pageContent['meta_description'] ?? 'Browse ChhattisgarhABC members by district and member type.';

        $overviewChapter = '01';
        $overviewTitle = 'People behind the <em>alliance.</em>';
        $overviewDescription = 'Browse members district-wise, search by name, or filter by member type.';
        $directoryChapter = '02';
        $directoryTitle = 'Find a member.';

        $page = Page::where('route_name', 'members')->where('is_active', 1)->first();
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
                $sections = is_array($content) ? $content : [];
                if ($row->section_key === 'members_overview') {
                    $overviewChapter = $sections['chapter'] ?? $overviewChapter;
                    $overviewTitle = $sections['title'] ?? $overviewTitle;
                    $overviewDescription = $sections['description'] ?? $overviewDescription;
                }
                if ($row->section_key === 'members_directory') {
                    $directoryChapter = $sections['chapter'] ?? $directoryChapter;
                    $directoryTitle = $sections['title'] ?? $directoryTitle;
                }
            }
        }

        $filterData = $this->memberships->filters();
        $activeDistrict = $request->input('district', 'all');
        $activeType = $request->input('type', 'all');
        $activeSearch = trim((string) $request->input('search', ''));

        $memberPaginator = $this->memberships->paginatedListing(20, [
            'district' => $activeDistrict,
            'type' => $activeType,
            'search' => $activeSearch,
        ]);

        $members = $memberPaginator->items();
        $districtOptions = $filterData['districts'];
        $typeOptions = $filterData['member_types'];
        $memberTypes = $filterData['member_type_map'];
        $totalMembers = $filterData['count'];
        $districtCount = max(0, count($districtOptions) - 1);
        $memberTypeCount = max(0, count($typeOptions) - 1);
        $memberTotal = $memberPaginator->total();
        $hasMembers = $memberTotal > 0;

        return view('members.index', compact(
            'metaTitle',
            'metaDescription',
            'districtOptions',
            'typeOptions',
            'memberTypes',
            'members',
            'memberPaginator',
            'activeDistrict',
            'activeType',
            'activeSearch',
            'totalMembers',
            'districtCount',
            'memberTypeCount',
            'memberTotal',
            'hasMembers',
            'overviewChapter',
            'overviewTitle',
            'overviewDescription',
            'directoryChapter',
            'directoryTitle'
        ));
    }
}
