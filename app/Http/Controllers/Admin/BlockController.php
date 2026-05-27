<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\TogglesRecordStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlockController extends Controller
{
    use TogglesRecordStatus;

    public function index(Request $request)
    {
        $q           = trim((string) $request->query('q', ''));
        $district_id = (string) $request->query('district_id', '');

        $query = DB::table('block')
            ->leftJoin('district', 'block.district_id', '=', 'district.id')
            ->select(
                'block.id', 'block.block_name', 'block.status', 'block.district_id',
                'district.district_name'
            );

        if ($q !== '') {
            $query->where('block.block_name', 'like', "%{$q}%");
        }

        if ($district_id !== '') {
            $query->where('block.district_id', (int) $district_id);
        }

        $blocks = $query
            ->orderBy('district.district_name')
            ->orderBy('block.block_name')
            ->get();

        $districts = DB::table('district')->orderBy('district_name')->get();

        return view('admin.blocks.index', compact('blocks', 'districts', 'q', 'district_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'district_id' => 'required|exists:district,id',
            'block_name'  => 'required|max:50',
            'status'      => 'required|in:0,1',
        ]);

        DB::table('block')->insert([
            'district_id' => (int) $request->district_id,
            'block_name'  => $request->block_name,
            'status'      => (int) $request->status,
        ]);

        return redirect()->route('admin.blocks.index')
            ->with('success', 'Block added.');
    }

    public function edit($id)
    {
        $block = DB::table('block')->where('id', $id)->first();
        if (!$block) {
            abort(404);
        }
        $districts = DB::table('district')->orderBy('district_name')->get();
        return view('admin.blocks.edit', compact('block', 'districts'));
    }

    public function update(Request $request, $id)
    {
        $block = DB::table('block')->where('id', $id)->first();
        if (!$block) {
            abort(404);
        }

        $request->validate([
            'district_id' => 'required|exists:district,id',
            'block_name'  => 'required|max:50',
            'status'      => 'required|in:0,1',
        ]);

        DB::table('block')->where('id', $id)->update([
            'district_id' => (int) $request->district_id,
            'block_name'  => $request->block_name,
            'status'      => (int) $request->status,
        ]);

        return redirect()->route('admin.blocks.index')
            ->with('success', 'Block updated.');
    }

    public function toggleStatus($id)
    {
        return $this->toggleRecordStatus('block', $id, 'status', [], 'Block status updated.');
    }

    public function destroy($id)
    {
        $block = DB::table('block')->where('id', $id)->first();
        if (!$block) {
            abort(404);
        }

        DB::table('block')->where('id', $id)->delete();

        return redirect()->route('admin.blocks.index')
            ->with('success', 'Block deleted.');
    }
}
