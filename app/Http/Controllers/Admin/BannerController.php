<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesUploadedMedia;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    use HandlesUploadedMedia;

    public function index()
    {
        $banners = DB::table('banner')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'dbannerimg' => 'required|image|max:4096',
            'mbannerimg' => 'required|image|max:4096',
            'ytlink'     => 'nullable|string|max:500',
            'redirect'   => 'required|string|max:500',
        ]);

        $desktopName = '';
        if ($request->hasFile('dbannerimg')) {
            $desktopName = $this->storeUploadedFile('banner', $request->file('dbannerimg'));
        }

        $mobileName = '';
        if ($request->hasFile('mbannerimg')) {
            $mobileName = $this->storeUploadedFile('banner', $request->file('mbannerimg'));
        }

        DB::table('banner')->insert([
            'dbannerimg' => $desktopName,
            'mbannerimg' => $mobileName,
            'ytlink'     => $data['ytlink'] ?? null,
            'redirect'   => $data['redirect'],
            'created_at' => now()->toDateString(),
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner saved');
    }

    public function edit($id)
    {
        $banner = DB::table('banner')->where('id', $id)->first();
        if (!$banner) {
            abort(404);
        }

        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = DB::table('banner')->where('id', $id)->first();
        if (!$banner) {
            abort(404);
        }

        $data = $request->validate([
            'dbannerimg' => 'nullable|image|max:4096',
            'mbannerimg' => 'nullable|image|max:4096',
            'ytlink'     => 'nullable|string|max:500',
            'redirect'   => 'required|string|max:500',
        ]);

        $desktopName = $banner->dbannerimg;
        if ($request->hasFile('dbannerimg')) {
            $desktopName = $this->replaceUploadedFile('banner', $request->file('dbannerimg'), $desktopName);
        }

        $mobileName = $banner->mbannerimg;
        if ($request->hasFile('mbannerimg')) {
            $mobileName = $this->replaceUploadedFile('banner', $request->file('mbannerimg'), $mobileName);
        }

        DB::table('banner')->where('id', $id)->update([
            'dbannerimg' => $desktopName,
            'mbannerimg' => $mobileName,
            'ytlink'     => $data['ytlink'] ?? null,
            'redirect'   => $data['redirect'],
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner saved');
    }

    public function destroy($id)
    {
        $banner = DB::table('banner')->where('id', $id)->first();
        if (!$banner) {
            abort(404);
        }

        $this->deleteUploadedFile('banner', $banner->dbannerimg);
        $this->deleteUploadedFile('banner', $banner->mbannerimg);

        DB::table('banner')->where('id', $id)->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted');
    }
}
