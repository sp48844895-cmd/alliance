<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->string('type', 50)->default('Report');
            $table->string('cover_path', 500)->default('');
            $table->string('preview_path', 500)->default('');
            $table->string('download_path', 500)->default('');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
