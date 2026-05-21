<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event', function (Blueprint $table) {
            $table->increments('id');
            $table->string('date', 100)->default('');
            $table->string('time', 100)->default('');
            $table->string('event_name', 100);
            $table->string('start_date', 100)->default('');
            $table->time('end_date')->nullable();
            $table->text('description');
            $table->string('location', 100)->default('NOT MENTIONED');
            $table->string('admin', 100)->default('');
            $table->tinyInteger('event_status')->default(1)->comment('1=Active, 0=Inactive');
            $table->string('event_image', 100)->default('');
            $table->text('googlemap')->nullable();
            $table->index('event_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event');
    }
};
