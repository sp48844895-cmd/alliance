<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = (int) Auth::id();

        $logs = DB::table('intern_work_logs')
            ->where('user_id', $userId)
            ->orderByDesc('log_date')
            ->orderByDesc('id')
            ->paginate(15);

        $totalHours = (float) DB::table('intern_work_logs')
            ->where('user_id', $userId)
            ->sum('hours_worked');

        return view('intern.dashboard', [
            'logs' => $logs,
            'totalHours' => $totalHours,
            'logCount' => (int) DB::table('intern_work_logs')->where('user_id', $userId)->count(),
        ]);
    }

    public function storeWorkLog(Request $request)
    {
        $data = $request->validate([
            'log_date' => 'required|date|before_or_equal:today',
            'tasks_done' => 'required|string|max:5000',
            'hours_worked' => 'required|numeric|min:0.25|max:24',
            'notes' => 'nullable|string|max:2000',
        ]);

        DB::table('intern_work_logs')->insert([
            'user_id' => (int) Auth::id(),
            'log_date' => $data['log_date'],
            'tasks_done' => trim($data['tasks_done']),
            'hours_worked' => $data['hours_worked'],
            'notes' => isset($data['notes']) ? trim($data['notes']) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('intern.dashboard')
            ->with('work_log_status', 'Work log entry saved.');
    }
}
