<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Concerns\HandlesUploadedMedia;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use HandlesUploadedMedia;

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
            $this->deleteUploadedFile('user', $imageName);
            $imageName = '';
        }

        if ($request->hasFile('image')) {
            $imageName = $this->replaceUploadedFile('user', $request->file('image'), $imageName);
        }

        $update['image'] = $imageName;

        DB::table('users')->where('id', $userId)->update($update);

        return redirect()->route('author.profile.edit')->with('success', 'Profile updated.');
    }
}
