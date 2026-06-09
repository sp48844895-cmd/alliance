<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\TogglesRecordStatus;
use App\Http\Controllers\Controller;
use App\Services\KnowledgeHubPageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LearningMainCategoryController extends Controller
{
    use TogglesRecordStatus;

    public function index()
    {
        $categories = DB::table('learning_cat')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('cat_name')
            ->get();

        $subCounts = DB::table('learning_cat')
            ->whereNotNull('parent_id')
            ->selectRaw('parent_id, count(*) as total')
            ->groupBy('parent_id')
            ->pluck('total', 'parent_id');

        return view('admin.learning-main-cats.index', compact('categories', 'subCounts'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedCategory($request);

        DB::table('learning_cat')->insert([
            'parent_id'   => null,
            'cat_name'    => $data['cat_name'],
            'cat_icon'    => $data['cat_icon'],
            'description' => $data['description'],
            'sort_order'  => $data['sort_order'],
            'status'      => (int) $data['status'],
            'admin_name'  => $this->adminName(),
            'created_at'  => now(),
        ]);

        $this->clearCache();

        return redirect()->route('admin.learning-main-cats.index')->with('success', 'Main category saved');
    }

    public function edit($id)
    {
        $category = $this->findMainOrFail($id);

        return view('admin.learning-main-cats.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $this->findMainOrFail($id);
        $data = $this->validatedCategory($request);

        DB::table('learning_cat')->where('id', $id)->update([
            'parent_id'   => null,
            'cat_name'    => $data['cat_name'],
            'cat_icon'    => $data['cat_icon'],
            'description' => $data['description'],
            'sort_order'  => $data['sort_order'],
            'status'      => (int) $data['status'],
        ]);

        $this->clearCache();

        return redirect()->route('admin.learning-main-cats.index')->with('success', 'Main category saved');
    }

    public function toggleStatus($id)
    {
        $this->findMainOrFail($id);
        $response = $this->toggleRecordStatus('learning_cat', $id, 'status', [], 'Category status updated');
        $this->clearCache();

        return $response;
    }

    public function destroy($id)
    {
        $this->findMainOrFail($id);

        $subCount = (int) DB::table('learning_cat')->where('parent_id', $id)->count();
        if ($subCount > 0) {
            return redirect()->back()->with('error', 'Cannot delete: this main category has '.$subCount.' subcategory(ies).');
        }

        $inUse = (int) DB::table('learning_corner')->where('cat_id', $id)->count();
        if ($inUse > 0) {
            return redirect()->back()->with('error', 'Cannot delete: '.$inUse.' resource(s) are linked to this category.');
        }

        DB::table('learning_cat')->where('id', $id)->delete();
        $this->clearCache();

        return redirect()->route('admin.learning-main-cats.index')->with('success', 'Main category deleted');
    }

    private function findMainOrFail(int|string $id): object
    {
        $category = DB::table('learning_cat')
            ->where('id', $id)
            ->whereNull('parent_id')
            ->first();

        if (! $category) {
            abort(404);
        }

        return $category;
    }

    private function validatedCategory(Request $request): array
    {
        $data = $request->validate([
            'cat_name'    => 'required|string|max:255',
            'cat_icon'    => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\-]+$/',
            'description' => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer|min:0|max:9999',
            'status'      => 'required|in:0,1',
        ]);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['description'] = $data['description'] ?? '';

        return $data;
    }

    private function adminName(): string
    {
        $user = auth()->user();
        $adminName = trim(($user->fname ?? '').' '.($user->lname ?? ''));

        return $adminName !== '' ? $adminName : ($user->fname ?? 'Admin');
    }

    private function clearCache(): void
    {
        app(KnowledgeHubPageService::class)->clearCache();
    }
}
