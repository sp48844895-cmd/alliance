<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('admin_dashboard_stats', 60, function () {
            $pendingStories = 0;
            try {
                $pendingStories = (int) DB::table('stories')
                    ->where('category', '!=', 'Events')
                    ->where('approval_status', 'pending')
                    ->count();
            } catch (\Exception $e) {
                // stories table or approval_status column not yet migrated
            }

            $contactMsgs = 0;
            try {
                $contactMsgs = (int) DB::table('contact_messages')
                    ->where('status', 'new')
                    ->count();
            } catch (\Exception $e) {
                // contact_messages table not yet migrated
            }

            return [
                'blogs_total'       => (int) DB::table('blog')->count(),
                'blogs_published'   => (int) DB::table('blog')->where('status', 1)->count(),
                'events_total'      => (int) DB::table('event')->count(),
                'memberships_total' => (int) DB::table('membership')->count(),
                'unread_contacts'   => (int) DB::table('mails')->where('status', 0)->count(),
                'new_contact_msgs'  => $contactMsgs,
                'categories_total'  => (int) DB::table('categories')->count(),
                'learning_total'    => (int) DB::table('learning_corner')->count(),
                'pending_stories'   => $pendingStories,
            ];
        });

        $recentBlogs = DB::table('blog')
            ->leftJoin('categories', 'blog.cat_id', '=', 'categories.id')
            ->select(
                'blog.id',
                'blog.title',
                'blog.status',
                'blog.date_created',
                'categories.category_name'
            )
            ->orderBy('blog.date_created', 'desc')
            ->orderBy('blog.id', 'desc')
            ->limit(5)
            ->get();

        $recentMembers = DB::table('membership')
            ->select('id', 'name', 'type', 'code', 'date')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBlogs', 'recentMembers'));
    }
}
