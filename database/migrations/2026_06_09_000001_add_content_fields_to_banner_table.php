<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banner', function (Blueprint $table) {
            $table->string('title', 150)->default('')->after('id');
            $table->text('description')->nullable()->after('title');
            $table->string('small_title', 100)->default('')->after('description');
            $table->string('front_image', 100)->default('')->after('mbannerimg');
            $table->string('url', 500)->default('')->after('front_image');
            $table->unsignedInteger('sort_order')->default(0)->after('url');
            $table->unsignedTinyInteger('status')->default(1)->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('banner', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'description',
                'small_title',
                'front_image',
                'url',
                'sort_order',
                'status',
            ]);
        });
    }
};
