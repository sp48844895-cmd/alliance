<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\TogglesRecordStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    use TogglesRecordStatus;

    public function index()
    {
        $categories = DB::table('categories')
            ->leftJoin('blog', 'blog.cat_id', '=', 'categories.id')
            ->select(
                'categories.id',
                'categories.category_name',
                'categories.status',
                'categories.create_time',
                'categories.admin_name',
                DB::raw('COUNT(blog.id) as blog_count')
            )
            ->groupBy(
                'categories.id',
                'categories.category_name',
                'categories.status',
                'categories.create_time',
                'categories.admin_name'
            )
            ->orderBy('categories.id', 'desc')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_name' => 'required|string|max:100|unique:categories,category_name',
            'status'        => 'nullable|in:0,1',
        ]);

        $user = auth()->user();
        $adminName = trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''));

        DB::table('categories')->insert([
            'category_name' => $data['category_name'],
            'status'        => isset($data['status']) ? (int) $data['status'] : 1,
            'create_time'   => now(),
            'admin_name'    => $adminName !== '' ? $adminName : ($user->fname ?? 'Admin'),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created');
    }

    public function edit($id)
    {
        $category = DB::table('categories')->where('id', $id)->first();
        if (!$category) {
            abort(404);
        }

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = DB::table('categories')->where('id', $id)->first();
        if (!$category) {
            abort(404);
        }

        $data = $request->validate([
            'category_name' => 'required|string|max:100|unique:categories,category_name,' . $id,
            'status'        => 'required|in:0,1',
        ]);

        DB::table('categories')->where('id', $id)->update([
            'category_name' => $data['category_name'],
            'status'        => (int) $data['status'],
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated');
    }

    public function toggleStatus($id)
    {
        return $this->toggleRecordStatus('categories', $id, 'status', [], 'Category status updated');
    }

    public function destroy($id)
    {
        $category = DB::table('categories')->where('id', $id)->first();
        if (!$category) {
            abort(404);
        }

        $blogCount = (int) DB::table('blog')->where('cat_id', $id)->count();
        if ($blogCount > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete: ' . $blogCount . ' blog(s) belong to this category.');
        }

        DB::table('categories')->where('id', $id)->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted');
    }
}
