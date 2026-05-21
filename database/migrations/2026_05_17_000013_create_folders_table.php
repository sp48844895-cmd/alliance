<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0);
            $table->string('name', 200);
            $table->unsignedInteger('parent_id')->default(0)->comment('0 = root');
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('folders');
    }
};
