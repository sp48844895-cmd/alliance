<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoryController extends Controller
{
    public function index(Request $request)
    {
        $userId = (int) auth()->id();
        $status = $request->query('approval_status');

        $query = DB::table('stories')
            ->where('created_by', $userId)
            ->where('category', '!=', 'Events');

        if ($status !== null && $status !== '' && in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $query->where('approval_status', $status);
        }

        $stories = $query
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('author.stories.index', [
            'stories' => $stories,
            'filters' => ['approval_status' => $status],
        ]);
    }

    public function create()
    {
        return view('author.stories.create', [
            'categories' => $this->storyCategories(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'content'  => 'required|string',
            'tag'      => 'nullable|string|max:255',
            'image'    => 'nullable|image|max:4096',
        ]);

        $slug = $this->uniqueSlug($data['title']);
        $thumbnail = $this->storeThumbnail($request);

        DB::table('stories')->insert([
            'title'            => $data['title'],
            'slug'             => $slug,
            'category'         => $data['category'],
            'content'          => $data['content'],
            'tag'              => $data['tag'] ?? '',
            'thumbnail_path'   => $thumbnail,
            'status'           => 'inactive',
            'approval_status'  => 'pending',
            'created_by'       => (int) auth()->id(),
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return redirect()
            ->route('author.stories.index')
            ->with('success', 'Story submitted for admin approval. It will appear on the public site after review.');
    }

    public function edit($id)
    {
        $story = $this->findOwnStory($id);
        if (! in_array($story->approval_status, ['pending', 'rejected'], true)) {
            return redirect()
                ->route('author.stories.index')
                ->with('error', 'Approved stories cannot be edited.');
        }

        return view('author.stories.edit', [
            'story'      => $story,
            'categories' => $this->storyCategories(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $story = $this->findOwnStory($id);
        if (! in_array($story->approval_status, ['pending', 'rejected'], true)) {
            return redirect()
                ->route('author.stories.index')
                ->with('error', 'Approved stories cannot be edited.');
        }

        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'content'  => 'required|string',
            'tag'      => 'nullable|string|max:255',
            'image'    => 'nullable|image|max:4096',
        ]);

        $update = [
            'title'           => $data['title'],
            'category'        => $data['category'],
            'content'         => $data['content'],
            'tag'             => $data['tag'] ?? '',
            'approval_status' => 'pending',
            'status'          => 'inactive',
            'updated_at'      => now(),
        ];

        if ((string) $story->title !== $data['title']) {
            $update['slug'] = $this->uniqueSlug($data['title'], (int) $story->id);
        }

        if ($request->hasFile('image')) {
            $this->deleteThumbnail($story->thumbnail_path ?? '');
            $update['thumbnail_path'] = $this->storeThumbnail($request);
        }

        DB::table('stories')->where('id', $story->id)->update($update);

        return redirect()
            ->route('author.stories.index')
            ->with('success', 'Story updated and sent for admin approval again.');
    }

    private function findOwnStory($id): object
    {
        $story = DB::table('stories')
            ->where('id', $id)
            ->where('created_by', (int) auth()->id())
            ->where('category', '!=', 'Events')
            ->first();

        abort_unless($story, 404);

        return $story;
    }

    private function storyCategories(): array
    {
        $fromDb = DB::table('categories')
            ->where('status', 1)
            ->orderBy('category_name')
            ->pluck('category_name')
            ->all();

        if (! empty($fromDb)) {
            return $fromDb;
        }

        return [
            'Life skills and Youth',
            'Social change and Community Champions',
            'Mental Health & Wellbeing',
            'Volunteerism',
            'Social Welfare',
        ];
    }

    private function uniqueSlug(string $title, ?int $exceptId = null): string
    {
        $base = Str::slug($title);
        if ($base === '') {
            $base = 'story';
        }

        $slug = $base;
        $counter = 1;

        while (true) {
            $query = DB::table('stories')->where('slug', $slug);
            if ($exceptId) {
                $query->where('id', '!=', $exceptId);
            }
            if (! $query->exists()) {
                return $slug;
            }
            $slug = $base . '-' . $counter;
            $counter++;
        }
    }

    private function storeThumbnail(Request $request): string
    {
        if (! $request->hasFile('image')) {
            return '';
        }

        $dir = public_path('uploads/stories');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file = $request->file('image');
        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);

        return 'uploads/stories/' . $filename;
    }

    private function deleteThumbnail(string $path): void
    {
        if ($path === '' || Str::startsWith($path, ['http://', 'https://'])) {
            return;
        }

        $full = public_path(ltrim($path, '/'));
        if (is_file($full)) {
            unlink($full);
        }
    }
}
