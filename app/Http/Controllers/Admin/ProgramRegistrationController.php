<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramRegistration;
use App\Services\MembershipPageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramRegistrationController extends Controller
{
    public function __construct(private MembershipPageService $membershipPageService) {}

    public static function typeLabels(): array
    {
        return ProgramRegistration::typeLabels();
    }

    public static function statusOptions(): array
    {
        return ProgramRegistration::statusOptions();
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
                $w->where('program_registrations.full_name', 'like', '%'.$q.'%')
                    ->orWhere('program_registrations.email', 'like', '%'.$q.'%')
                    ->orWhere('program_registrations.phone', 'like', '%'.$q.'%')
                    ->orWhere('program_registrations.institution', 'like', '%'.$q.'%');
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
            'total' => (int) DB::table('program_registrations')->count(),
            'pending' => (int) DB::table('program_registrations')->whereIn('status', ProgramRegistration::pendingStatuses())->count(),
            'approved' => (int) DB::table('program_registrations')->whereIn('status', ProgramRegistration::approvedStatuses())->count(),
            'intern' => (int) DB::table('program_registrations')->where('type', 'intern')->count(),
            'fellow' => (int) DB::table('program_registrations')->where('type', 'fellow')->count(),
            'partner' => (int) DB::table('program_registrations')->where('type', 'partner')->count(),
            'guest' => (int) DB::table('program_registrations')->where('type', 'guest')->count(),
        ];

        return view('admin.registrations.index', [
            'registrations' => $registrations,
            'stats' => $stats,
            'typeLabels' => self::typeLabels(),
            'filters' => [
                'q' => $q,
                'type' => $type,
                'status' => $status,
            ],
        ]);
    }

    public function show($id)
    {
        $registration = DB::table('program_registrations')->where('id', $id)->first();
        abort_unless($registration, 404);

        if (in_array($registration->status, ProgramRegistration::pendingStatuses(), true)) {
            DB::table('program_registrations')->where('id', $id)->update([
                'status' => ProgramRegistration::STATUS_REVIEWED,
                'updated_at' => now(),
            ]);
            $registration->status = ProgramRegistration::STATUS_REVIEWED;
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
            'user' => $user,
            'typeLabels' => self::typeLabels(),
            'domains' => $domains,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $registration = DB::table('program_registrations')->where('id', $id)->first();
        abort_unless($registration, 404);

        $data = $request->validate([
            'status' => 'required|in:'.implode(',', self::statusOptions()),
        ]);

        DB::table('program_registrations')->where('id', $id)->update([
            'status' => $data['status'],
            'updated_at' => now(),
        ]);

        if ($registration->user_id) {
            $isActive = in_array($data['status'], ProgramRegistration::approvedStatuses(), true);
            DB::table('users')->where('id', $registration->user_id)->update([
                'is_active' => $isActive,
                'updated_at' => now(),
            ]);
        }

        $this->membershipPageService->clearCache();

        return redirect()
            ->route('admin.registrations.show', $id)
            ->with('success', 'Application status updated.');
    }

    public function destroy($id)
    {
        $deleted = DB::table('program_registrations')->where('id', $id)->delete();
        abort_unless($deleted, 404);

        $this->membershipPageService->clearCache();

        return redirect()
            ->route('admin.registrations.index')
            ->with('success', 'Application deleted.');
    }
}
