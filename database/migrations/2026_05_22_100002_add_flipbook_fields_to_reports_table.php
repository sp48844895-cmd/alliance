<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('flipbook_slug', 100)->nullable()->after('preview_path');
            $table->unsignedSmallInteger('flipbook_pages')->nullable()->after('flipbook_slug');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['flipbook_slug', 'flipbook_pages']);
        });
    }
};
