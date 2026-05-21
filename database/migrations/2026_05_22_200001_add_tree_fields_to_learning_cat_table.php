<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('learning_cat', function (Blueprint $table) {
            $table->unsignedInteger('parent_id')->nullable()->after('id');
            $table->unsignedSmallInteger('sort_order')->default(0)->after('status');
            $table->string('description', 500)->default('')->after('cat_name');
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::table('learning_cat', function (Blueprint $table) {
            $table->dropIndex(['parent_id']);
            $table->dropColumn(['parent_id', 'sort_order', 'description']);
        });
    }
};
