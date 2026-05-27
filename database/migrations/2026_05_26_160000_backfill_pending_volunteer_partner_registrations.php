<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('users') || ! DB::getSchemaBuilder()->hasTable('program_registrations')) {
            return;
        }

        $pathwayByUserType = [
            'volunteer' => 'volunteer',
            'ngo' => 'partner',
        ];

        $users = DB::table('users')
            ->whereNull('deleted_at')
            ->where('is_active', 0)
            ->whereIn('type', array_keys($pathwayByUserType))
            ->orderBy('id')
            ->get();

        foreach ($users as $user) {
            $pathway = $pathwayByUserType[$user->type] ?? null;

            if ($pathway === null) {
                continue;
            }

            $exists = DB::table('program_registrations')
                ->where('user_id', $user->id)
                ->where('type', $pathway)
                ->exists();

            if ($exists) {
                continue;
            }

            $contact = DB::table('contact_messages')
                ->where('email', strtolower(trim((string) $user->email)))
                ->where('pathway', $pathway)
                ->orderByDesc('id')
                ->first();

            $fullName = trim((string) $user->fname.' '.(string) $user->lname);

            DB::table('program_registrations')->insert([
                'user_id' => $user->id,
                'type' => $pathway,
                'full_name' => $contact ? trim((string) $contact->name) : $fullName,
                'email' => strtolower(trim((string) $user->email)),
                'phone' => $contact ? trim((string) $contact->phone) : '',
                'institution' => $contact ? trim((string) $contact->subject) : '',
                'class_year' => null,
                'domain_area' => null,
                'domain_areas' => null,
                'years_experience' => null,
                'motivation' => $contact ? trim((string) $contact->message) : '',
                'status' => 'new',
                'created_at' => $contact->created_at ?? $user->created_at ?? now(),
                'updated_at' => $contact->updated_at ?? $user->updated_at ?? now(),
            ]);
        }
    }

    public function down(): void
    {
    }
};
