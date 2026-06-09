<?php

namespace App\Http\Controllers;

use App\Models\LearningCat;
use App\Models\LearningCorner;
use App\Models\Page;
use App\Models\PageSection;

class LearningCornerController extends Controller
{
    public function index()
    {
        $metaTitle = 'Learning Corner · ChhattisgarhABC';
        $metaDescription = 'Explore modules, videos, posters and training material for strengthening SBC practice.';
        $lcEyebrow = 'Learning space';
        $lcTitle = 'Learning <em>Corner</em>';
        $lcLede = 'Explore short modules, videos, posters, flipbooks and training material for strengthening SBC practice.';

        $page = Page::where('route_name', 'learning-corner')->where('is_active', 1)->first();
        if ($page) {
            if ($page->meta_title) {
                $metaTitle = $page->meta_title;
            }
            if ($page->meta_description) {
                $metaDescription = $page->meta_description;
            }
            $jumbotron = PageSection::where('page_id', $page->id)->where('section_key', 'jumbotron')->where('is_active', 1)->first();
            if ($jumbotron) {
                $cms = json_decode((string) $jumbotron->content, true);
                if (is_array($cms)) {
                    $lcEyebrow = $cms['eyebrow'] ?? $lcEyebrow;
                    $lcTitle = $cms['title'] ?? $lcTitle;
                    $lcLede = $cms['lede'] ?? $lcLede;
                }
            }
        }

        $allCats = LearningCat::where('status', 1)->orderBy('sort_order')->orderBy('cat_name')->get();
        $resourceRows = LearningCorner::where('status', 1)->get(['cat_id']);
        $counts = [];
        foreach ($resourceRows as $resource) {
            $counts[$resource->cat_id] = ($counts[$resource->cat_id] ?? 0) + 1;
        }

        $mainCategories = [];
        $subtopicTotal = 0;
        $resourceTotal = 0;

        foreach ($allCats as $cat) {
            if ($cat->parent_id !== null) {
                continue;
            }

            $children = [];
            foreach ($allCats as $sub) {
                if ((int) $sub->parent_id !== (int) $cat->id) {
                    continue;
                }
                $subCount = $counts[$sub->id] ?? 0;
                $subtopicTotal++;
                $resourceTotal += $subCount;
                $children[] = [
                    'id' => $sub->id,
                    'name' => $sub->cat_name,
                    'icon' => $sub->cat_icon ?: 'icon-folder',
                    'count' => $subCount,
                    'url' => route('learning-corner.sub', ['main' => $cat->id, 'sub' => $sub->id]),
                ];
            }

            $totalCount = 0;
            foreach ($children as $child) {
                $totalCount += $child['count'];
            }

            $description = trim((string) ($cat->description ?? ''));
            $mainCategories[] = [
                'id' => $cat->id,
                'name' => $cat->cat_name,
                'icon' => $cat->cat_icon ?: 'icon-folder',
                'description' => $description,
                'description_short' => strlen($description) > 100 ? substr($description, 0, 100).'...' : $description,
                'children' => $children,
                'total_count' => $totalCount,
                'url' => route('learning-corner.main', ['main' => $cat->id]),
            ];
        }

        $lcStats = [
            ['value' => count($mainCategories), 'label' => 'Topics'],
            ['value' => $subtopicTotal, 'label' => 'Subtopics'],
            ['value' => $resourceTotal, 'label' => 'Resources'],
        ];

        return view('learning-corner.index', compact(
            'metaTitle',
            'metaDescription',
            'mainCategories',
            'lcEyebrow',
            'lcTitle',
            'lcLede',
            'lcStats'
        ));
    }

    public function main(int $main)
    {
        $mainCat = LearningCat::where('id', $main)->whereNull('parent_id')->where('status', 1)->first();
        abort_unless($mainCat, 404);

        $resourceRows = LearningCorner::where('status', 1)->get(['cat_id']);
        $counts = [];
        foreach ($resourceRows as $resource) {
            $counts[$resource->cat_id] = ($counts[$resource->cat_id] ?? 0) + 1;
        }

        $subcategories = [];
        $resourceSum = 0;
        $subs = LearningCat::where('parent_id', $main)->where('status', 1)->orderBy('sort_order')->orderBy('cat_name')->get();

        foreach ($subs as $sub) {
            $count = $counts[$sub->id] ?? 0;
            $resourceSum += $count;
            $description = trim((string) ($sub->description ?? ''));
            $subcategories[] = [
                'id' => $sub->id,
                'name' => $sub->cat_name,
                'icon' => $sub->cat_icon ?: 'icon-folder',
                'description' => $description,
                'description_short' => strlen($description) > 80 ? substr($description, 0, 80).'...' : $description,
                'resource_count' => $count,
                'url' => route('learning-corner.sub', ['main' => $main, 'sub' => $sub->id]),
            ];
        }

        $lcEyebrow = 'Learning space';
        $lcTitle = e($mainCat->cat_name);
        $lcLede = trim((string) $mainCat->description) ?: 'Choose a subtopic to browse learning resources.';
        $lcStats = [
            ['value' => count($subcategories), 'label' => 'Subtopics'],
            ['value' => $resourceSum, 'label' => 'Resources'],
        ];

        $main = [
            'id' => $mainCat->id,
            'name' => $mainCat->cat_name,
            'description' => $mainCat->description ?? '',
        ];

        return view('learning-corner.subcategories', compact(
            'main',
            'subcategories',
            'lcEyebrow',
            'lcTitle',
            'lcLede',
            'lcStats'
        ));
    }

    public function sub(int $main, int $sub)
    {
        $mainCat = LearningCat::where('id', $main)->whereNull('parent_id')->where('status', 1)->first();
        $subCat = LearningCat::where('id', $sub)->where('parent_id', $main)->where('status', 1)->first();
        abort_unless($mainCat && $subCat, 404);

        $rows = LearningCorner::where('cat_id', $sub)->where('status', 1)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        $typeLabels = ['book' => 'Book', 'posters' => 'Poster', 'mobile kunji' => 'Kunji', 'video' => 'Video'];
        $typeIcons = ['book' => 'bi-book', 'posters' => 'bi-image', 'mobile kunji' => 'bi-phone', 'video' => 'bi-play-circle'];

        $resources = [];
        foreach ($rows as $row) {
            $type = (string) $row->m_type;
            $resources[] = [
                'title' => $row->title,
                'content' => $row->content,
                'link' => trim((string) $row->link),
                'image' => $row->image ? asset('uploads/learning/'.$row->image) : '',
                'type_label' => $typeLabels[$type] ?? 'Resource',
                'type_icon' => $typeIcons[$type] ?? 'bi-file-earmark',
                'cover_label' => strtoupper(substr($type !== '' ? $type : 'LC', 0, 2)),
                'date_label' => $row->date ? date('d M Y', strtotime($row->date)) : '',
            ];
        }

        $main = ['id' => $mainCat->id, 'name' => $mainCat->cat_name];
        $sub = ['id' => $subCat->id, 'name' => $subCat->cat_name];
        $lcEyebrow = $mainCat->cat_name;
        $lcTitle = e($subCat->cat_name);
        $lcLede = trim((string) $subCat->description) ?: 'Learning resources for '.$subCat->cat_name.'.';
        $lcStats = [['value' => count($resources), 'label' => 'Resources']];

        return view('learning-corner.resources', compact(
            'main',
            'sub',
            'resources',
            'lcEyebrow',
            'lcTitle',
            'lcLede',
            'lcStats'
        ));
    }
}
