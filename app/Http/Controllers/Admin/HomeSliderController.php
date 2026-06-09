<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesUploadedMedia;
use App\Http\Controllers\Concerns\TogglesRecordStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeSliderController extends Controller
{
    use HandlesUploadedMedia;
    use TogglesRecordStatus;

    public function index(Request $request)
    {
        $status = $request->query('status', '');

        $query = DB::table('home_slider_slides');

        if ($status === '1' || $status === '0') {
            $query->where('status', (int) $status);
        }

        $slides = $query->orderBy('sort_order')->orderBy('id')->get();

        return view('admin.home-slider.index', compact('slides', 'status'));
    }

    public function create()
    {
        return view('admin.home-slider.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:150',
            'short_description' => 'required',
            'url' => 'nullable|string|max:500',
            'image' => 'required|image',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1',
        ]);

        $image = $this->storeUploadedFile('home-slider', $request->file('image'));

        DB::table('home_slider_slides')->insert([
            'title' => $request->title,
            'short_description' => $request->short_description,
            'url' => $request->url ?? '',
            'image' => $image,
            'sort_order' => (int) ($request->sort_order ?? 0),
            'status' => (int) $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Cache::forget('home.slider-slides');

        return redirect()->route('admin.home-slider.index')->with('success', 'Slider slide created successfully.');
    }

    public function edit($id)
    {
        $slide = DB::table('home_slider_slides')->where('id', $id)->first();
        if (! $slide) {
            abort(404);
        }

        return view('admin.home-slider.edit', compact('slide'));
    }

    public function update(Request $request, $id)
    {
        $slide = DB::table('home_slider_slides')->where('id', $id)->first();
        if (! $slide) {
            abort(404);
        }

        $request->validate([
            'title' => 'required|max:150',
            'short_description' => 'required',
            'url' => 'nullable|string|max:500',
            'image' => 'nullable|image',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1',
        ]);

        $image = (string) ($slide->image ?? '');
        if ($request->hasFile('image')) {
            $image = $this->replaceUploadedFile('home-slider', $request->file('image'), $image);
        }

        DB::table('home_slider_slides')->where('id', $id)->update([
            'title' => $request->title,
            'short_description' => $request->short_description,
            'url' => $request->url ?? '',
            'image' => $image,
            'sort_order' => (int) ($request->sort_order ?? 0),
            'status' => (int) $request->status,
            'updated_at' => now(),
        ]);

        Cache::forget('home.slider-slides');

        return redirect()->route('admin.home-slider.index')->with('success', 'Slider slide updated successfully.');
    }

    public function toggleStatus($id)
    {
        $response = $this->toggleRecordStatus('home_slider_slides', $id, 'status', ['updated_at' => now()], 'Slide status updated.');
        Cache::forget('home.slider-slides');

        return $response;
    }

    public function destroy($id)
    {
        $slide = DB::table('home_slider_slides')->where('id', $id)->first();
        if (! $slide) {
            abort(404);
        }

        $this->deleteUploadedFile('home-slider', (string) ($slide->image ?? ''));
        DB::table('home_slider_slides')->where('id', $id)->delete();
        Cache::forget('home.slider-slides');

        return redirect()->route('admin.home-slider.index')->with('success', 'Slider slide deleted.');
    }
}
