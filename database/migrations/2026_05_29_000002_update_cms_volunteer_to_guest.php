<?php

use App\Services\PageContentService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $pageIds = DB::table('pages')
            ->whereIn('slug', ['get-involved', 'home'])
            ->pluck('id');

        if ($pageIds->isEmpty()) {
            return;
        }

        $rows = DB::table('page_sections')->whereIn('page_id', $pageIds)->get();

        foreach ($rows as $row) {
            $content = $row->content ?? '';

            if (! preg_match('/volunteer|gi-volunteer|register\/volunteer/i', $content)) {
                continue;
            }

            $updated = str_replace(
                [
                    'gi-volunteer',
                    '/get-involved/register/volunteer',
                    '"slug": "volunteer"',
                    '"pathway": "volunteer"',
                    '"icon": "volunteer"',
                    '"slug":"volunteer"',
                    '"pathway":"volunteer"',
                    '"icon":"volunteer"',
                    '"anchor": "gi-volunteer"',
                    'an Individual Volunteer',
                    'Individual Volunteer',
                    '"label": "Volunteer"',
                    'Volunteer registration',
                ],
                [
                    'gi-guest',
                    '/get-involved/register/guest',
                    '"slug": "guest"',
                    '"pathway": "guest"',
                    '"icon": "guest"',
                    '"slug":"guest"',
                    '"pathway":"guest"',
                    '"icon":"guest"',
                    '"anchor": "gi-guest"',
                    'a Guest',
                    'Guest',
                    '"label": "Guest"',
                    'Guest registration',
                ],
                $content
            );

            $updated = preg_replace('/"anchor":"gi-volunteer"/', '"anchor":"gi-guest"', $updated);

            if ($updated !== $content) {
                DB::table('page_sections')->where('id', $row->id)->update([
                    'content' => $updated,
                    'updated_at' => now(),
                ]);
            }
        }

        app(PageContentService::class)->clearCache();
    }

    public function down(): void
    {
    }
};
