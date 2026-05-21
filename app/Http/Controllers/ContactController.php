<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:120',
            'email'   => 'required|email|max:150',
            'phone'   => 'required|string|max:20',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:5000',
        ]);

        DB::table('contact_messages')->insert([
            'name'       => trim($data['name']),
            'email'      => trim($data['email']),
            'phone'      => trim($data['phone']),
            'subject'    => trim($data['subject']),
            'message'    => trim($data['message']),
            'status'     => 'new',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your message was sent successfully. We will get back to you soon.',
            ]);
        }

        return back()->with('contact_toast', [
            'type'    => 'success',
            'message' => 'Your message was sent successfully. We will get back to you soon.',
        ]);
    }
}
