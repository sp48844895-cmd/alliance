<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_links', function (Blueprint $table) {
            $table->increments('id');
            $table->string('facebook', 255)->default('');
            $table->string('twitter', 255)->default('');
            $table->string('instagram', 255)->default('');
            $table->string('linkedin', 255)->default('');
            $table->string('github', 255)->default('');
            $table->string('footerlink', 255)->default('');
            $table->string('footertxt', 255)->default('');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_links');
    }
};
