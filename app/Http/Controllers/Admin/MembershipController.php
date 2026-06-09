<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramRegistration;
use App\Services\MembershipPageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MembershipController extends Controller
{
    public function __construct(private MembershipPageService $membershipPageService) {}

    public function index(Request $request)
    {
        return redirect()->route('admin.registrations.index', [
            'status' => ProgramRegistration::STATUS_APPROVED,
            'q' => $request->query('q'),
            'type' => $request->query('type'),
        ]);
    }

    public function show($id)
    {
        return redirect()->route('admin.registrations.show', $id);
    }

    public function export(Request $request): StreamedResponse
    {
        $q = trim((string) $request->query('q', ''));
        $type = (string) $request->query('type', '');

        $query = DB::table('program_registrations')
            ->leftJoin('district', 'district.id', '=', 'program_registrations.district_id')
            ->whereIn('program_registrations.status', ProgramRegistration::approvedStatuses())
            ->select([
                'program_registrations.id',
                'program_registrations.full_name',
                'program_registrations.email',
                'program_registrations.phone',
                'program_registrations.type',
                'program_registrations.institution',
                'program_registrations.created_at',
                'district.district_name',
            ]);

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('program_registrations.full_name', 'like', "%{$q}%")
                    ->orWhere('program_registrations.email', 'like', "%{$q}%")
                    ->orWhere('program_registrations.phone', 'like', "%{$q}%");
            });
        }

        if ($type !== '' && isset(ProgramRegistration::typeLabels()[$type])) {
            $query->where('program_registrations.type', $type);
        }

        $rows = $query->orderBy('program_registrations.full_name')->get();
        $filename = 'approved-members-'.date('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Name', 'Email', 'Phone', 'Type', 'Institution', 'District', 'Approved at']);

            foreach ($rows as $row) {
                fputcsv($out, [
                    $row->id,
                    $row->full_name,
                    $row->email,
                    $row->phone,
                    ProgramRegistration::publicTypeLabel((string) $row->type),
                    $row->institution,
                    $row->district_name,
                    $row->created_at,
                ]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
