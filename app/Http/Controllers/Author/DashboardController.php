<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = (int) auth()->id();

        $stats = [
            'total'    => (int) DB::table('stories')->where('created_by', $userId)->where('category', '!=', 'Events')->count(),
            'pending'  => (int) DB::table('stories')->where('created_by', $userId)->where('category', '!=', 'Events')->where('approval_status', 'pending')->count(),
            'approved' => (int) DB::table('stories')->where('created_by', $userId)->where('category', '!=', 'Events')->where('approval_status', 'approved')->count(),
            'rejected' => (int) DB::table('stories')->where('created_by', $userId)->where('category', '!=', 'Events')->where('approval_status', 'rejected')->count(),
        ];

        $recentStories = DB::table('stories')
            ->where('created_by', $userId)
            ->where('category', '!=', 'Events')
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get(['id', 'title', 'slug', 'approval_status', 'updated_at']);

        return view('author.dashboard', compact('stats', 'recentStories'));
    }
}
