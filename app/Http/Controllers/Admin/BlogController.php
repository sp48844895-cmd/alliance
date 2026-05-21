<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesUploadedMedia;
use App\Http\Controllers\Concerns\TogglesRecordStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    use HandlesUploadedMedia;
    use TogglesRecordStatus;

    public function index(Request $request)
    {
        $q      = trim((string) $request->query('q', ''));
        $catId  = $request->query('cat_id');
        $status = $request->query('status');

        $query = DB::table('blog')
            ->leftJoin('categories', 'blog.cat_id', '=', 'categories.id')
            ->select(
                'blog.id',
                'blog.title',
                'blog.image',
                'blog.admin',
                'blog.status',
                'blog.views',
                'blog.date_updated',
                'blog.cat_id',
                'categories.category_name'
            );

        if ($q !== '') {
            $query->where('blog.title', 'like', '%' . $q . '%');
        }
        if ($catId !== null && $catId !== '') {
            $query->where('blog.cat_id', (int) $catId);
        }
        if ($status !== null && $status !== '' && in_array($status, ['0', '1'], true)) {
            $query->where('blog.status', (int) $status);
        }

        $blogs = $query->orderBy('blog.date_updated', 'desc')
            ->orderBy('blog.id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $categories = DB::table('categories')
            ->select('id', 'category_name')
            ->orderBy('category_name')
            ->get();

        return view('admin.blogs.index', [
            'blogs'      => $blogs,
            'categories' => $categories,
            'filters'    => [
                'q'      => $q,
                'cat_id' => $catId,
                'status' => $status,
            ],
        ]);
    }

    public function create()
    {
        $categories = DB::table('categories')
            ->where('status', 1)
            ->orderBy('category_name')
            ->get(['id', 'category_name']);

        $districts = DB::table('district')
            ->where('status', 1)
            ->orderBy('district_name')
            ->get(['id', 'district_name']);

        return view('admin.blogs.create', compact('categories', 'districts'));
    }

    public function show($id)
    {
        $blog = DB::table('blog')
            ->leftJoin('categories', 'blog.cat_id', '=', 'categories.id')
            ->select(
                'blog.id',
                'blog.title',
                'blog.content',
                'blog.tag',
                'blog.image',
                'blog.admin',
                'blog.status',
                'blog.views',
                'blog.date_created',
                'blog.date_updated',
                'categories.category_name'
            )
            ->where('blog.id', $id)
            ->first();

        if (! $blog) {
            abort(404);
        }

        return view('admin.blogs.show', compact('blog'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255|unique:blog,title',
            'cat_id'  => 'required|integer|exists:categories,id',
            'content' => 'required|string',
            'tag'     => 'nullable|string',
            'location' => 'nullable|string|max:100',
            'status'  => 'required|in:0,1',
            'rate'    => 'nullable|integer|between:0,5',
            'image'   => 'nullable|image|max:4096',
        ]);

        $user = auth()->user();
        $authorName = trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''));

        $filename = '';
        if ($request->hasFile('image')) {
            $filename = $this->storeUploadedFile('story', $request->file('image'));
        }

        DB::table('blog')->insert([
            'cat_id'       => (int) $data['cat_id'],
            'title'        => $data['title'],
            'content'      => $data['content'],
            'tag'          => $data['tag'] ?? '',
            'location'     => $data['location'] ?? '',
            'admin'        => $authorName !== '' ? $authorName : ($user->fname ?? 'Admin'),
            'user_id'      => (int) ($user->id ?? 1),
            'status'       => (int) $data['status'],
            'rate'         => isset($data['rate']) ? (int) $data['rate'] : 0,
            'image'        => $filename,
            'date_created' => now()->toDateString(),
            'views'        => '0',
            'date_updated' => now(),
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Story saved');
    }

    public function edit($id)
    {
        $blog = DB::table('blog')->where('id', $id)->first();
        if (!$blog) {
            abort(404);
        }

        $categories = DB::table('categories')
            ->where('status', 1)
            ->orderBy('category_name')
            ->get(['id', 'category_name']);

        $districts = DB::table('district')
            ->where('status', 1)
            ->orderBy('district_name')
            ->get(['id', 'district_name']);

        return view('admin.blogs.edit', compact('blog', 'categories', 'districts'));
    }

    public function update(Request $request, $id)
    {
        $blog = DB::table('blog')->where('id', $id)->first();
        if (!$blog) {
            abort(404);
        }

        $data = $request->validate([
            'title'         => 'required|string|max:255|unique:blog,title,' . $id,
            'cat_id'        => 'required|integer|exists:categories,id',
            'content'       => 'required|string',
            'tag'           => 'nullable|string',
            'location'      => 'nullable|string|max:100',
            'status'        => 'required|in:0,1',
            'rate'          => 'nullable|integer|between:0,5',
            'image'         => 'nullable|image|max:4096',
            'delete_image'  => 'nullable|boolean',
        ]);

        $filename = $blog->image;

        if ($request->boolean('delete_image') && $filename) {
            $this->deleteUploadedFile('story', $filename);
            $filename = '';
        }

        if ($request->hasFile('image')) {
            $filename = $this->replaceUploadedFile('story', $request->file('image'), $filename);
        }

        DB::table('blog')->where('id', $id)->update([
            'cat_id'       => (int) $data['cat_id'],
            'title'        => $data['title'],
            'content'      => $data['content'],
            'tag'          => $data['tag'] ?? '',
            'location'     => $data['location'] ?? '',
            'status'       => (int) $data['status'],
            'rate'         => isset($data['rate']) ? (int) $data['rate'] : 0,
            'image'        => $filename,
            'date_updated' => now(),
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Story saved');
    }

    public function toggleStatus($id)
    {
        return $this->toggleRecordStatus('blog', $id, 'status', ['date_updated' => now()], 'Story status updated');
    }

    public function destroy($id)
    {
        $blog = DB::table('blog')->where('id', $id)->first();
        if (!$blog) {
            abort(404);
        }

        if ($blog->image) {
            $this->deleteUploadedFile('story', (string) $blog->image);
        }

        DB::table('blog')->where('id', $id)->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Story deleted');
    }
}
