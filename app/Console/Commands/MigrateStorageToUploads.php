<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateStorageToUploads extends Command
{
    protected $signature = 'media:migrate-to-uploads
                            {--dry-run : List files that would be copied without writing}
                            {--force : Overwrite existing files in uploads}';

    protected $description = 'Copy legacy storage/app/public files into public/uploads (preserves originals)';

    /** @var array<string, string> */
    private array $folderMap = [
        'story' => 'uploads/story',
        'event' => 'uploads/events',
        'logos' => 'uploads/memberships',
        'insights' => 'uploads/insights',
        'sbc-pool' => 'uploads/sbc-pool',
        'programs' => 'uploads/programs',
    ];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');
        $sourceRoot = storage_path('app/public');

        if (! is_dir($sourceRoot)) {
            $this->error('Source directory not found: '.$sourceRoot);

            return self::FAILURE;
        }

        if ($dryRun) {
            $this->warn('Dry run — no files will be copied.');
        }

        $copied = 0;
        $skipped = 0;

        foreach ($this->folderMap as $sourceDir => $targetRelative) {
            $from = $sourceRoot.DIRECTORY_SEPARATOR.$sourceDir;
            $to = public_path($targetRelative);

            if (! is_dir($from)) {
                $this->line("Skip missing source: {$sourceDir}");

                continue;
            }

            if (! $dryRun && ! is_dir($to)) {
                mkdir($to, 0755, true);
            }

            $this->info("{$sourceDir} → {$targetRelative}");

            foreach ($this->filesIn($from) as $file) {
                $relative = ltrim(str_replace($from, '', $file->getPathname()), DIRECTORY_SEPARATOR);
                $dest = $to.DIRECTORY_SEPARATOR.$relative;
                $destDir = dirname($dest);

                if (is_file($dest) && ! $force) {
                    $skipped++;
                    continue;
                }

                if ($dryRun) {
                    $this->line("  would copy: {$relative}");
                    $copied++;

                    continue;
                }

                if (! is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }

                if (copy($file->getPathname(), $dest)) {
                    $copied++;
                } else {
                    $this->warn("  failed: {$relative}");
                }
            }
        }

        $this->newLine();
        $this->info(($dryRun ? 'Would copy' : 'Copied')." {$copied} file(s), skipped {$skipped} existing.");

        return self::SUCCESS;
    }

    /**
     * @return \SplFileInfo[]
     */
    private function filesIn(string $directory): array
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $files[] = $file;
            }
        }

        return $files;
    }
}
