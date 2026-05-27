<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesUploadedMedia;
use App\Http\Controllers\Concerns\TogglesRecordStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{
    use HandlesUploadedMedia;
    use TogglesRecordStatus;

    public function index(Request $request)
    {
        $q      = trim((string) $request->query('q', ''));
        $status = $request->query('status', '');

        $query = DB::table('programs');

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                  ->orWhere('tag', 'like', "%{$q}%");
            });
        }

        if ($status === '1' || $status === '0') {
            $query->where('status', (int) $status);
        }

        $programs = $query->orderBy('sort_order')->orderBy('id')->get();

        return view('admin.programs.index', compact('programs', 'q', 'status'));
    }

    public function create()
    {
        return view('admin.programs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|max:150',
            'tag'        => 'nullable|max:150',
            'short_desc' => 'required',
            'full_desc'  => 'nullable',
            'image'      => 'nullable|image',
            'card_style' => 'required|in:featured,default,teal,ochre,leaf',
            'sort_order' => 'nullable|integer|min:0',
            'status'     => 'required|in:0,1',
        ]);

        $image = '';
        if ($request->hasFile('image')) {
            $image = $this->storeUploadedFile('program', $request->file('image'));
        }

        DB::table('programs')->insert([
            'title'      => $request->title,
            'tag'        => $request->tag ?? '',
            'short_desc' => $request->short_desc,
            'full_desc'  => $request->full_desc ?? '',
            'image'      => $image,
            'card_style' => $request->card_style,
            'sort_order' => (int) ($request->sort_order ?? 0),
            'status'     => (int) $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Cache::forget('home.programs');

        return redirect()->route('admin.programs.index')->with('success', 'Program created successfully.');
    }

    public function edit($id)
    {
        $program = DB::table('programs')->where('id', $id)->first();
        if (!$program) {
            abort(404);
        }

        return view('admin.programs.edit', compact('program'));
    }

    public function update(Request $request, $id)
    {
        $program = DB::table('programs')->where('id', $id)->first();
        if (!$program) {
            abort(404);
        }

        $request->validate([
            'title'      => 'required|max:150',
            'tag'        => 'nullable|max:150',
            'short_desc' => 'required',
            'full_desc'  => 'nullable',
            'image'      => 'nullable|image',
            'card_style' => 'required|in:featured,default,teal,ochre,leaf',
            'sort_order' => 'nullable|integer|min:0',
            'status'     => 'required|in:0,1',
        ]);

        $image = (string) ($program->image ?? '');
        if ($request->hasFile('image')) {
            $image = $this->replaceUploadedFile('program', $request->file('image'), $image);
        }

        DB::table('programs')->where('id', $id)->update([
            'title'      => $request->title,
            'tag'        => $request->tag ?? '',
            'short_desc' => $request->short_desc,
            'full_desc'  => $request->full_desc ?? '',
            'image'      => $image,
            'card_style' => $request->card_style,
            'sort_order' => (int) ($request->sort_order ?? 0),
            'status'     => (int) $request->status,
            'updated_at' => now(),
        ]);

        Cache::forget('home.programs');

        return redirect()->route('admin.programs.index')->with('success', 'Program updated successfully.');
    }

    public function toggleStatus($id)
    {
        $response = $this->toggleRecordStatus('programs', $id, 'status', ['updated_at' => now()], 'Program status updated.');
        Cache::forget('home.programs');

        return $response;
    }

    public function destroy($id)
    {
        $program = DB::table('programs')->where('id', $id)->first();
        if (!$program) {
            abort(404);
        }

        $this->deleteUploadedFile('program', (string) ($program->image ?? ''));
        DB::table('programs')->where('id', $id)->delete();
        Cache::forget('home.programs');

        return redirect()->route('admin.programs.index')->with('success', 'Program deleted.');
    }
}
