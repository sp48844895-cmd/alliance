<?php

namespace App\Http\Controllers\Preview;

use App\Http\Controllers\Controller;

use App\Models\ProgramRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RegistrationController extends Controller
{
    public function guestForm()
    {
        return view('preview::registrations.guest');
    }

    public function guestStore(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:120',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'motivation' => 'required|string|max:5000',
        ]);

        $email = strtolower(trim($data['email']));
        $existingUser = User::where('email', $email)->whereNull('deleted_at')->first();

        if ($existingUser) {
            if (($existingUser->type ?? '') !== 'guest') {
                throw ValidationException::withMessages(['email' => 'This email is already registered for another account type.']);
            }
            if ($existingUser->is_active ?? false) {
                throw ValidationException::withMessages(['email' => 'An account with this email already exists. Please sign in.']);
            }
            $parts = preg_split('/\s+/', trim($data['full_name']), 2) ?: [];
            $existingUser->fname = Str::limit($parts[0] ?? 'Applicant', 100, '');
            $existingUser->lname = Str::limit($parts[1] ?? '', 50, '');
            $existingUser->password = Hash::make($data['password']);
            $existingUser->is_active = false;
            $existingUser->save();
            $userId = $existingUser->id;
        } else {
            $parts = preg_split('/\s+/', trim($data['full_name']), 2) ?: [];
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
                'type' => 'guest',
                'is_active' => false,
                'date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        ProgramRegistration::insert([
            'user_id' => $userId,
            'type' => 'guest',
            'full_name' => trim($data['full_name']),
            'email' => $email,
            'phone' => trim($data['phone']),
            'motivation' => trim($data['motivation']),
            'status' => ProgramRegistration::STATUS_PENDING,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('preview.register.guest')->with('register_toast', [
            'type' => 'success',
            'title' => 'Registration received',
            'message' => 'Thank you for registering. We will review your application and activate your guest login within a few working days.',
        ]);
    }

    public function internForm()
    {
        $domainAreas = [
            'Communication',
            'Marketing Communication',
            'Policy',
            'Design',
            'Training & Engagement',
            'Content Writing',
            'Research',
        ];

        return view('preview::registrations.intern', compact('domainAreas'));
    }

    public function internStore(Request $request)
    {
        $domainAreas = [
            'Communication', 'Marketing Communication', 'Policy', 'Design',
            'Training & Engagement', 'Content Writing', 'Research',
        ];

        $data = $request->validate([
            'full_name' => 'required|string|max:120',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'university' => 'required|string|max:255',
            'class_year' => 'required|string|max:100',
            'domain_area' => ['required', 'string', Rule::in($domainAreas)],
            'motivation' => 'required|string|max:5000',
        ]);

        $this->saveApplication('intern', $data);

        return redirect()->route('preview.register.intern')->with('register_toast', [
            'type' => 'success',
            'title' => 'Application received',
            'message' => 'Thank you for applying. We will review your application within 3 working days.',
        ]);
    }

    public function fellowForm()
    {
        $domainAreas = [
            'Communication',
            'Marketing Communication',
            'Policy',
            'Design',
            'Training & Engagement',
            'Content Writing',
            'Research',
        ];

        return view('preview::registrations.fellow', compact('domainAreas'));
    }

    public function fellowStore(Request $request)
    {
        $domainAreas = [
            'Communication', 'Marketing Communication', 'Policy', 'Design',
            'Training & Engagement', 'Content Writing', 'Research',
        ];

        $data = $request->validate([
            'full_name' => 'required|string|max:120',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'university' => 'required|string|max:255',
            'class_year' => 'required|string|max:100',
            'domain_area' => ['required', 'string', Rule::in($domainAreas)],
            'motivation' => 'required|string|max:5000',
        ]);

        $this->saveApplication('fellow', $data);

        return redirect()->route('preview.register.fellow')->with('register_toast', [
            'type' => 'success',
            'title' => 'Application received',
            'message' => 'Thank you for applying. We will review your application within 3 working days.',
        ]);
    }

    private function saveApplication(string $type, array $data): void
    {
        $userType = $type === 'fellow' ? 'fellow' : 'intern';
        $email = strtolower(trim($data['email']));
        $existingUser = User::where('email', $email)->whereNull('deleted_at')->first();

        if ($existingUser) {
            if (($existingUser->type ?? '') !== $userType) {
                throw ValidationException::withMessages(['email' => 'This email is already registered for another account type.']);
            }
            if ($existingUser->is_active ?? false) {
                throw ValidationException::withMessages(['email' => 'An account with this email already exists. Please sign in.']);
            }
            $open = ProgramRegistration::where('user_id', $existingUser->id)->whereIn('status', [ProgramRegistration::STATUS_PENDING, ProgramRegistration::STATUS_REVIEWED])->exists();
            if ($open) {
                throw ValidationException::withMessages(['email' => 'An application with this email is already under review.']);
            }
            $parts = preg_split('/\s+/', trim($data['full_name']), 2) ?: [];
            $existingUser->fname = Str::limit($parts[0] ?? 'Applicant', 100, '');
            $existingUser->lname = Str::limit($parts[1] ?? '', 50, '');
            $existingUser->password = Hash::make($data['password']);
            $existingUser->is_active = false;
            $existingUser->save();
            $userId = $existingUser->id;
        } else {
            $parts = preg_split('/\s+/', trim($data['full_name']), 2) ?: [];
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
            'type' => $type,
            'full_name' => trim($data['full_name']),
            'email' => $email,
            'phone' => trim($data['phone']),
            'institution' => trim($data['university']),
            'class_year' => trim($data['class_year']),
            'domain_area' => $data['domain_area'],
            'motivation' => trim($data['motivation']),
            'status' => ProgramRegistration::STATUS_PENDING,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
