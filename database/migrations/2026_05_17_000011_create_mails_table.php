<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mails', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('email', 150);
            $table->string('subject', 200);
            $table->string('phone', 16)->default('');
            $table->text('message');
            $table->tinyInteger('status')->default(0)->comment('0=Unread, 1=Read');
            $table->timestamp('date')->useCurrent();
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mails');
    }
};
