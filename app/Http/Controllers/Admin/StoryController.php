<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoryController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $approvalStatus = $request->query('approval_status');

        $query = DB::table('stories')
            ->leftJoin('users', 'users.id', '=', 'stories.created_by')
            ->where('stories.category', '!=', 'Events')
            ->select(
                'stories.id',
                'stories.title',
                'stories.slug',
                'stories.category',
                'stories.thumbnail_path',
                'stories.approval_status',
                'stories.status',
                'stories.created_at',
                'stories.updated_at',
                'users.fname',
                'users.lname',
                'users.email'
            );

        if ($q !== '') {
            $query->where('stories.title', 'like', '%' . $q . '%');
        }

        if ($approvalStatus !== null && $approvalStatus !== '' && in_array($approvalStatus, ['pending', 'approved', 'rejected'], true)) {
            $query->where('stories.approval_status', $approvalStatus);
        }

        $stories = $query
            ->orderByRaw("FIELD(stories.approval_status, 'pending', 'rejected', 'approved')")
            ->orderByDesc('stories.created_at')
            ->paginate(15)
            ->withQueryString();

        $pendingCount = (int) DB::table('stories')
            ->where('category', '!=', 'Events')
            ->where('approval_status', 'pending')
            ->count();

        return view('admin.stories.index', [
            'stories'       => $stories,
            'pendingCount'  => $pendingCount,
            'filters'       => [
                'q'               => $q,
                'approval_status' => $approvalStatus,
            ],
        ]);
    }

    public function show($id)
    {
        $story = $this->findStory($id);

        return view('admin.stories.show', compact('story'));
    }

    public function approve(Request $request, $id)
    {
        $story = $this->findStory($id);

        if ($story->approval_status === 'approved') {
            return back()->with('error', 'Story is already approved.');
        }

        DB::table('stories')->where('id', $story->id)->update([
            'approval_status' => 'approved',
            'status'          => 'active',
            'approved_by'     => (int) auth()->id(),
            'approved_at'     => now(),
            'rejection_note'  => null,
            'updated_at'      => now(),
        ]);

        return back()->with('success', 'Story approved and published on the public site.');
    }

    public function reject(Request $request, $id)
    {
        $data = $request->validate([
            'rejection_note' => 'nullable|string|max:1000',
        ]);

        $story = $this->findStory($id);

        DB::table('stories')->where('id', $story->id)->update([
            'approval_status' => 'rejected',
            'status'          => 'inactive',
            'approved_by'     => null,
            'approved_at'     => null,
            'rejection_note'  => $data['rejection_note'] ?? '',
            'updated_at'      => now(),
        ]);

        return back()->with('success', 'Story rejected. The author can edit and resubmit.');
    }

    public function destroy($id)
    {
        $story = $this->findStory($id);

        if (! empty($story->thumbnail_path) && ! Str::startsWith($story->thumbnail_path, ['http://', 'https://'])) {
            $full = public_path(ltrim($story->thumbnail_path, '/'));
            if (is_file($full)) {
                unlink($full);
            }
        }

        DB::table('stories')->where('id', $story->id)->delete();

        return redirect()
            ->route('admin.stories.index')
            ->with('success', 'Story deleted.');
    }

    private function findStory($id): object
    {
        $story = DB::table('stories')
            ->leftJoin('users', 'users.id', '=', 'stories.created_by')
            ->where('stories.id', $id)
            ->where('stories.category', '!=', 'Events')
            ->first([
                'stories.*',
                'users.fname',
                'users.lname',
                'users.email',
            ]);

        abort_unless($story, 404);

        return $story;
    }
}
