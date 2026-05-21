<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_corner', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cat_id');
            $table->string('title', 255);
            $table->string('content', 500)->default('');
            $table->string('admin', 100)->default('');
            $table->unsignedBigInteger('user_id')->default(1);
            $table->string('image', 255)->default('');
            $table->string('m_type', 255)->default('book');
            $table->string('link', 500)->default('');
            $table->date('date')->useCurrent();
            $table->index('cat_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_corner');
    }
};
