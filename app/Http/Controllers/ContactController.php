<?php

namespace App\Http\Controllers;

use App\Services\ProgramApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function __construct(private ProgramApplicationService $applications)
    {
    }

    public function store(Request $request)
    {
        $pathwayInput = $request->input('pathway');
        $accountPathways = ['volunteer', 'partner'];

        $rules = [
            'name'    => 'required|string|max:120',
            'email'   => 'required|email|max:150',
            'phone'   => 'required|string|max:20',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:5000',
            'pathway' => 'nullable|string|in:volunteer,partner,intern,fellow',
        ];

        if (in_array($pathwayInput, $accountPathways, true)) {
            $rules['password'] = 'required|string|min:8|max:191|confirmed';
        }

        $data = $request->validate($rules);

        $pathway = isset($data['pathway']) && $data['pathway'] !== '' ? $data['pathway'] : null;

        DB::transaction(function () use ($pathway, $accountPathways, $data) {
            if (in_array($pathway, $accountPathways, true)) {
                $this->applications->createPathwayAccount(
                    $pathway,
                    $data['name'],
                    $data['email'],
                    $data['password'],
                    $data['phone'],
                    $data['subject'],
                    $data['message']
                );
            }

            DB::table('contact_messages')->insert([
                'name'       => trim($data['name']),
                'email'      => trim($data['email']),
                'phone'      => trim($data['phone']),
                'subject'    => trim($data['subject']),
                'pathway'    => $pathway,
                'message'    => trim($data['message']),
                'status'     => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        $successMessage = in_array($pathway, $accountPathways, true)
            ? 'Your registration was submitted. The team will review your application and activate your account so you can sign in.'
            : 'Your message was sent successfully. We will get back to you soon.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        $toast = [
            'type'    => 'success',
            'message' => $successMessage,
        ];

        if (in_array($pathway, $accountPathways, true)) {
            return redirect()
                ->route('contact', ['pathway' => $pathway])
                ->withFragment('contact-form')
                ->with('contact_toast', $toast);
        }

        return back()->with('contact_toast', $toast);
    }
}
