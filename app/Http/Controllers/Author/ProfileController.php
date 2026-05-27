<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = DB::table('users')->where('id', (int) auth()->id())->first();
        abort_unless($user, 404);

        return view('author.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $userId = (int) auth()->id();
        $user = DB::table('users')->where('id', $userId)->first();
        abort_unless($user, 404);

        $data = $request->validate([
            'fname'    => 'required|string|max:100',
            'lname'    => 'nullable|string|max:50',
            'username' => 'required|string|max:50|unique:users,username,' . $userId,
            'email'    => 'required|email|max:100|unique:users,email,' . $userId,
            'bio'      => 'nullable|string|max:1000',
            'password' => 'nullable|string|min:8|confirmed',
            'image'    => 'nullable|image',
            'delete_image' => 'nullable|in:1',
        ]);

        $update = [
            'fname'      => $data['fname'],
            'lname'      => $data['lname'] ?? '',
            'username'   => $data['username'],
            'email'      => $data['email'],
            'bio'        => $data['bio'] ?? '',
            'updated_at' => now(),
        ];

        if (! empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        $imageName = $user->image ?? '';
        if ($request->boolean('delete_image') && $imageName !== '') {
            $this->deleteUserImage($imageName);
            $imageName = '';
        }
        if ($request->hasFile('image')) {
            if ($imageName !== '') {
                $this->deleteUserImage($imageName);
            }
            $file = $request->file('image');
            $imageName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $dir = public_path('uploads/users');
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $file->move($dir, $imageName);
        }
        $update['image'] = $imageName;

        DB::table('users')->where('id', $userId)->update($update);

        return redirect()->route('author.profile.edit')->with('success', 'Profile updated.');
    }

    private function deleteUserImage(string $filename): void
    {
        $path = public_path('uploads/users/' . $filename);
        if (is_file($path)) {
            unlink($path);
        }
    }
}
