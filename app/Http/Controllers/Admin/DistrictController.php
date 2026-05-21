<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\TogglesRecordStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistrictController extends Controller
{
    use TogglesRecordStatus;

    public function index()
    {
        $districts = DB::table('district')
            ->select('district.id', 'district.district_name', 'district.status',
                DB::raw('(SELECT COUNT(*) FROM block WHERE block.district_id = district.id) AS blocks_count'))
            ->orderBy('district.district_name')
            ->paginate(33);

        return view('admin.districts.index', compact('districts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'district_name' => 'required|max:50|unique:district,district_name',
            'status'        => 'required|in:0,1',
        ]);

        DB::table('district')->insert([
            'district_name' => $request->district_name,
            'status'        => (int) $request->status,
        ]);

        return redirect()->route('admin.districts.index')
            ->with('success', 'District added.');
    }

    public function edit($id)
    {
        $district = DB::table('district')->where('id', $id)->first();
        if (!$district) {
            abort(404);
        }
        return view('admin.districts.edit', compact('district'));
    }

    public function update(Request $request, $id)
    {
        $district = DB::table('district')->where('id', $id)->first();
        if (!$district) {
            abort(404);
        }

        $request->validate([
            'district_name' => 'required|max:50|unique:district,district_name,' . $id,
            'status'        => 'required|in:0,1',
        ]);

        DB::table('district')->where('id', $id)->update([
            'district_name' => $request->district_name,
            'status'        => (int) $request->status,
        ]);

        return redirect()->route('admin.districts.index')
            ->with('success', 'District updated.');
    }

    public function toggleStatus($id)
    {
        return $this->toggleRecordStatus('district', $id, 'status', [], 'District status updated.');
    }

    public function destroy($id)
    {
        $district = DB::table('district')->where('id', $id)->first();
        if (!$district) {
            abort(404);
        }

        $blocks = DB::table('block')->where('district_id', $id)->count();
        if ($blocks > 0) {
            return back()->with('error',
                "Cannot delete: {$blocks} block(s) are still linked to this district.");
        }

        DB::table('district')->where('id', $id)->delete();

        return redirect()->route('admin.districts.index')
            ->with('success', 'District deleted.');
    }
}
