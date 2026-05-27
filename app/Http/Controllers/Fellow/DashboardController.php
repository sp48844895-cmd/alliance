<?php

namespace App\Http\Controllers\Fellow;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = (int) Auth::id();

        $registration = DB::table('program_registrations')
            ->where('user_id', $userId)
            ->where('type', 'fellow')
            ->orderByDesc('id')
            ->first();

        $domains = [];
        if ($registration && ! empty($registration->domain_areas)) {
            $decoded = json_decode($registration->domain_areas, true);
            if (is_array($decoded)) {
                $domains = $decoded;
            }
        }

        return view('fellow.dashboard', [
            'registration' => $registration,
            'domains' => $domains,
        ]);
    }
}
