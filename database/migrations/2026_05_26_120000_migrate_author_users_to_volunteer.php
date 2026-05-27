<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->where('type', 'author')->update([
            'type' => 'volunteer',
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
    }
};
