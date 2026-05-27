<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_cat', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cat_name', 255);
            $table->string('cat_icon', 255)->default('icon-folder');
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->dateTime('created_at')->useCurrent();
            $table->string('admin_name', 255)->default('');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_cat');
    }
};
