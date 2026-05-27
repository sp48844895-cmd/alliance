<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('type', 16);
            $table->string('full_name', 120);
            $table->string('email', 150);
            $table->string('phone', 20);
            $table->string('institution', 255)->default('');
            $table->string('class_year', 100)->nullable();
            $table->string('domain_area', 80)->nullable();
            $table->json('domain_areas')->nullable();
            $table->string('years_experience', 50)->nullable();
            $table->text('motivation');
            $table->string('status', 20)->default('new');
            $table->timestamps();

            $table->index('type');
            $table->index('status');
            $table->index('created_at');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_registrations');
    }
};
