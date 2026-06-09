<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->where('type', 'volunteer')->update([
            'type' => 'guest',
            'updated_at' => now(),
        ]);

        DB::table('program_registrations')->where('type', 'volunteer')->update([
            'type' => 'guest',
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('users')->where('type', 'guest')->update([
            'type' => 'volunteer',
            'updated_at' => now(),
        ]);

        DB::table('program_registrations')->where('type', 'guest')->update([
            'type' => 'volunteer',
            'updated_at' => now(),
        ]);
    }
};
