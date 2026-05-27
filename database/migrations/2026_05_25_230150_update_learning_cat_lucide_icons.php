<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $rootIcons = [
        'Nutrition' => 'icon-salad',
        'ODF+ and Hygiene' => 'icon-soap-dispenser-droplet',
        'Child Health and Nutrition' => 'icon-baby',
        'Maternal Health and Nutrition' => 'icon-heart-pulse',
        'Education and Parental engagement' => 'icon-book-open-text',
        'Life skills' => 'icon-target',
        'Mission Life' => 'icon-sprout',
        'Adolescent health and Nutrition' => 'icon-users',
        'Mental Health' => 'icon-brain',
        'SBC' => 'icon-megaphone',
    ];

    public function up(): void
    {
        foreach ($this->rootIcons as $name => $icon) {
            DB::table('learning_cat')
                ->where('cat_name', $name)
                ->whereNull('parent_id')
                ->update(['cat_icon' => $icon]);
        }

        DB::table('learning_cat')
            ->whereNotNull('parent_id')
            ->update(['cat_icon' => 'icon-folder']);
    }

    public function down(): void
    {
        $fallbacks = [
            'Nutrition' => 'bi bi-egg-fried',
            'ODF+ and Hygiene' => 'bi bi-water',
            'Child Health and Nutrition' => 'bi bi-emoji-smile',
            'Maternal Health and Nutrition' => 'bi bi-heart',
            'Education and Parental engagement' => 'bi bi-book',
            'Life skills' => 'bi bi-lightbulb',
            'Mission Life' => 'bi bi-tree',
            'Adolescent health and Nutrition' => 'bi bi-gender-ambiguous',
            'Mental Health' => 'bi bi-brain',
            'SBC' => 'bi bi-broadcast',
        ];

        foreach ($fallbacks as $name => $icon) {
            DB::table('learning_cat')
                ->where('cat_name', $name)
                ->whereNull('parent_id')
                ->update(['cat_icon' => $icon]);
        }
    }
};
