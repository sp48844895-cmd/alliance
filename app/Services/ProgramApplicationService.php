<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProgramApplicationService
{
    public function submitApplication(string $registrationType, array $data): int
    {
        $userType = $registrationType === 'fellow' ? 'fellow' : 'intern';
        $email = strtolower(trim($data['email']));

        return (int) DB::transaction(function () use ($registrationType, $userType, $email, $data) {
            $existingUser = DB::table('users')
                ->where('email', $email)
                ->whereNull('deleted_at')
                ->first();

            if ($existingUser) {
                if (($existingUser->type ?? '') !== $userType) {
                    throw ValidationException::withMessages([
                        'email' => 'This email is already registered for another account type.',
                    ]);
                }

                if ($existingUser->is_active ?? false) {
                    throw ValidationException::withMessages([
                        'email' => 'An account with this email already exists. Please sign in.',
                    ]);
                }

                $openRegistration = DB::table('program_registrations')
                    ->where('user_id', $existingUser->id)
                    ->whereIn('status', ['pending', 'new', 'reviewed'])
                    ->exists();

                if ($openRegistration) {
                    throw ValidationException::withMessages([
                        'email' => 'An application with this email is already under review.',
                    ]);
                }

                $name = $this->splitName($data['full_name']);
                DB::table('users')->where('id', $existingUser->id)->update([
                    'fname' => $name['fname'],
                    'lname' => $name['lname'],
                    'password' => Hash::make($data['password']),
                    'is_active' => false,
                    'updated_at' => now(),
                ]);

                $userId = (int) $existingUser->id;
            } else {
                $name = $this->splitName($data['full_name']);
                $userId = (int) DB::table('users')->insertGetId([
                    'fname' => $name['fname'],
                    'lname' => $name['lname'],
                    'username' => $this->uniqueUsername($email),
                    'email' => $email,
                    'password' => Hash::make($data['password']),
                    'image' => '',
                    'bio' => null,
                    'role' => 2,
                    'type' => $userType,
                    'is_active' => false,
                    'date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $registrationId = $this->insertRegistration($registrationType, $userId, $data);

            return $registrationId;
        });
    }

    private function insertRegistration(string $registrationType, int $userId, array $data): int
    {
        if (in_array($registrationType, ['intern', 'fellow'], true)) {
            return (int) DB::table('program_registrations')->insertGetId([
                'user_id' => $userId,
                'type' => $registrationType,
                'full_name' => trim($data['full_name']),
                'email' => strtolower(trim($data['email'])),
                'phone' => trim($data['phone']),
                'institution' => trim($data['university']),
                'class_year' => trim($data['class_year']),
                'domain_area' => $data['domain_area'],
                'domain_areas' => null,
                'years_experience' => null,
                'motivation' => trim($data['motivation']),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return 0;
    }

    public function splitName(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName), 2) ?: [];
        $fname = $parts[0] ?? 'Applicant';
        $lname = $parts[1] ?? '';

        return [
            'fname' => Str::limit($fname, 100, ''),
            'lname' => Str::limit($lname, 50, ''),
        ];
    }

    public function createPathwayAccount(
        string $pathway,
        string $fullName,
        string $email,
        string $password,
        string $phone = '',
        string $subject = '',
        string $message = ''
    ): int {
        $userType = $pathway === 'partner' ? 'ngo' : 'guest';
        $email = strtolower(trim($email));

        return (int) DB::transaction(function () use ($pathway, $userType, $email, $fullName, $password, $phone, $subject, $message) {
            $existingUser = DB::table('users')
                ->where('email', $email)
                ->whereNull('deleted_at')
                ->first();

            if ($existingUser) {
                if (($existingUser->type ?? '') !== $userType) {
                    throw ValidationException::withMessages([
                        'email' => 'This email is already registered for another account type.',
                    ]);
                }

                if ($existingUser->is_active ?? false) {
                    throw ValidationException::withMessages([
                        'email' => 'An account with this email already exists. Please sign in.',
                    ]);
                }

                $name = $this->splitName($fullName);
                DB::table('users')->where('id', $existingUser->id)->update([
                    'fname' => $name['fname'],
                    'lname' => $name['lname'],
                    'password' => Hash::make($password),
                    'is_active' => false,
                    'updated_at' => now(),
                ]);

                $userId = (int) $existingUser->id;
            } else {
                $name = $this->splitName($fullName);
                $userId = (int) DB::table('users')->insertGetId([
                    'fname' => $name['fname'],
                    'lname' => $name['lname'],
                    'username' => $this->uniqueUsername($email),
                    'email' => $email,
                    'password' => Hash::make($password),
                    'image' => '',
                    'bio' => null,
                    'role' => 2,
                    'type' => $userType,
                    'is_active' => false,
                    'date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('program_registrations')->insert([
                'user_id' => $userId,
                'type' => $pathway,
                'full_name' => trim($fullName),
                'email' => $email,
                'phone' => trim($phone),
                'institution' => trim($subject),
                'class_year' => null,
                'domain_area' => null,
                'domain_areas' => null,
                'years_experience' => null,
                'motivation' => trim($message),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $userId;
        });
    }

    public function uniqueUsername(string $email): string
    {
        $base = Str::slug(Str::before($email, '@'), '_');
        if ($base === '') {
            $base = 'applicant';
        }
        $base = Str::limit($base, 40, '');

        $username = $base;
        $suffix = 1;

        while (DB::table('users')->where('username', $username)->exists()) {
            $username = Str::limit($base, 35, '') . '_' . $suffix;
            $suffix++;
        }

        return Str::limit($username, 50, '');
    }
}
