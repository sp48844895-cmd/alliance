<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesUploadedMedia;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    use HandlesUploadedMedia;

    public function index(Request $request)
    {
        $q    = trim((string) $request->query('q', ''));
        $type = $request->query('type');

        $query = DB::table('users')
            ->select('id', 'fname', 'lname', 'username', 'email', 'image', 'role', 'type', 'date', 'created_at');

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $like = '%' . $q . '%';
                $w->where('fname', 'like', $like)
                    ->orWhere('lname', 'like', $like)
                    ->orWhere('username', 'like', $like)
                    ->orWhere('email', 'like', $like);
            });
        }

        $allowedTypes = ['admin', 'guest', 'intern', 'fellow', 'ngo'];
        if ($type !== null && $type !== '' && in_array($type, $allowedTypes, true)) {
            $query->where('type', $type);
        }

        $users = $query->orderBy('id', 'desc')->get();

        $totalUsers   = (int) DB::table('users')->count();
        $totalAdmins  = (int) DB::table('users')->where('type', 'admin')->count();
        $totalFellows = (int) DB::table('users')->where('type', 'fellow')->count();
        $totalOther   = (int) DB::table('users')
            ->whereNotIn('type', ['admin', 'fellow'])
            ->count();

        return view('admin.users.index', [
            'users'        => $users,
            'filters'      => ['q' => $q, 'type' => $type],
            'totalUsers'   => $totalUsers,
            'totalAdmins'  => $totalAdmins,
            'totalFellows' => $totalFellows,
            'totalOther'   => $totalOther,
        ]);
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fname'    => 'required|string|max:100',
            'lname'    => 'nullable|string|max:50',
            'username' => 'required|string|max:50|unique:users,username',
            'email'    => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'type'     => 'required|in:admin,guest,intern,fellow,ngo',
            'role'     => 'required|in:1,2',
            'bio'      => 'nullable|string|max:2000',
            'image'    => 'nullable|image',
        ]);

        $imageName = '';
        if ($request->hasFile('image')) {
            $imageName = $this->storeUploadedFile('user', $request->file('image'));
        }

        DB::table('users')->insert([
            'fname'      => $data['fname'],
            'lname'      => $data['lname'] ?? '',
            'username'   => $data['username'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'image'      => $imageName,
            'bio'        => $data['bio'] ?? null,
            'role'       => (int) $data['role'],
            'type'       => $data['type'],
            'date'       => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created');
    }

    public function edit($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        if (!$user) {
            abort(404);
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        if (!$user) {
            abort(404);
        }

        $data = $request->validate([
            'fname'        => 'required|string|max:100',
            'lname'        => 'nullable|string|max:50',
            'username'     => 'required|string|max:50|unique:users,username,' . $id,
            'email'        => 'required|email|max:100|unique:users,email,' . $id,
            'password'     => 'nullable|string|min:8|confirmed',
            'type'         => 'required|in:admin,guest,intern,fellow,ngo',
            'role'         => 'required|in:1,2',
            'bio'          => 'nullable|string|max:2000',
            'image'        => 'nullable|image',
            'delete_image' => 'nullable|boolean',
        ]);

        $imageName = $user->image;

        if ($request->boolean('delete_image') && $imageName) {
            $this->deleteUploadedFile('user', $imageName);
            $imageName = '';
        }

        if ($request->hasFile('image')) {
            $imageName = $this->replaceUploadedFile('user', $request->file('image'), $imageName);
        }

        $update = [
            'fname'      => $data['fname'],
            'lname'      => $data['lname'] ?? '',
            'username'   => $data['username'],
            'email'      => $data['email'],
            'image'      => $imageName,
            'bio'        => $data['bio'] ?? null,
            'role'       => (int) $data['role'],
            'type'       => $data['type'],
            'updated_at' => now(),
        ];

        if (!empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        DB::table('users')->where('id', $id)->update($update);

        return redirect()->route('admin.users.index')->with('success', 'User updated');
    }

    public function destroy($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        if (!$user) {
            abort(404);
        }

        if ((int) auth()->id() === (int) $id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account.');
        }

        if ($user->type === 'admin') {
            $adminCount = (int) DB::table('users')->where('type', 'admin')->count();
            if ($adminCount <= 1) {
                return redirect()->route('admin.users.index')->with('error', 'Cannot delete the last admin.');
            }
        }

        DB::table('blog')->where('user_id', $id)->update(['user_id' => 1]);
        DB::table('learning_corner')->where('user_id', $id)->update(['user_id' => 1]);

        $this->deleteUploadedFile('user', $user->image);

        DB::table('users')->where('id', $id)->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted');
    }
}
