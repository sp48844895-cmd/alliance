<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

trait TogglesRecordStatus
{
    protected function toggleRecordStatus(
        string $table,
        int|string $id,
        string $statusColumn = 'status',
        array $extraUpdate = [],
        string $successMessage = 'Status updated'
    ): RedirectResponse {
        $record = DB::table($table)->where('id', $id)->first();

        if (! $record) {
            abort(404);
        }

        $update = array_merge([
            $statusColumn => (int) (! (bool) $record->{$statusColumn}),
        ], $extraUpdate);

        DB::table($table)->where('id', $id)->update($update);

        return redirect()->back()->with('success', $successMessage);
    }
}
