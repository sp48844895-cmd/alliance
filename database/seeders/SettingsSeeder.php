<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'contact_city',        'value' => 'Raipur, Chhattisgarh'],
            ['key' => 'contact_email',        'value' => 'info@chhttisgarhabc.org'],
            ['key' => 'footer_copyright',     'value' => 'ChhattisgarhABC · Alliance for Behaviour Change'],
            ['key' => 'footer_developer',     'value' => 'Ingenious Insights'],
            ['key' => 'social_facebook',      'value' => 'https://www.facebook.com/ChhattisgarhABC/'],
            ['key' => 'social_instagram',     'value' => 'https://www.instagram.com/chhattisgarhabc/'],
            ['key' => 'social_twitter',       'value' => 'https://twitter.com/chhattisgarhabc'],
            ['key' => 'social_youtube',       'value' => 'https://www.youtube.com/@chhattisgarhabc'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}
