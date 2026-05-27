<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesUploadedMedia;
use App\Services\MembershipPageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MembershipController extends Controller
{
    use HandlesUploadedMedia;

    public function __construct(
        private MembershipPageService $membershipPageService
    ) {}

    public function index(Request $request)
    {
        $q           = trim((string) $request->query('q', ''));
        $type        = (string) $request->query('type', '');
        $district_id = (string) $request->query('district_id', '');
        $block_id    = (string) $request->query('block_id', '');

        $query = DB::table('membership');

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('mobile', 'like', "%{$q}%");
            });
        }

        $validTypes = ['Individual', 'CSO/NGO', 'Volunteer', 'Firm/Organization', 'individual'];
        if (in_array($type, $validTypes, true)) {
            $query->where('type', $type);
        }

        if ($district_id !== '') {
            $query->where('district', $district_id);
        }

        if ($block_id !== '') {
            $query->where('block', $block_id);
        }

        $members = $query->orderBy('id', 'desc')->get();
        $members->transform(function ($row) {
            $row->image_url = $this->membershipPageService->resolveImageUrl(
                (string) $row->name,
                (string) ($row->img ?? '')
            );

            return $row;
        });

        $districts = DB::table('district')->where('status', 1)
            ->orderBy('district_name')->get();

        $blocksQuery = DB::table('block')
            ->leftJoin('district', 'block.district_id', '=', 'district.id')
            ->select('block.id', 'block.block_name', 'block.district_id', 'district.district_name')
            ->where('block.status', 1);
        if ($district_id !== '') {
            $blocksQuery->where('block.district_id', (int) $district_id);
        }
        $blocks = $blocksQuery->orderBy('district.district_name')
            ->orderBy('block.block_name')->get();

        $districtMap = DB::table('district')->pluck('district_name', 'id');

        return view('admin.memberships.index', compact(
            'members', 'q', 'type', 'district_id', 'block_id',
            'districts', 'blocks', 'districtMap'
        ));
    }

    public function show($id)
    {
        $member = DB::table('membership')->where('id', $id)->first();
        if (!$member) {
            abort(404);
        }

        $districtName = DB::table('district')
            ->where('id', (int) $member->district)
            ->value('district_name');
        $blockName = DB::table('block')
            ->where('id', (int) $member->block)
            ->value('block_name');

        $areas = array_values(array_filter(array_map('trim', explode(',', (string) $member->area))));
        $memberImageUrl = $this->membershipPageService->resolveImageUrl(
            (string) $member->name,
            (string) ($member->img ?? '')
        );

        return view('admin.memberships.show', compact('member', 'districtName', 'blockName', 'areas', 'memberImageUrl'));
    }

    public function create()
    {
        $districts = DB::table('district')->where('status', 1)
            ->orderBy('district_name')->get();

        $blocks = DB::table('block')
            ->leftJoin('district', 'block.district_id', '=', 'district.id')
            ->select('block.id', 'block.block_name', 'block.district_id', 'district.district_name')
            ->where('block.status', 1)
            ->orderBy('district.district_name')
            ->orderBy('block.block_name')
            ->get();

        return view('admin.memberships.create', compact('districts', 'blocks'));
    }

    public function edit($id)
    {
        $member = DB::table('membership')->where('id', $id)->first();
        if (!$member) {
            abort(404);
        }

        $districts = DB::table('district')->where('status', 1)
            ->orderBy('district_name')->get();

        $blocks = DB::table('block')
            ->leftJoin('district', 'block.district_id', '=', 'district.id')
            ->select('block.id', 'block.block_name', 'block.district_id', 'district.district_name')
            ->where('block.status', 1)
            ->orderBy('district.district_name')
            ->orderBy('block.block_name')
            ->get();

        $memberImageUrl = $this->membershipPageService->resolveImageUrl(
            (string) $member->name,
            (string) ($member->img ?? '')
        );

        return view('admin.memberships.edit', compact('member', 'districts', 'blocks', 'memberImageUrl'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|max:255',
            'mobile'           => 'required|max:255',
            'email'            => 'required|email|max:255',
            'type'             => 'required|in:Individual,CSO/NGO,Volunteer,Firm/Organization',
            'district'         => 'required',
            'block'            => 'required',
            'address'          => 'nullable',
            'area'             => 'nullable',
            'fb'               => 'nullable|max:255',
            'insta'            => 'nullable|max:255',
            'twitter'          => 'nullable|max:255',
            'youtube'          => 'nullable|max:255',
            'website'          => 'nullable|max:255',
            'ngo_organization' => 'nullable|max:255',
            'org_intro'        => 'nullable',
            'img'              => 'nullable|image',
        ]);

        $filename = '';
        if ($request->hasFile('img')) {
            $basename = strtoupper(trim($request->name)).'_'.time();
            $filename = $this->storeUploadedFile('membership', $request->file('img'), $basename);
        }

        $id = DB::table('membership')->insertGetId([
            'name'             => $request->name,
            'mobile'           => $request->mobile,
            'email'            => $request->email,
            'type'             => $request->type,
            'district'         => (string) $request->district,
            'block'            => (string) $request->block,
            'address'          => $request->address ?? '',
            'area'             => $request->area ?? '',
            'fb'               => $request->fb ?? '',
            'insta'            => $request->insta ?? '',
            'twitter'          => $request->twitter ?? '',
            'youtube'          => $request->youtube ?? '',
            'website'          => $request->website ?? '',
            'ngo_organization' => $request->ngo_organization ?? '',
            'org_intro'        => $request->org_intro ?? '',
            'img'              => $filename,
            'code'             => $this->generateMemberCode(),
            'date'             => now(),
        ]);

        return redirect()->route('admin.memberships.show', $id)
            ->with('success', 'Membership created.');
    }

    public function update(Request $request, $id)
    {
        $member = DB::table('membership')->where('id', $id)->first();
        if (!$member) {
            abort(404);
        }

        $request->validate([
            'name'             => 'required|max:255',
            'mobile'           => 'required|max:255',
            'email'            => 'required|email|max:255',
            'type'             => 'required|in:Individual,CSO/NGO,Volunteer,Firm/Organization',
            'district'         => 'required',
            'block'            => 'required',
            'address'          => 'nullable',
            'area'             => 'nullable',
            'fb'               => 'nullable|max:255',
            'insta'            => 'nullable|max:255',
            'twitter'          => 'nullable|max:255',
            'youtube'          => 'nullable|max:255',
            'website'          => 'nullable|max:255',
            'ngo_organization' => 'nullable|max:255',
            'org_intro'        => 'nullable',
            'img'              => 'nullable|image',
        ]);

        $filename = $member->img;
        if ($request->hasFile('img')) {
            $basename = strtoupper(trim($request->name)).'_'.time();
            $filename = $this->replaceUploadedFile('membership', $request->file('img'), $member->img, $basename);
        }

        DB::table('membership')->where('id', $id)->update([
            'name'             => $request->name,
            'mobile'           => $request->mobile,
            'email'            => $request->email,
            'type'             => $request->type,
            'district'         => (string) $request->district,
            'block'            => (string) $request->block,
            'address'          => $request->address ?? '',
            'area'             => $request->area ?? '',
            'fb'               => $request->fb ?? '',
            'insta'            => $request->insta ?? '',
            'twitter'          => $request->twitter ?? '',
            'youtube'          => $request->youtube ?? '',
            'website'          => $request->website ?? '',
            'ngo_organization' => $request->ngo_organization ?? '',
            'org_intro'        => $request->org_intro ?? '',
            'img'              => $filename ?? '',
        ]);

        return redirect()->route('admin.memberships.show', $id)
            ->with('success', 'Membership updated.');
    }

    public function destroy($id)
    {
        $member = DB::table('membership')->where('id', $id)->first();
        if (!$member) {
            abort(404);
        }

        $this->deleteUploadedFile('membership', (string) $member->img);

        DB::table('membership')->where('id', $id)->delete();

        return redirect()->route('admin.memberships.index')
            ->with('success', 'Membership deleted.');
    }

    public function export(Request $request): StreamedResponse
    {
        $q           = trim((string) $request->query('q', ''));
        $type        = (string) $request->query('type', '');
        $district_id = (string) $request->query('district_id', '');
        $block_id    = (string) $request->query('block_id', '');

        $query = DB::table('membership');

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('mobile', 'like', "%{$q}%");
            });
        }

        $validTypes = ['Individual', 'CSO/NGO', 'Volunteer', 'Firm/Organization', 'individual'];
        if (in_array($type, $validTypes, true)) {
            $query->where('type', $type);
        }

        if ($district_id !== '') {
            $query->where('district', $district_id);
        }

        if ($block_id !== '') {
            $query->where('block', $block_id);
        }

        $rows = $query->orderBy('id', 'desc')->get();

        $districts = DB::table('district')->pluck('district_name', 'id');
        $blocks    = DB::table('block')->pluck('block_name', 'id');

        $filename = 'memberships-' . date('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($rows, $districts, $blocks) {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'ID', 'Name', 'Mobile', 'Email', 'Type', 'Code',
                'District', 'Block', 'Address', 'Areas', 'NGO/Org', 'Date',
            ]);

            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->id,
                    $r->name,
                    $r->mobile,
                    $r->email,
                    $r->type,
                    $r->code,
                    $districts[(int) $r->district] ?? '',
                    $blocks[(int) $r->block] ?? '',
                    $r->address,
                    $r->area,
                    $r->ngo_organization,
                    $r->date,
                ]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function generateMemberCode(): string
    {
        do {
            $code = 'ABC-'.random_int(1000, 9999);
            $exists = DB::table('membership')->where('code', $code)->exists();
        } while ($exists);

        return $code;
    }

}
