<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesUploadedMedia;
use App\Http\Controllers\Controller;
use App\Services\KnowledgeHubPageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LearningCornerController extends Controller
{
    use HandlesUploadedMedia;

    public function index(Request $request)
    {
        $q      = trim((string) $request->query('q', ''));
        $catId  = $request->query('cat_id');
        $mType  = $request->query('m_type');

        $query = DB::table('learning_corner')
            ->leftJoin('learning_cat', 'learning_corner.cat_id', '=', 'learning_cat.id')
            ->select(
                'learning_corner.id',
                'learning_corner.title',
                'learning_corner.content',
                'learning_corner.image',
                'learning_corner.m_type',
                'learning_corner.link',
                'learning_corner.admin',
                'learning_corner.date',
                'learning_corner.cat_id',
                'learning_cat.cat_name'
            );

        if ($q !== '') {
            $query->where('learning_corner.title', 'like', '%' . $q . '%');
        }
        if ($catId !== null && $catId !== '') {
            $query->where('learning_corner.cat_id', (int) $catId);
        }
        if ($mType !== null && $mType !== '' && in_array($mType, ['book', 'posters', 'mobile kunji', 'video'], true)) {
            $query->where('learning_corner.m_type', $mType);
        }

        $resources = $query->orderBy('learning_corner.id', 'desc')
            ->paginate(12)
            ->withQueryString();

        $categories = DB::table('learning_cat')
            ->where('status', 1)
            ->orderBy('cat_name')
            ->get(['id', 'cat_name']);

        return view('admin.learning-corner.index', [
            'resources'  => $resources,
            'categories' => $categories,
            'filters'    => [
                'q'      => $q,
                'cat_id' => $catId,
                'm_type' => $mType,
            ],
        ]);
    }

    public function create()
    {
        $categories = DB::table('learning_cat')
            ->where('status', 1)
            ->orderBy('cat_name')
            ->get(['id', 'cat_name']);

        return view('admin.learning-corner.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'cat_id'  => 'required|integer|exists:learning_cat,id',
            'm_type'  => 'required|in:book,posters,mobile kunji,video',
            'link'    => 'required|url|max:500',
            'content' => 'nullable|string|max:500',
            'image'   => 'nullable|image|max:4096',
        ]);

        $user = auth()->user();
        $adminName = trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''));

        $filename = '';
        if ($request->hasFile('image')) {
            $filename = $this->storeUploadedFile('learning', $request->file('image'));
        }

        DB::table('learning_corner')->insert([
            'cat_id'  => (int) $data['cat_id'],
            'title'   => $data['title'],
            'content' => $data['content'] ?? '',
            'admin'   => $adminName !== '' ? $adminName : ($user->fname ?? 'Admin'),
            'user_id' => (int) ($user->id ?? 1),
            'image'   => $filename,
            'm_type'  => $data['m_type'],
            'link'    => $data['link'],
            'date'    => now()->toDateString(),
        ]);

        $this->clearKnowledgeHubCache();

        return redirect()->route('admin.learning-corner.index')->with('success', 'Resource saved');
    }

    public function edit($id)
    {
        $resource = DB::table('learning_corner')->where('id', $id)->first();
        if (!$resource) {
            abort(404);
        }

        $categories = DB::table('learning_cat')
            ->where('status', 1)
            ->orderBy('cat_name')
            ->get(['id', 'cat_name']);

        return view('admin.learning-corner.edit', compact('resource', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $resource = DB::table('learning_corner')->where('id', $id)->first();
        if (!$resource) {
            abort(404);
        }

        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'cat_id'  => 'required|integer|exists:learning_cat,id',
            'm_type'  => 'required|in:book,posters,mobile kunji,video',
            'link'    => 'required|url|max:500',
            'content' => 'nullable|string|max:500',
            'image'   => 'nullable|image|max:4096',
        ]);

        $filename = $resource->image;
        if ($request->hasFile('image')) {
            $filename = $this->replaceUploadedFile('learning', $request->file('image'), $filename);
        }

        DB::table('learning_corner')->where('id', $id)->update([
            'cat_id'  => (int) $data['cat_id'],
            'title'   => $data['title'],
            'content' => $data['content'] ?? '',
            'image'   => $filename,
            'm_type'  => $data['m_type'],
            'link'    => $data['link'],
        ]);

        $this->clearKnowledgeHubCache();

        return redirect()->route('admin.learning-corner.index')->with('success', 'Resource saved');
    }

    public function destroy($id)
    {
        $resource = DB::table('learning_corner')->where('id', $id)->first();
        if (!$resource) {
            abort(404);
        }

        $this->deleteUploadedFile('learning', $resource->image);

        DB::table('learning_corner')->where('id', $id)->delete();

        $this->clearKnowledgeHubCache();

        return redirect()->route('admin.learning-corner.index')->with('success', 'Resource deleted');
    }

    private function clearKnowledgeHubCache(): void
    {
        app(KnowledgeHubPageService::class)->clearCache();
    }
}
