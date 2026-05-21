<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('mobile', 255)->default('');
            $table->string('email', 255)->default('');
            $table->text('area')->nullable();
            $table->text('address')->nullable();
            $table->string('block', 255)->default('');
            $table->string('district', 255)->default('');
            $table->string('fb', 255)->default('');
            $table->string('insta', 255)->default('');
            $table->string('twitter', 255)->default('');
            $table->string('youtube', 255)->default('');
            $table->string('website', 255)->default('');
            $table->string('ngo_organization', 255)->default('');
            $table->text('org_intro')->nullable();
            $table->string('img', 255)->default('');
            $table->string('type', 255)->default('Individual')->comment('Individual|CSO/NGO|Volunteer|Firm/Organization');
            $table->string('code', 100)->unique();
            $table->dateTime('date')->useCurrent();
            $table->index('type');
            $table->index('district');
            $table->index('block');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership');
    }
};
