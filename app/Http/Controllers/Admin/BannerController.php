<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesUploadedMedia;
use App\Http\Controllers\Concerns\TogglesRecordStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    use HandlesUploadedMedia;
    use TogglesRecordStatus;

    public function index(Request $request)
    {
        $status = $request->query('status', '');

        $query = DB::table('banner');

        if ($status === '1' || $status === '0') {
            $query->where('status', (int) $status);
        }

        $banners = $query
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return view('admin.banners.index', compact('banners', 'status'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $this->assertValidUpload($request, 'dbannerimg', true);
        $this->assertValidUpload($request, 'mbannerimg', true);
        $this->assertValidUpload($request, 'front_image', true);

        $data = $request->validate([
            'title' => 'required|max:150',
            'description' => 'required|string|max:2000',
            'small_title' => 'nullable|max:100',
            'dbannerimg' => 'required|file|image',
            'mbannerimg' => 'required|file|image',
            'front_image' => 'required|file|image',
            'url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1',
        ]);

        $desktopName = $this->storeUploadedFile('banner', $request->file('dbannerimg'), null, 'dbannerimg');
        $mobileName = $this->storeUploadedFile('banner', $request->file('mbannerimg'), null, 'mbannerimg');
        $frontName = $this->storeUploadedFile('banner', $request->file('front_image'), null, 'front_image');

        DB::table('banner')->insert([
            'title' => $data['title'],
            'description' => $data['description'],
            'small_title' => $data['small_title'] ?? '',
            'dbannerimg' => $desktopName,
            'mbannerimg' => $mobileName,
            'front_image' => $frontName,
            'url' => $data['url'] ?? '',
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'status' => (int) $data['status'],
            'redirect' => '#',
            'created_at' => now()->toDateString(),
        ]);

        Cache::forget('home.banners');

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }

    public function edit($id)
    {
        $banner = DB::table('banner')->where('id', $id)->first();
        if (! $banner) {
            abort(404);
        }

        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = DB::table('banner')->where('id', $id)->first();
        if (! $banner) {
            abort(404);
        }

        $this->assertValidUpload($request, 'dbannerimg');
        $this->assertValidUpload($request, 'mbannerimg');
        $this->assertValidUpload($request, 'front_image');

        $data = $request->validate([
            'title' => 'required|max:150',
            'description' => 'required|string|max:2000',
            'small_title' => 'nullable|max:100',
            'dbannerimg' => 'nullable|file|image',
            'mbannerimg' => 'nullable|file|image',
            'front_image' => 'nullable|file|image',
            'url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1',
        ]);

        $desktopName = (string) ($banner->dbannerimg ?? '');
        if ($request->hasFile('dbannerimg')) {
            $desktopName = $this->replaceUploadedFile('banner', $request->file('dbannerimg'), $desktopName, null, 'dbannerimg');
        }

        $mobileName = (string) ($banner->mbannerimg ?? '');
        if ($request->hasFile('mbannerimg')) {
            $mobileName = $this->replaceUploadedFile('banner', $request->file('mbannerimg'), $mobileName, null, 'mbannerimg');
        }

        $frontName = (string) ($banner->front_image ?? '');
        if ($request->hasFile('front_image')) {
            $frontName = $this->replaceUploadedFile('banner', $request->file('front_image'), $frontName, null, 'front_image');
        }

        DB::table('banner')->where('id', $id)->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'small_title' => $data['small_title'] ?? '',
            'dbannerimg' => $desktopName,
            'mbannerimg' => $mobileName,
            'front_image' => $frontName,
            'url' => $data['url'] ?? '',
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'status' => (int) $data['status'],
        ]);

        Cache::forget('home.banners');

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function toggleStatus($id)
    {
        $response = $this->toggleRecordStatus('banner', $id, 'status', [], 'Banner status updated.');
        Cache::forget('home.banners');

        return $response;
    }

    public function destroy($id)
    {
        $banner = DB::table('banner')->where('id', $id)->first();
        if (! $banner) {
            abort(404);
        }

        $this->deleteUploadedFile('banner', (string) ($banner->dbannerimg ?? ''));
        $this->deleteUploadedFile('banner', (string) ($banner->mbannerimg ?? ''));
        $this->deleteUploadedFile('banner', (string) ($banner->front_image ?? ''));

        DB::table('banner')->where('id', $id)->delete();

        Cache::forget('home.banners');

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted.');
    }
}
