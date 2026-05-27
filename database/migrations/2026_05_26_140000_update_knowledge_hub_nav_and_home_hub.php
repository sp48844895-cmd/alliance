<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $pageId = DB::table('pages')->where('route_name', 'home')->value('id');

        if ($pageId !== null) {
            $hubContent = [
                'chapter_num' => '06',
                'chapter_label' => 'Knowledge Hub',
                'heading_html' => 'Knowledge assets around <em>behaviour change</em>.',
                'side_text' => 'Learning materials, evidence, programmes and practitioner support — organised for teams across the alliance.',
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
                        'link' => ['type' => 'route', 'name' => 'resources'],
                        'type' => 'Practitioners',
                        'title' => 'SBC Resource Pool',
                        'meta' => 'Connect with trainers and field communicators',
                        'aos_delay' => 100,
                        'icon' => 'users',
                    ],
                    [
                        'link' => ['type' => 'route', 'name' => 'programs'],
                        'type' => 'Programs',
                        'title' => 'Programs and Initiatives',
                        'meta' => 'Flagship SBC initiatives across districts',
                        'aos_delay' => 200,
                        'icon' => 'target',
                    ],
                    [
                        'link' => ['type' => 'route', 'name' => 'reports'],
                        'type' => 'Evidence',
                        'title' => 'Reports and Insights',
                        'meta' => 'Reports, newsletters and success stories',
                        'aos_delay' => 300,
                        'icon' => 'chart',
                    ],
                ],
                'library_link' => ['type' => 'route', 'name' => 'learning-corner'],
                'library_label' => 'Open Learning Corner →',
            ];

            DB::table('page_sections')
                ->where('page_id', $pageId)
                ->where('section_key', 'hub')
                ->update([
                    'content' => json_encode($hubContent),
                    'updated_at' => now(),
                ]);
        }

        DB::table('pages')->where('route_name', 'knowledge-hub')->delete();
    }

    public function down(): void
    {
    }
};
