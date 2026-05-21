<?php

namespace App\Console\Commands;

use App\Services\PageContentService;
use Database\Seeders\PageContentSeeder;
use Database\Seeders\StoryArchiveSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SyncPageContent extends Command
{
    protected $signature = 'pages:sync';

    protected $description = 'Seed all CMS page sections and story archive, then clear page cache';

    public function handle(PageContentService $pageContent): int
    {
        $this->callSeeder(PageContentSeeder::class);
        $this->callSeeder(StoryArchiveSeeder::class);

        $pageContent->clearCache();
        Artisan::call('view:clear');

        $this->info('All page content synced.');

        return self::SUCCESS;
    }

    private function callSeeder(string $class): void
    {
        $this->info('Running '.$class.'...');
        $this->call('db:seed', ['--class' => $class, '--force' => true]);
    }
}
