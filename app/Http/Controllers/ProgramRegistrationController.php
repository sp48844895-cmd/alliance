<?php

namespace App\Http\Controllers;

use App\Services\ProgramApplicationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProgramRegistrationController extends Controller
{
    public function __construct(private ProgramApplicationService $applications)
    {
    }

    public static function domainAreas(): array
    {
        return [
            'Communication',
            'Marketing Communication',
            'Policy',
            'Design',
            'Training & Engagement',
            'Content Writing',
            'Research',
        ];
    }

    public function internForm()
    {
        return view('pages.register.intern', [
            'domainAreas' => self::domainAreas(),
        ]);
    }

    public function internStore(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:120',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'university' => 'required|string|max:255',
            'class_year' => 'required|string|max:100',
            'domain_area' => ['required', 'string', Rule::in(self::domainAreas())],
            'motivation' => 'required|string|max:5000',
        ]);

        $this->applications->submitApplication('intern', $data);

        return redirect()
            ->route('register.intern')
            ->with('register_toast', [
                'type' => 'success',
                'title' => 'Application received',
                'message' => 'Thank you for applying. We will review your application within 3 working days. After approval, sign in at the intern login with the email and password you set here.',
            ]);
    }

    public function fellowForm()
    {
        return view('pages.register.fellow', [
            'domainAreas' => self::domainAreas(),
        ]);
    }

    public function fellowStore(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:120',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'university' => 'required|string|max:255',
            'class_year' => 'required|string|max:100',
            'domain_area' => ['required', 'string', Rule::in(self::domainAreas())],
            'motivation' => 'required|string|max:5000',
        ]);

        $this->applications->submitApplication('fellow', $data);

        return redirect()
            ->route('register.fellow')
            ->with('register_toast', [
                'type' => 'success',
                'title' => 'Application received',
                'message' => 'Thank you for applying. We will review your application within 3 working days. After approval, sign in at the fellowship login with the email and password you set here.',
            ]);
    }

    public function guestForm()
    {
        return view('pages.register.guest');
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

        $this->applications->createPathwayAccount(
            'guest',
            $data['full_name'],
            $data['email'],
            $data['password'],
            $data['phone'],
            'Guest registration — ChhattisgarhABC',
            $data['motivation']
        );

        return redirect()
            ->route('register.guest')
            ->with('register_toast', [
                'type' => 'success',
                'title' => 'Registration received',
                'message' => 'Thank you for registering. We will review your application and activate your guest login within a few working days.',
            ]);
    }
}
