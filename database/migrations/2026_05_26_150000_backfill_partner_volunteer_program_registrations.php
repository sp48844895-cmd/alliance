<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('contact_messages') || ! DB::getSchemaBuilder()->hasTable('program_registrations')) {
            return;
        }

        $messages = DB::table('contact_messages')
            ->whereIn('pathway', ['partner', 'volunteer'])
            ->orderBy('id')
            ->get();

        foreach ($messages as $message) {
            $user = DB::table('users')
                ->where('email', strtolower(trim((string) $message->email)))
                ->whereNull('deleted_at')
                ->first();

            if (! $user) {
                continue;
            }

            $alreadyListed = DB::table('program_registrations')
                ->where('user_id', $user->id)
                ->where('type', $message->pathway)
                ->exists();

            if ($alreadyListed) {
                continue;
            }

            DB::table('program_registrations')->insert([
                'user_id' => $user->id,
                'type' => $message->pathway,
                'full_name' => trim((string) $message->name),
                'email' => strtolower(trim((string) $message->email)),
                'phone' => trim((string) $message->phone),
                'institution' => trim((string) $message->subject),
                'class_year' => null,
                'domain_area' => null,
                'domain_areas' => null,
                'years_experience' => null,
                'motivation' => trim((string) $message->message),
                'status' => 'new',
                'created_at' => $message->created_at ?? now(),
                'updated_at' => $message->updated_at ?? now(),
            ]);
        }
    }

    public function down(): void
    {
    }
};
