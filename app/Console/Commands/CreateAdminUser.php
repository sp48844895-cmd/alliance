<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create-user
                            {--email=admin@abc.in : Admin email address}
                            {--password= : Plain password (uses logins:create default if omitted)}
                            {--force : Overwrite password if user already exists}';

    protected $description = 'Create or update the admin login user (delegates to logins:create for admin only).';

    public function handle(): int
    {
        $email = (string) $this->option('email');
        $password = $this->option('password') ?: 'abc@1234';

        $this->call('logins:create', [
            '--password' => $password,
            '--force' => $this->option('force'),
        ]);

        $this->newLine();
        $this->line('Admin portal: ' . rtrim((string) config('app.url'), '/') . '/login/admin');
        $this->line('Admin email:  ' . $email);

        return self::SUCCESS;
    }
}
