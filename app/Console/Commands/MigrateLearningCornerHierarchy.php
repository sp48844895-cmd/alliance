<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateLearningCornerHierarchy extends Command
{
    protected $signature = 'learning-corner:migrate-hierarchy {--dry-run : Report changes without writing}';

    protected $description = 'Move resources from main categories to default subcategories and flatten depth > 2';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        if ($dryRun) {
            $this->warn('Dry run — no database changes will be made.');
        }

        $movedResources = 0;
        $createdSubs = 0;
        $flattened = 0;

        $mainWithResources = DB::table('learning_cat as main')
            ->join('learning_corner', 'learning_corner.cat_id', '=', 'main.id')
            ->whereNull('main.parent_id')
            ->select('main.id', 'main.cat_name')
            ->distinct()
            ->get();

        foreach ($mainWithResources as $main) {
            $subId = DB::table('learning_cat')
                ->where('parent_id', $main->id)
                ->where('cat_name', 'General')
                ->value('id');

            if (! $subId) {
                $this->line("Create subcategory \"General\" under main #{$main->id} ({$main->cat_name})");
                if (! $dryRun) {
                    $subId = DB::table('learning_cat')->insertGetId([
                        'parent_id' => $main->id,
                        'cat_name' => 'General',
                        'cat_icon' => 'icon-folder',
                        'description' => '',
                        'sort_order' => 0,
                        'status' => 1,
                        'admin_name' => 'System',
                        'created_at' => now(),
                    ]);
                }
                $createdSubs++;
            }

            $count = (int) DB::table('learning_corner')->where('cat_id', $main->id)->count();
            if ($count > 0) {
                $this->line("Move {$count} resource(s) from main #{$main->id} to sub #{$subId}");
                if (! $dryRun && $subId) {
                    DB::table('learning_corner')->where('cat_id', $main->id)->update(['cat_id' => $subId]);
                }
                $movedResources += $count;
            }
        }

        $deepSubs = DB::table('learning_cat as sub')
            ->join('learning_cat as parent', 'parent.id', '=', 'sub.parent_id')
            ->whereNotNull('sub.parent_id')
            ->whereNotNull('parent.parent_id')
            ->select('sub.id', 'sub.cat_name', 'sub.parent_id', 'parent.parent_id as main_id')
            ->get();

        foreach ($deepSubs as $sub) {
            $this->line("Flatten sub #{$sub->id} ({$sub->cat_name}) → parent main #{$sub->main_id}");
            if (! $dryRun) {
                DB::table('learning_cat')->where('id', $sub->id)->update(['parent_id' => $sub->main_id]);
            }
            $flattened++;
        }

        $orphans = DB::table('learning_cat as sub')
            ->leftJoin('learning_cat as main', 'main.id', '=', 'sub.parent_id')
            ->whereNotNull('sub.parent_id')
            ->where(function ($q) {
                $q->whereNull('main.id')->orWhereNotNull('main.parent_id');
            })
            ->select('sub.id', 'sub.cat_name', 'sub.parent_id')
            ->get();

        foreach ($orphans as $sub) {
            $newMain = DB::table('learning_cat')->whereNull('parent_id')->where('status', 1)->orderBy('id')->value('id');
            if (! $newMain) {
                $this->error('Orphan subcategory #'.$sub->id.' but no main category exists to reassign.');

                continue;
            }
            $this->line("Reassign orphan sub #{$sub->id} ({$sub->cat_name}) → main #{$newMain}");
            if (! $dryRun) {
                DB::table('learning_cat')->where('id', $sub->id)->update(['parent_id' => $newMain]);
            }
            $flattened++;
        }

        $this->info("Done. Created subs: {$createdSubs}, moved resources: {$movedResources}, flattened/reassigned: {$flattened}.");

        return self::SUCCESS;
    }
}
