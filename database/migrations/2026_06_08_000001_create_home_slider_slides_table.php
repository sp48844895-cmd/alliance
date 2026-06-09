<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_slider_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->text('short_description');
            $table->string('url', 500)->default('');
            $table->string('image', 255)->default('');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_slider_slides');
    }
};
