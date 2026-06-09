<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_registrations', function (Blueprint $table) {
            $table->unsignedInteger('district_id')->nullable()->after('phone');
            $table->string('profile_image', 255)->nullable()->after('district_id');
            $table->json('profile')->nullable()->after('profile_image');
        });

        DB::table('program_registrations')->where('status', 'new')->update(['status' => 'pending']);
        DB::table('program_registrations')->where('status', 'accepted')->update(['status' => 'approved']);

        if (! Schema::hasTable('membership')) {
            return;
        }

        $memberships = DB::table('membership')->orderBy('id')->get();

        foreach ($memberships as $member) {
            $email = strtolower(trim((string) $member->email));

            if ($email === '') {
                continue;
            }

            if (DB::table('program_registrations')->where('email', $email)->exists()) {
                continue;
            }

            $type = match (strtolower(trim((string) $member->type))) {
                'cso/ngo', 'firm/organization', 'firm/organisation' => 'partner',
                default => 'guest',
            };

            $districtId = is_numeric($member->district) ? (int) $member->district : null;

            DB::table('program_registrations')->insert([
                'user_id' => null,
                'type' => $type,
                'full_name' => (string) $member->name,
                'email' => $email,
                'phone' => (string) ($member->mobile ?? ''),
                'district_id' => $districtId,
                'profile_image' => trim((string) ($member->img ?? '')) ?: null,
                'profile' => json_encode([
                    'fb' => (string) ($member->fb ?? ''),
                    'insta' => (string) ($member->insta ?? ''),
                    'twitter' => (string) ($member->twitter ?? ''),
                    'youtube' => (string) ($member->youtube ?? ''),
                    'website' => (string) ($member->website ?? ''),
                    'ngo_organization' => (string) ($member->ngo_organization ?? ''),
                    'legacy_code' => (string) ($member->code ?? ''),
                ]),
                'institution' => trim((string) ($member->ngo_organization ?? '')),
                'motivation' => trim((string) ($member->org_intro ?? '')) ?: 'Imported from legacy membership directory.',
                'status' => 'approved',
                'created_at' => $member->date ?? now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('program_registrations', function (Blueprint $table) {
            $table->dropColumn(['district_id', 'profile_image', 'profile']);
        });

        DB::table('program_registrations')->where('status', 'pending')->update(['status' => 'new']);
        DB::table('program_registrations')->where('status', 'approved')->update(['status' => 'accepted']);
    }
};
