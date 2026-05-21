<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoryArchiveSeeder extends Seeder
{
    public function run(): void
    {
        $stories = require __DIR__.'/data/story_pages.php';
        $now = now();

        foreach ($stories as $slug => $story) {
            DB::table('stories')->updateOrInsert(
                ['slug' => $slug],
                [
                    'title' => $story['title'],
                    'category' => $story['category'],
                    'content' => $story['intro'] ?? $story['lede'],
                    'tag' => $story['theme'] ?? '',
                    'thumbnail_path' => $story['hero_image'] ?? '',
                    'status' => 'active',
                    'payload' => json_encode($story, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
