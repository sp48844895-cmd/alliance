<?php

use App\Services\PageContentService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const STATEMENT = 'A practice that revolves around Human Centric Communication';

    public function up(): void
    {
        $pageId = DB::table('pages')->where('route_name', 'home')->value('id');

        if ($pageId === null) {
            return;
        }

        $row = DB::table('page_sections')
            ->where('page_id', $pageId)
            ->where('section_key', 'intro')
            ->first(['id', 'content']);

        if ($row === null) {
            return;
        }

        $content = json_decode((string) $row->content, true);

        if (! is_array($content)) {
            return;
        }

        if (($content['heading_html'] ?? '') === self::STATEMENT) {
            $content['heading_html'] = '';
        }

        if (! empty($content['html'])) {
            $content['html'] = str_replace(
                '<p class="intro-statement" data-aos="fade-up" data-aos-delay="80">'.self::STATEMENT.'</p>'."\n      ",
                '',
                (string) $content['html']
            );
            $content['html'] = str_replace(
                '<p class="intro-statement" data-aos="fade-up" data-aos-delay="80">'.self::STATEMENT.'</p>',
                '',
                (string) $content['html']
            );
        }

        DB::table('page_sections')
            ->where('id', $row->id)
            ->update(['content' => json_encode($content)]);

        app(PageContentService::class)->clearCache();
    }

    public function down(): void
    {
    }
};
