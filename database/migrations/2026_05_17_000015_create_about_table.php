<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about', function (Blueprint $table) {
            $table->increments('id');
            $table->text('abouttext');
            $table->text('aboutvideo')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about');
    }
};
