<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insights', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 200);
            $table->string('tag', 100)->default('');
            $table->text('description');
            $table->string('image', 100)->default('');
            $table->string('link_text', 100)->default('');
            $table->string('link_url', 255)->default('');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->timestamps();
            $table->index('status');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insights');
    }
};
