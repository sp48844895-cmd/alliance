<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('folder_id')->default(0);
            $table->string('file_type', 50);
            $table->text('file_path');
            $table->tinyInteger('is_public')->nullable()->default(0);
            $table->dateTime('date_updated')->useCurrent()->useCurrentOnUpdate();
            $table->index('folder_id');
            $table->index('file_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
