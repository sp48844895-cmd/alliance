<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\JsonResponse;
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
    ): RedirectResponse|JsonResponse {
        $record = DB::table($table)->where('id', $id)->first();

        if (! $record) {
            abort(404);
        }

        $active = ! (bool) $record->{$statusColumn};

        $update = array_merge([
            $statusColumn => (int) $active,
        ], $extraUpdate);

        DB::table($table)->where('id', $id)->update($update);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'message' => $successMessage,
                'active' => $active,
                'statusHtml' => view('components.admin.status-pill', ['active' => $active])->render(),
            ]);
        }

        return redirect()->back()->with('success', $successMessage);
    }
}
