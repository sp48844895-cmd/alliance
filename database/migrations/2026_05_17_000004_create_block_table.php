<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('block', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('district_id');
            $table->string('block_name', 50);
            $table->tinyInteger('status')->default(1);
            $table->index('district_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('block');
    }
};
