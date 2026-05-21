<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('learning_corner', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1)->after('date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('learning_corner', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn('status');
        });
    }
};
