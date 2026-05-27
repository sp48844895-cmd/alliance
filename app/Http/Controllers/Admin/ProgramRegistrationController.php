<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProgramRegistrationController extends Controller
{
    public static function typeLabels(): array
    {
        return [
            'intern'    => 'Intern',
            'fellow'    => 'Fellowship',
            'partner'   => 'Organisation partnership',
            'volunteer' => 'Volunteer registration',
        ];
    }

    public static function statusOptions(): array
    {
        return ['new', 'reviewed', 'accepted', 'rejected'];
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $type = $request->query('type');
        $status = $request->query('status');

        $query = DB::table('program_registrations')
            ->leftJoin('users', 'users.id', '=', 'program_registrations.user_id')
            ->select(
                'program_registrations.*',
                'users.is_active as user_is_active'
            );

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('program_registrations.full_name', 'like', '%' . $q . '%')
                    ->orWhere('program_registrations.email', 'like', '%' . $q . '%')
                    ->orWhere('program_registrations.phone', 'like', '%' . $q . '%')
                    ->orWhere('program_registrations.institution', 'like', '%' . $q . '%');
            });
        }

        if ($type !== null && $type !== '' && isset(self::typeLabels()[$type])) {
            $query->where('program_registrations.type', $type);
        }

        if ($status !== null && $status !== '' && in_array($status, self::statusOptions(), true)) {
            $query->where('program_registrations.status', $status);
        }

        $registrations = $query
            ->orderByDesc('program_registrations.created_at')
            ->orderByDesc('program_registrations.id')
            ->get();

        $stats = [
            'total'     => (int) DB::table('program_registrations')->count(),
            'new'       => (int) DB::table('program_registrations')->where('status', 'new')->count(),
            'intern'    => (int) DB::table('program_registrations')->where('type', 'intern')->count(),
            'fellow'    => (int) DB::table('program_registrations')->where('type', 'fellow')->count(),
            'partner'   => (int) DB::table('program_registrations')->where('type', 'partner')->count(),
            'volunteer' => (int) DB::table('program_registrations')->where('type', 'volunteer')->count(),
        ];

        return view('admin.registrations.index', [
            'registrations' => $registrations,
            'stats'         => $stats,
            'typeLabels'    => self::typeLabels(),
            'filters'       => [
                'q'      => $q,
                'type'   => $type,
                'status' => $status,
            ],
        ]);
    }

    public function show($id)
    {
        $registration = DB::table('program_registrations')->where('id', $id)->first();
        abort_unless($registration, 404);

        if ($registration->status === 'new') {
            DB::table('program_registrations')->where('id', $id)->update([
                'status'     => 'reviewed',
                'updated_at' => now(),
            ]);
            $registration->status = 'reviewed';
        }

        $user = null;
        if ($registration->user_id) {
            $user = DB::table('users')
                ->select('id', 'fname', 'lname', 'email', 'username', 'type', 'is_active')
                ->where('id', $registration->user_id)
                ->whereNull('deleted_at')
                ->first();
        }

        $domains = [];
        if (! empty($registration->domain_areas)) {
            $decoded = json_decode($registration->domain_areas, true);
            if (is_array($decoded)) {
                $domains = $decoded;
            }
        }

        return view('admin.registrations.show', [
            'registration' => $registration,
            'user'         => $user,
            'typeLabels'   => self::typeLabels(),
            'domains'      => $domains,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $registration = DB::table('program_registrations')->where('id', $id)->first();
        abort_unless($registration, 404);

        $data = $request->validate([
            'status' => 'required|in:new,reviewed,accepted,rejected',
        ]);

        DB::table('program_registrations')->where('id', $id)->update([
            'status'     => $data['status'],
            'updated_at' => now(),
        ]);

        if ($registration->user_id) {
            $isActive = $data['status'] === 'accepted';
            DB::table('users')->where('id', $registration->user_id)->update([
                'is_active'  => $isActive,
                'updated_at' => now(),
            ]);
        }

        if ($data['status'] === 'accepted' && in_array($registration->type, ['partner', 'volunteer'], true)) {
            $this->createMembershipFromRegistration($registration);
        }

        return redirect()
            ->route('admin.registrations.show', $id)
            ->with('success', 'Application status updated.');
    }

    public function destroy($id)
    {
        $deleted = DB::table('program_registrations')->where('id', $id)->delete();
        abort_unless($deleted, 404);

        return redirect()
            ->route('admin.registrations.index')
            ->with('success', 'Application deleted.');
    }

    private function createMembershipFromRegistration(object $registration): void
    {
        $email = strtolower(trim((string) $registration->email));

        if ($email === '' || DB::table('membership')->where('email', $email)->exists()) {
            return;
        }

        $membershipType = $registration->type === 'partner' ? 'CSO/NGO' : 'Volunteer';

        DB::table('membership')->insert([
            'name'             => $registration->full_name,
            'mobile'           => $registration->phone ?? '',
            'email'            => $registration->email,
            'type'             => $membershipType,
            'district'         => '',
            'block'            => '',
            'address'          => '',
            'area'             => '',
            'fb'               => '',
            'insta'            => '',
            'twitter'          => '',
            'youtube'          => '',
            'website'          => '',
            'ngo_organization' => $registration->type === 'partner' ? $registration->full_name : '',
            'org_intro'        => $registration->motivation ?? '',
            'img'              => '',
            'code'             => $this->generateMemberCode(),
            'date'             => now(),
        ]);

        Cache::forget('membership.filters');
    }

    private function generateMemberCode(): string
    {
        do {
            $code = 'ABC-'.random_int(1000, 9999);
            $exists = DB::table('membership')->where('code', $code)->exists();
        } while ($exists);

        return $code;
    }
}
