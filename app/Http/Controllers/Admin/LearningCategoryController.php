<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\TogglesRecordStatus;
use App\Http\Controllers\Controller;
use App\Services\KnowledgeHubPageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LearningCategoryController extends Controller
{
    use TogglesRecordStatus;

    public function index()
    {
        $categories = DB::table('learning_cat')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.learning-cats.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cat_name' => 'required|string|max:255',
            'cat_icon' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\-]+$/',
            'status'   => 'required|in:0,1',
        ]);

        $user = auth()->user();
        $adminName = trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''));

        DB::table('learning_cat')->insert([
            'cat_name'   => $data['cat_name'],
            'cat_icon'   => $data['cat_icon'],
            'status'     => (int) $data['status'],
            'admin_name' => $adminName !== '' ? $adminName : ($user->fname ?? 'Admin'),
            'created_at' => now(),
        ]);

        $this->clearKnowledgeHubCache();

        return redirect()->route('admin.learning-cats.index')->with('success', 'Category saved');
    }

    public function edit($id)
    {
        $category = DB::table('learning_cat')->where('id', $id)->first();
        if (!$category) {
            abort(404);
        }

        return view('admin.learning-cats.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = DB::table('learning_cat')->where('id', $id)->first();
        if (!$category) {
            abort(404);
        }

        $data = $request->validate([
            'cat_name' => 'required|string|max:255',
            'cat_icon' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\-]+$/',
            'status'   => 'required|in:0,1',
        ]);

        DB::table('learning_cat')->where('id', $id)->update([
            'cat_name' => $data['cat_name'],
            'cat_icon' => $data['cat_icon'],
            'status'   => (int) $data['status'],
        ]);

        $this->clearKnowledgeHubCache();

        return redirect()->route('admin.learning-cats.index')->with('success', 'Category saved');
    }

    public function toggleStatus($id)
    {
        $response = $this->toggleRecordStatus('learning_cat', $id, 'status', [], 'Category status updated');
        $this->clearKnowledgeHubCache();

        return $response;
    }

    public function destroy($id)
    {
        $category = DB::table('learning_cat')->where('id', $id)->first();
        if (!$category) {
            abort(404);
        }

        $inUse = (int) DB::table('learning_corner')->where('cat_id', $id)->count();
        if ($inUse > 0) {
            return redirect()->back()->with('error', 'Cannot delete: this category is used by ' . $inUse . ' resource(s).');
        }

        DB::table('learning_cat')->where('id', $id)->delete();

        $this->clearKnowledgeHubCache();

        return redirect()->route('admin.learning-cats.index')->with('success', 'Category deleted');
    }

    private function clearKnowledgeHubCache(): void
    {
        app(KnowledgeHubPageService::class)->clearCache();
    }
}
