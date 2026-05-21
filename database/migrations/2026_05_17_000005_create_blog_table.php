<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cat_id');
            $table->string('title', 255)->unique();
            $table->text('content');
            $table->text('tag');
            $table->string('admin', 100)->default('');
            $table->unsignedBigInteger('user_id')->default(1);
            $table->tinyInteger('status')->default(0)->comment('1=Published, 0=Draft');
            $table->tinyInteger('rate')->default(0);
            $table->string('image', 255)->default('');
            $table->date('date_created')->useCurrent();
            $table->string('views', 100)->default('0');
            $table->timestamp('date_updated')->useCurrent()->useCurrentOnUpdate();
            $table->index('cat_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog');
    }
};
