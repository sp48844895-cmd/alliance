<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->string('username', 100)->unique();
            $table->string('password', 200);
            $table->tinyInteger('type')->default(2)->comment('1=Admin, 2=User');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
