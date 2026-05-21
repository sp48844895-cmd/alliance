<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateLoginUsers extends Command
{
    protected $signature = 'logins:create
                            {--password=abc@1234 : Password for all portal accounts}
                            {--force : Update password if the account already exists}';

    protected $description = 'Create or update all portal login users (Admin, Author, Volunteer, Intern, Pro, NGO).';

    private const ACCOUNTS = [
        [
            'type'     => 'admin',
            'fname'    => 'Suraj',
            'lname'    => 'Pandey',
            'username' => 'admin',
            'email'    => 'admin@abc.in',
            'bio'      => 'Platform administrator',
            'role'     => 1,
        ],
        [
            'type'     => 'author',
            'fname'    => 'Anita',
            'lname'    => 'Author',
            'username' => 'anita.a',
            'email'    => 'author@abc.in',
            'bio'      => 'Story author',
            'role'     => 2,
        ],
        [
            'type'     => 'volunteer',
            'fname'    => 'Asha',
            'lname'    => 'Volunteer',
            'username' => 'asha.v',
            'email'    => 'volunteer@abc.in',
            'bio'      => 'Field volunteer, Raipur',
            'role'     => 2,
        ],
        [
            'type'     => 'intern',
            'fname'    => 'Riya',
            'lname'    => 'Intern',
            'username' => 'riya.i',
            'email'    => 'intern@abc.in',
            'bio'      => 'Intern, communications',
            'role'     => 2,
        ],
        [
            'type'     => 'professional',
            'fname'    => 'Dr. Mehra',
            'lname'    => '',
            'username' => 'pro.mehra',
            'email'    => 'pro@abc.in',
            'bio'      => 'Public health professional',
            'role'     => 2,
        ],
        [
            'type'     => 'ngo',
            'fname'    => 'Prerna',
            'lname'    => 'NGO',
            'username' => 'prerna.ngo',
            'email'    => 'ngo@abc.in',
            'bio'      => 'Partner NGO contact',
            'role'     => 2,
        ],
    ];

    public function handle(): int
    {
        $password = (string) $this->option('password');
        $force = (bool) $this->option('force');
        $now = now();
        $baseUrl = rtrim((string) config('app.url'), '/');

        $rows = [];

        foreach (self::ACCOUNTS as $account) {
            $existing = DB::table('users')->where('email', $account['email'])->first();

            if ($existing && ! $force) {
                $this->line("Skipped {$account['type']} — {$account['email']} already exists (use --force to reset password).");
                $rows[] = [$account['type'], $account['email'], 'exists', $baseUrl . '/login/' . $this->loginPath($account['type'])];
                continue;
            }

            $payload = [
                'fname'      => $account['fname'],
                'lname'      => $account['lname'],
                'username'   => $account['username'],
                'email'      => $account['email'],
                'image'      => '',
                'bio'        => $account['bio'],
                'role'       => $account['role'],
                'type'       => $account['type'],
                'date'       => $now,
                'updated_at' => $now,
            ];

            if ($existing) {
                if ($force) {
                    $payload['password'] = Hash::make($password);
                }
                DB::table('users')->where('id', $existing->id)->update($payload);
                $action = $force ? 'updated' : 'kept';
            } else {
                $payload['password'] = Hash::make($password);
                $payload['created_at'] = $now;
                DB::table('users')->insert($payload);
                $action = 'created';
            }

            $this->info(ucfirst($account['type']) . " {$action}: {$account['email']}");
            $rows[] = [$account['type'], $account['email'], $action, $baseUrl . '/login/' . $this->loginPath($account['type'])];
        }

        $this->newLine();
        $this->table(['Portal', 'Email', 'Status', 'Login URL'], $rows);
        $this->newLine();
        $this->line('Default password: ' . $password);
        $this->line('All portals hub: ' . $baseUrl . '/login');

        return self::SUCCESS;
    }

    private function loginPath(string $type): string
    {
        return $type === 'professional' ? 'pro' : $type;
    }
}
