<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banner', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dbannerimg', 100)->default('');
            $table->string('mbannerimg', 100)->default('');
            $table->text('ytlink')->nullable();
            $table->text('redirect');
            $table->date('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banner');
    }
};
