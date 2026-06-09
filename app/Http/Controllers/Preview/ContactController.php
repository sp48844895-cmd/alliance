<?php

namespace App\Http\Controllers\Preview;

use App\Http\Controllers\Controller;

use App\Models\ContactMessage;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\ProgramRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    public function index()
    {
        $metaTitle = 'Contact Us · ChhattisgarhABC';
        $metaDescription = 'Contact ChhattisgarhABC for partnerships, volunteering, resources and collaboration.';
        $sections = [];

        $page = Page::where('route_name', 'contact')->where('is_active', 1)->first();
        if ($page) {
            if ($page->meta_title) {
                $metaTitle = $page->meta_title;
            }
            if ($page->meta_description) {
                $metaDescription = $page->meta_description;
            }
            $rows = PageSection::where('page_id', $page->id)->where('is_active', 1)->orderBy('sort_order')->get();
            foreach ($rows as $row) {
                $content = json_decode((string) $row->content, true);
                $sections[$row->section_key] = is_array($content) ? $content : [];
            }
        }

        if (isset($sections['contact_cards']['cards']) && is_array($sections['contact_cards']['cards'])) {
            $sections['contact_cards']['cards'] = array_values(array_filter(
                $sections['contact_cards']['cards'],
                fn (array $card): bool => ($card['icon'] ?? '') !== 'phone'
            ));
        }

        $activePathway = old('pathway', request('pathway'));
        $requiresAccount = in_array($activePathway, ['guest', 'partner'], true);
        $defaultSubject = match ($activePathway) {
            'guest' => 'Guest registration — ChhattisgarhABC',
            'intern' => 'Intern application — ChhattisgarhABC',
            'fellow' => 'Fellowship application — ChhattisgarhABC',
            'partner' => 'Organisation partnership — ChhattisgarhABC',
            default => old('subject', ''),
        };

        return view('preview::contact.index', compact(
            'metaTitle',
            'metaDescription',
            'sections',
            'activePathway',
            'requiresAccount',
            'defaultSubject'
        ));
    }

    public function store(Request $request)
    {
        $pathwayInput = $request->input('pathway');
        $accountPathways = ['guest', 'partner'];

        $rules = [
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:5000',
            'pathway' => 'nullable|string|in:guest,partner,intern,fellow',
        ];

        if (in_array($pathwayInput, $accountPathways, true)) {
            $rules['password'] = 'required|string|min:8|max:191|confirmed';
        }

        $data = $request->validate($rules);
        $pathway = isset($data['pathway']) && $data['pathway'] !== '' ? $data['pathway'] : null;

        if (in_array($pathway, $accountPathways, true)) {
            $userType = $pathway === 'partner' ? 'ngo' : 'guest';
            $email = strtolower(trim($data['email']));
            $existingUser = User::where('email', $email)->whereNull('deleted_at')->first();

            if ($existingUser) {
                if (($existingUser->type ?? '') !== $userType) {
                    throw ValidationException::withMessages(['email' => 'This email is already registered for another account type.']);
                }
                if ($existingUser->is_active ?? false) {
                    throw ValidationException::withMessages(['email' => 'An account with this email already exists. Please sign in.']);
                }
                $parts = preg_split('/\s+/', trim($data['name']), 2) ?: [];
                $existingUser->fname = Str::limit($parts[0] ?? 'Applicant', 100, '');
                $existingUser->lname = Str::limit($parts[1] ?? '', 50, '');
                $existingUser->password = Hash::make($data['password']);
                $existingUser->is_active = false;
                $existingUser->save();
                $userId = $existingUser->id;
            } else {
                $parts = preg_split('/\s+/', trim($data['name']), 2) ?: [];
                $base = Str::slug(Str::before($email, '@'), '_') ?: 'applicant';
                $username = Str::limit($base, 40, '');
                $suffix = 1;
                while (User::where('username', $username)->exists()) {
                    $username = Str::limit($base, 35, '').'_'.$suffix;
                    $suffix++;
                }
                $userId = User::insertGetId([
                    'fname' => Str::limit($parts[0] ?? 'Applicant', 100, ''),
                    'lname' => Str::limit($parts[1] ?? '', 50, ''),
                    'username' => Str::limit($username, 50, ''),
                    'email' => $email,
                    'password' => Hash::make($data['password']),
                    'image' => '',
                    'role' => 2,
                    'type' => $userType,
                    'is_active' => false,
                    'date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            ProgramRegistration::insert([
                'user_id' => $userId,
                'type' => $pathway,
                'full_name' => trim($data['name']),
                'email' => $email,
                'phone' => trim($data['phone']),
                'institution' => trim($data['subject']),
                'motivation' => trim($data['message']),
                'status' => ProgramRegistration::STATUS_PENDING,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        ContactMessage::insert([
            'name' => trim($data['name']),
            'email' => trim($data['email']),
            'phone' => trim($data['phone']),
            'subject' => trim($data['subject']),
            'pathway' => $pathway,
            'message' => trim($data['message']),
            'status' => 'new',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $successMessage = in_array($pathway, $accountPathways, true)
            ? 'Your registration was submitted. The team will review your application and activate your account so you can sign in.'
            : 'Your message was sent successfully. We will get back to you soon.';

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $successMessage]);
        }

        $toast = ['type' => 'success', 'message' => $successMessage];

        if (in_array($pathway, $accountPathways, true)) {
            return redirect()->route('preview.contact', ['pathway' => $pathway])
                ->withFragment('contact-form')
                ->with('contact_toast', $toast);
        }

        return back()->with('contact_toast', $toast);
    }
}
