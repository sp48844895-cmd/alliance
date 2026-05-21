<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportsSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('reports')->exists()) {
            return;
        }

        $now = now();

        DB::table('reports')->insert([
            [
                'title' => 'Report: Noni Johar 2024',
                'type' => 'Report',
                'cover_path' => 'images/book/bookcover_3.jpg',
                'preview_path' => 'magazine/Report_Noni_Johar_24.html',
                'download_path' => 'images/book/Report_Noni_Johar_24_compressed.pdf',
                'sort_order' => 1,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Agricons Foundation Newsletter',
                'type' => 'Newsletter',
                'cover_path' => 'images/book/bookcover_2.jpg',
                'preview_path' => 'magazine/af_newsletter.html',
                'download_path' => 'images/book/AF_Newsletter_Vol 1_compressed.pdf',
                'sort_order' => 2,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'ChhattisgarhABC Success Stories',
                'type' => 'Stories',
                'cover_path' => 'images/book/bookcover.JPG',
                'preview_path' => 'magazine/slider.html',
                'download_path' => 'images/book/ChhattisgarhABC_SuccessStories_compressed.pdf',
                'sort_order' => 3,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
