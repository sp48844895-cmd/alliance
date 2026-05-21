<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site', function (Blueprint $table) {
            $table->increments('id');
            $table->string('logo', 255)->default('');
            $table->string('title', 255)->default('Alliance for Behavior Change');
            $table->string('footer', 255)->default('');
            $table->integer('postdisplay')->default(10);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site');
    }
};
