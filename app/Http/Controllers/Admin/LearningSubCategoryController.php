<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\TogglesRecordStatus;
use App\Http\Controllers\Controller;
use App\Services\KnowledgeHubPageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LearningSubCategoryController extends Controller
{
    use TogglesRecordStatus;

    public function index()
    {
        $categories = DB::table('learning_cat as sub')
            ->join('learning_cat as main', 'main.id', '=', 'sub.parent_id')
            ->whereNotNull('sub.parent_id')
            ->orderBy('main.cat_name')
            ->orderBy('sub.sort_order')
            ->orderBy('sub.cat_name')
            ->get([
                'sub.id',
                'sub.cat_name',
                'sub.cat_icon',
                'sub.description',
                'sub.sort_order',
                'sub.status',
                'sub.created_at',
                'sub.admin_name',
                'sub.parent_id',
                'main.cat_name as main_name',
            ]);

        $resourceCounts = DB::table('learning_corner')
            ->selectRaw('cat_id, count(*) as total')
            ->groupBy('cat_id')
            ->pluck('total', 'cat_id');

        $mainCategories = DB::table('learning_cat')
            ->whereNull('parent_id')
            ->where('status', 1)
            ->orderBy('cat_name')
            ->get(['id', 'cat_name']);

        return view('admin.learning-sub-cats.index', compact('categories', 'resourceCounts', 'mainCategories'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedSubcategory($request);

        DB::table('learning_cat')->insert([
            'parent_id'   => (int) $data['parent_id'],
            'cat_name'    => $data['cat_name'],
            'cat_icon'    => $data['cat_icon'],
            'description' => $data['description'],
            'sort_order'  => $data['sort_order'],
            'status'      => (int) $data['status'],
            'admin_name'  => $this->adminName(),
            'created_at'  => now(),
        ]);

        $this->clearCache();

        return redirect()->route('admin.learning-sub-cats.index')->with('success', 'Subcategory saved');
    }

    public function edit($id)
    {
        $category = $this->findSubOrFail($id);
        $mainCategories = DB::table('learning_cat')
            ->whereNull('parent_id')
            ->orderBy('cat_name')
            ->get(['id', 'cat_name']);

        return view('admin.learning-sub-cats.edit', compact('category', 'mainCategories'));
    }

    public function update(Request $request, $id)
    {
        $this->findSubOrFail($id);
        $data = $this->validatedSubcategory($request);

        DB::table('learning_cat')->where('id', $id)->update([
            'parent_id'   => (int) $data['parent_id'],
            'cat_name'    => $data['cat_name'],
            'cat_icon'    => $data['cat_icon'],
            'description' => $data['description'],
            'sort_order'  => $data['sort_order'],
            'status'      => (int) $data['status'],
        ]);

        $this->clearCache();

        return redirect()->route('admin.learning-sub-cats.index')->with('success', 'Subcategory saved');
    }

    public function toggleStatus($id)
    {
        $this->findSubOrFail($id);
        $response = $this->toggleRecordStatus('learning_cat', $id, 'status', [], 'Subcategory status updated');
        $this->clearCache();

        return $response;
    }

    public function destroy($id)
    {
        $this->findSubOrFail($id);

        $inUse = (int) DB::table('learning_corner')->where('cat_id', $id)->count();
        if ($inUse > 0) {
            return redirect()->back()->with('error', 'Cannot delete: this subcategory is used by '.$inUse.' resource(s).');
        }

        DB::table('learning_cat')->where('id', $id)->delete();
        $this->clearCache();

        return redirect()->route('admin.learning-sub-cats.index')->with('success', 'Subcategory deleted');
    }

    private function findSubOrFail(int|string $id): object
    {
        $category = DB::table('learning_cat')
            ->where('id', $id)
            ->whereNotNull('parent_id')
            ->first();

        if (! $category) {
            abort(404);
        }

        return $category;
    }

    private function validatedSubcategory(Request $request): array
    {
        $data = $request->validate([
            'parent_id'   => 'required|integer|exists:learning_cat,id',
            'cat_name'    => 'required|string|max:255',
            'cat_icon'    => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\-]+$/',
            'description' => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer|min:0|max:9999',
            'status'      => 'required|in:0,1',
        ]);

        $main = DB::table('learning_cat')
            ->where('id', $data['parent_id'])
            ->whereNull('parent_id')
            ->first();

        if (! $main) {
            throw ValidationException::withMessages([
                'parent_id' => ['Parent must be a main category.'],
            ]);
        }

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
