<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('email_id');
            $table->string('user', 50)->default('');
            $table->text('reply');
            $table->timestamp('date')->useCurrent();
            $table->index('email_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('replies');
    }
};
