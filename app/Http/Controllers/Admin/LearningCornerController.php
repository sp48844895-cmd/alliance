<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesUploadedMedia;
use App\Http\Controllers\Controller;
use App\Services\KnowledgeHubPageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LearningCornerController extends Controller
{
    use HandlesUploadedMedia;

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $mainId = $request->query('main_id');
        $catId = $request->query('cat_id');
        $mType = $request->query('m_type');

        $query = DB::table('learning_corner')
            ->join('learning_cat as sub', 'learning_corner.cat_id', '=', 'sub.id')
            ->join('learning_cat as main', 'main.id', '=', 'sub.parent_id')
            ->whereNotNull('sub.parent_id')
            ->select(
                'learning_corner.id',
                'learning_corner.title',
                'learning_corner.content',
                'learning_corner.image',
                'learning_corner.m_type',
                'learning_corner.link',
                'learning_corner.admin',
                'learning_corner.date',
                'learning_corner.status',
                'learning_corner.cat_id',
                'sub.cat_name as sub_name',
                'main.cat_name as main_name',
                'main.id as main_id'
            );

        if ($q !== '') {
            $query->where('learning_corner.title', 'like', '%'.$q.'%');
        }
        if ($mainId !== null && $mainId !== '') {
            $query->where('main.id', (int) $mainId);
        }
        if ($catId !== null && $catId !== '') {
            $query->where('learning_corner.cat_id', (int) $catId);
        }
        if ($mType !== null && $mType !== '' && in_array($mType, ['book', 'posters', 'mobile kunji', 'video'], true)) {
            $query->where('learning_corner.m_type', $mType);
        }

        $resources = $query->orderBy('learning_corner.id', 'desc')->get();

        return view('admin.learning-corner.index', [
            'resources' => $resources,
            'subcategories' => $this->subcategoriesForSelect(),
            'mainCategories' => DB::table('learning_cat')
                ->whereNull('parent_id')
                ->where('status', 1)
                ->orderBy('cat_name')
                ->get(['id', 'cat_name']),
            'filters' => [
                'q' => $q,
                'main_id' => $mainId,
                'cat_id' => $catId,
                'm_type' => $mType,
            ],
        ]);
    }

    public function create()
    {
        $categoryForm = $this->categoryFormData();

        if ($categoryForm['mainCategories']->isEmpty()) {
            return redirect()
                ->route('admin.learning-main-cats.index')
                ->with('error', 'Create a main category before adding learning resources.');
        }

        if ($categoryForm['subcategories']->isEmpty()) {
            return redirect()
                ->route('admin.learning-sub-cats.index')
                ->with('error', 'Create a subcategory before adding learning resources.');
        }

        return view('admin.learning-corner.create', $categoryForm);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'cat_id' => 'required|integer|exists:learning_cat,id',
            'm_type' => 'required|in:book,posters,mobile kunji,video',
            'link' => 'required|url|max:500',
            'content' => 'nullable|string|max:500',
            'image' => 'nullable|image',
            'date' => 'required|date',
            'status' => 'required|in:0,1',
        ]);

        $this->ensureSubcategory((int) $data['cat_id']);

        $user = auth()->user();
        $adminName = trim(($user->fname ?? '').' '.($user->lname ?? ''));

        $filename = '';
        if ($request->hasFile('image')) {
            $filename = $this->storeUploadedFile('learning', $request->file('image'));
        }

        DB::table('learning_corner')->insert([
            'cat_id' => (int) $data['cat_id'],
            'title' => $data['title'],
            'content' => $data['content'] ?? '',
            'admin' => $adminName !== '' ? $adminName : ($user->fname ?? 'Admin'),
            'user_id' => (int) ($user->id ?? 1),
            'image' => $filename,
            'm_type' => $data['m_type'],
            'link' => $data['link'],
            'date' => $data['date'],
            'status' => (int) $data['status'],
        ]);

        $this->clearKnowledgeHubCache();

        return redirect()->route('admin.learning-corner.index')->with('success', 'Resource saved');
    }

    public function edit($id)
    {
        $resource = DB::table('learning_corner')->where('id', $id)->first();
        if (! $resource) {
            abort(404);
        }

        return view('admin.learning-corner.edit', array_merge(
            ['resource' => $resource],
            $this->categoryFormData((int) $resource->cat_id)
        ));
    }

    public function update(Request $request, $id)
    {
        $resource = DB::table('learning_corner')->where('id', $id)->first();
        if (! $resource) {
            abort(404);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'cat_id' => 'required|integer|exists:learning_cat,id',
            'm_type' => 'required|in:book,posters,mobile kunji,video',
            'link' => 'required|url|max:500',
            'content' => 'nullable|string|max:500',
            'image' => 'nullable|image',
            'date' => 'required|date',
            'status' => 'required|in:0,1',
        ]);

        $this->ensureSubcategory((int) $data['cat_id']);

        $filename = $resource->image;
        if ($request->hasFile('image')) {
            $filename = $this->replaceUploadedFile('learning', $request->file('image'), $filename);
        }

        DB::table('learning_corner')->where('id', $id)->update([
            'cat_id' => (int) $data['cat_id'],
            'title' => $data['title'],
            'content' => $data['content'] ?? '',
            'image' => $filename,
            'm_type' => $data['m_type'],
            'link' => $data['link'],
            'date' => $data['date'],
            'status' => (int) $data['status'],
        ]);

        $this->clearKnowledgeHubCache();

        return redirect()->route('admin.learning-corner.index')->with('success', 'Resource saved');
    }

    public function destroy($id)
    {
        $resource = DB::table('learning_corner')->where('id', $id)->first();
        if (! $resource) {
            abort(404);
        }

        $this->deleteUploadedFile('learning', $resource->image);

        DB::table('learning_corner')->where('id', $id)->delete();

        $this->clearKnowledgeHubCache();

        return redirect()->route('admin.learning-corner.index')->with('success', 'Resource deleted');
    }

    private function subcategoriesForSelect()
    {
        return DB::table('learning_cat as sub')
            ->join('learning_cat as main', 'main.id', '=', 'sub.parent_id')
            ->where('sub.status', 1)
            ->whereNotNull('sub.parent_id')
            ->orderBy('main.cat_name')
            ->orderBy('sub.cat_name')
            ->get([
                'sub.id',
                'sub.cat_name',
                'main.cat_name as main_name',
                'sub.parent_id as main_id',
            ]);
    }

    private function categoryFormData(?int $selectedCatId = null): array
    {
        $mainCategories = DB::table('learning_cat')
            ->whereNull('parent_id')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('cat_name')
            ->get(['id', 'cat_name']);

        $subcategories = DB::table('learning_cat as sub')
            ->join('learning_cat as main', 'main.id', '=', 'sub.parent_id')
            ->where('sub.status', 1)
            ->whereNotNull('sub.parent_id')
            ->orderBy('sub.cat_name')
            ->get([
                'sub.id as id',
                'sub.cat_name',
                'sub.parent_id as main_id',
            ]);

        $selectedMainId = old('main_id');
        $selectedCatId = old('cat_id', $selectedCatId);

        if ($selectedMainId === null && $selectedCatId) {
            $selectedMainId = DB::table('learning_cat')
                ->where('id', $selectedCatId)
                ->value('parent_id');
        }

        return [
            'mainCategories' => $mainCategories,
            'subcategories' => $subcategories,
            'selectedMainId' => $selectedMainId,
            'selectedCatId' => $selectedCatId,
        ];
    }

    private function ensureSubcategory(int $catId): void
    {
        $isSub = DB::table('learning_cat')
            ->where('id', $catId)
            ->whereNotNull('parent_id')
            ->exists();

        if (! $isSub) {
            throw ValidationException::withMessages([
                'cat_id' => ['Resources must be assigned to a subcategory.'],
            ]);
        }
    }

    private function clearKnowledgeHubCache(): void
    {
        app(KnowledgeHubPageService::class)->clearCache();
    }
}
