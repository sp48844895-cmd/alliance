<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesUploadedMedia;
use App\Http\Controllers\Concerns\TogglesRecordStatus;
use App\Http\Controllers\Controller;
use App\Support\MediaUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SbcPoolMemberController extends Controller
{
    use HandlesUploadedMedia;
    use TogglesRecordStatus;

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $query = DB::table('sbc_pool_members');

        if ($q !== '') {
            $query->where(function ($inner) use ($q) {
                $inner->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $members = $query
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.sbc-pool.index', compact('members', 'q'));
    }

    public function create()
    {
        return view('admin.sbc-pool.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'photo' => 'required|image|max:4096',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1',
            'facebook' => 'nullable|string|max:500',
            'twitter' => 'nullable|string|max:500',
            'linkedin' => 'nullable|string|max:500',
            'instagram' => 'nullable|string|max:500',
        ]);

        $photoName = '';
        if ($request->hasFile('photo')) {
            $photoName = $this->storePhoto($request->file('photo'), $data['name']);
        }

        DB::table('sbc_pool_members')->insert([
            'name' => $data['name'],
            'email' => $data['email'] ?: null,
            'photo' => $photoName !== '' ? $photoName : null,
            'facebook' => $this->nullableText($data['facebook'] ?? null),
            'twitter' => $this->nullableText($data['twitter'] ?? null),
            'linkedin' => $this->nullableText($data['linkedin'] ?? null),
            'instagram' => $this->nullableText($data['instagram'] ?? null),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'status' => (int) $data['status'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.sbc-pool.index')->with('success', 'SBC pool member added');
    }

    public function edit($id)
    {
        $member = DB::table('sbc_pool_members')->where('id', $id)->first();
        if (! $member) {
            abort(404);
        }

        return view('admin.sbc-pool.edit', compact('member'));
    }

    public function update(Request $request, $id)
    {
        $member = DB::table('sbc_pool_members')->where('id', $id)->first();
        if (! $member) {
            abort(404);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'photo' => 'nullable|image|max:4096',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1',
            'facebook' => 'nullable|string|max:500',
            'twitter' => 'nullable|string|max:500',
            'linkedin' => 'nullable|string|max:500',
            'instagram' => 'nullable|string|max:500',
        ]);

        $photoName = (string) ($member->photo ?? '');
        if ($request->hasFile('photo')) {
            $photoName = $this->storePhoto($request->file('photo'), $data['name']);
            $this->deletePhoto((string) ($member->photo ?? ''));
        }

        DB::table('sbc_pool_members')->where('id', $id)->update([
            'name' => $data['name'],
            'email' => $data['email'] ?: null,
            'photo' => $photoName !== '' ? $photoName : null,
            'facebook' => $this->nullableText($data['facebook'] ?? null),
            'twitter' => $this->nullableText($data['twitter'] ?? null),
            'linkedin' => $this->nullableText($data['linkedin'] ?? null),
            'instagram' => $this->nullableText($data['instagram'] ?? null),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'status' => (int) $data['status'],
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.sbc-pool.index')->with('success', 'SBC pool member updated');
    }

    public function toggleStatus($id)
    {
        return $this->toggleRecordStatus('sbc_pool_members', $id, 'status', ['updated_at' => now()], 'Member status updated');
    }

    public function destroy($id)
    {
        $member = DB::table('sbc_pool_members')->where('id', $id)->first();
        if (! $member) {
            abort(404);
        }

        DB::table('sbc_pool_members')->where('id', $id)->delete();
        $this->deletePhoto((string) ($member->photo ?? ''));

        return redirect()->route('admin.sbc-pool.index')->with('success', 'SBC pool member deleted');
    }

    private function storePhoto($file, string $name): string
    {
        $dir = MediaUrl::uploadPath('sbc-pool');

        $extension = strtolower((string) $file->getClientOriginalExtension());
        $base = trim((string) pathinfo((string) $file->getClientOriginalName(), PATHINFO_FILENAME));
        $base = preg_replace('/[^A-Za-z0-9\-\._ ]+/', '', $base) ?: '';

        if ($base === '' || $base === '.') {
            $base = Str::slug($name);
        }

        $filename = $base.'.'.$extension;
        $counter = 1;
        while (is_file($dir.'/'.$filename)) {
            $filename = $base.'-'.$counter.'.'.$extension;
            $counter++;
        }

        $file->move($dir, $filename);

        return $filename;
    }

    private function deletePhoto(string $filename): void
    {
        $this->deleteUploadedFile('sbc-pool', $filename);
    }

    private function nullableText(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
