<?php

use App\Services\PageContentService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $pageId = DB::table('pages')->where('route_name', 'home')->value('id');

        if ($pageId === null) {
            return;
        }

        $hubContent = [
            'chapter_num' => '06',
            'chapter_label' => 'Knowledge Hub',
            'heading_html' => 'Knowledge assets around <em>behaviour change</em>.',
            'side_text' => 'Learning materials, evidence, toolkits and practitioner support — organised for teams across the alliance.',
            'resources' => [
                [
                    'link' => ['type' => 'route', 'name' => 'learning-corner'],
                    'type' => 'Learning',
                    'title' => 'Learning Corner',
                    'meta' => 'Modules, videos, posters and training material',
                    'aos_delay' => null,
                    'icon' => 'book',
                ],
                [
                    'link' => ['type' => 'route', 'name' => 'reports'],
                    'type' => 'Evidence',
                    'title' => 'Reports & Insights',
                    'meta' => 'Reports, newsletters and success stories',
                    'aos_delay' => 100,
                    'icon' => 'chart',
                ],
                [
                    'link' => [
                        'type' => 'route',
                        'name' => 'knowledge-hub',
                        'query' => 'filter=toolkit',
                        'fragment' => 'kh-search',
                    ],
                    'type' => 'Toolkits',
                    'title' => 'Resource Kit',
                    'meta' => 'Open-licensed guides, IEC and campaign assets',
                    'aos_delay' => 200,
                    'icon' => 'layers',
                ],
                [
                    'link' => ['type' => 'route', 'name' => 'resources'],
                    'type' => 'Practitioners',
                    'title' => 'SBC Resource Pool',
                    'meta' => 'Connect with trainers and field communicators',
                    'aos_delay' => 300,
                    'icon' => 'users',
                ],
            ],
            'library_link' => ['type' => 'route', 'name' => 'knowledge-hub'],
            'library_label' => 'Open the full library →',
        ];

        DB::table('page_sections')
            ->where('page_id', $pageId)
            ->where('section_key', 'hub')
            ->update([
                'content' => json_encode($hubContent),
                'updated_at' => now(),
            ]);

        $getInvolvedContent = [
            'chapter_num' => '07',
            'chapter_label' => 'Get Involved',
            'heading_html' => 'Join the <em>Alliance</em> for behaviour change.',
            'lede_html' => 'Volunteer, partner as an NGO or organisation, or contribute as a professional — four clear pathways to strengthen community-led SBC across Chhattisgarh.',
            'button_label' => 'Explore ways to join',
            'button_link' => ['type' => 'route', 'name' => 'get-involved'],
        ];

        $exists = DB::table('page_sections')
            ->where('page_id', $pageId)
            ->where('section_key', 'get_involved_cta')
            ->exists();

        if ($exists) {
            DB::table('page_sections')
                ->where('page_id', $pageId)
                ->where('section_key', 'get_involved_cta')
                ->update([
                    'content' => json_encode($getInvolvedContent),
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('page_sections')->insert([
                'page_id' => $pageId,
                'section_key' => 'get_involved_cta',
                'section_type' => 'cta',
                'content' => json_encode($getInvolvedContent),
                'sort_order' => 9,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $newsletterContent = [
            'chapter_num' => '08',
            'chapter_label' => 'Newsletter Subscribe',
            'heading_html' => 'Stay connected with the <em>Alliance</em>.',
            'lede_html' => 'Get stories, events, and resources from across Chhattisgarh — field updates, campaign highlights, and learning materials delivered to your inbox.',
        ];

        $newsletterExists = DB::table('page_sections')
            ->where('page_id', $pageId)
            ->where('section_key', 'newsletter')
            ->exists();

        if ($newsletterExists) {
            DB::table('page_sections')
                ->where('page_id', $pageId)
                ->where('section_key', 'newsletter')
                ->update([
                    'content' => json_encode($newsletterContent),
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('page_sections')->insert([
                'page_id' => $pageId,
                'section_key' => 'newsletter',
                'section_type' => 'newsletter',
                'content' => json_encode($newsletterContent),
                'sort_order' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        app(PageContentService::class)->clearCache();
    }

    public function down(): void
    {
        app(PageContentService::class)->clearCache();
    }
};
