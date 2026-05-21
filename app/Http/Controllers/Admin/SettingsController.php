<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function edit()
    {
        $site = DB::table('site')->where('id', 1)->first();
        $social = DB::table('social_links')->where('id', 1)->first();

        return view('admin.settings.edit', compact('site', 'social'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'footer'      => 'nullable|string|max:255',
            'postdisplay' => 'required|integer|between:1,100',
            'logo'        => 'nullable|image|max:4096',
        ]);

        $site = DB::table('site')->where('id', 1)->first();
        $logoName = $site->logo ?? '';

        if ($request->hasFile('logo')) {
            if ($logoName) {
                $old = public_path('uploads/site/' . $logoName);
                if (is_file($old)) {
                    @unlink($old);
                }
            }
            $file = $request->file('logo');
            $logoName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/site'), $logoName);
        }

        DB::table('site')->updateOrInsert(['id' => 1], [
            'title'       => $data['title'],
            'footer'      => $data['footer'] ?? '',
            'postdisplay' => (int) $data['postdisplay'],
            'logo'        => $logoName,
        ]);

        return redirect()->route('admin.settings.edit')->with('success', 'General settings saved');
    }

    public function updateSocial(Request $request)
    {
        $data = $request->validate([
            'facebook'   => 'nullable|url|max:255',
            'twitter'    => 'nullable|url|max:255',
            'instagram'  => 'nullable|url|max:255',
            'linkedin'   => 'nullable|url|max:255',
            'github'     => 'nullable|url|max:255',
            'footerlink' => 'nullable|url|max:255',
            'footertxt'  => 'nullable|string|max:255',
        ]);

        DB::table('social_links')->updateOrInsert(['id' => 1], [
            'facebook'   => $data['facebook']   ?? '',
            'twitter'    => $data['twitter']    ?? '',
            'instagram'  => $data['instagram']  ?? '',
            'linkedin'   => $data['linkedin']   ?? '',
            'github'     => $data['github']     ?? '',
            'footerlink' => $data['footerlink'] ?? '',
            'footertxt'  => $data['footertxt']  ?? '',
        ]);

        return redirect()->route('admin.settings.edit')->with('success', 'Social links saved');
    }
}
