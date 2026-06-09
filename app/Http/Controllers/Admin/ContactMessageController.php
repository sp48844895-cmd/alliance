<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactMessageController extends Controller
{
    public static function pathwayLabels(): array
    {
        return [
            'guest' => 'Guest',
            'partner'   => 'NGO / Partner',
            'intern'    => 'Intern',
            'fellow'    => 'Fellowship',
        ];
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $status = $request->query('status');
        $pathway = $request->query('pathway');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $query = DB::table('contact_messages');

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%')
                    ->orWhere('phone', 'like', '%' . $q . '%')
                    ->orWhere('subject', 'like', '%' . $q . '%');
            });
        }

        if ($status !== null && $status !== '' && in_array($status, ['new', 'read', 'replied'], true)) {
            $query->where('status', $status);
        }

        if ($pathway !== null && $pathway !== '' && isset(self::pathwayLabels()[$pathway])) {
            $query->where('pathway', $pathway);
        }

        if ($dateFrom !== null && $dateFrom !== '') {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo !== null && $dateTo !== '') {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $messages = $query
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $stats = [
            'new'     => (int) DB::table('contact_messages')->where('status', 'new')->count(),
            'read'    => (int) DB::table('contact_messages')->where('status', 'read')->count(),
            'replied' => (int) DB::table('contact_messages')->where('status', 'replied')->count(),
            'total'   => (int) DB::table('contact_messages')->count(),
        ];

        return view('admin.contact-messages.index', [
            'messages' => $messages,
            'stats'    => $stats,
            'pathwayLabels' => self::pathwayLabels(),
            'filters'  => [
                'q'         => $q,
                'status'    => $status,
                'pathway'   => $pathway,
                'date_from' => $dateFrom,
                'date_to'   => $dateTo,
            ],
        ]);
    }

    public function show($id)
    {
        $message = DB::table('contact_messages')->where('id', $id)->first();
        abort_unless($message, 404);

        if ($message->status === 'new') {
            DB::table('contact_messages')->where('id', $id)->update([
                'status'     => 'read',
                'updated_at' => now(),
            ]);
            $message->status = 'read';
        }

        return view('admin.contact-messages.show', compact('message'));
    }

    public function markRead(Request $request, $id)
    {
        $this->updateStatus($id, 'read', 'Message marked as read.');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'Marked as read',
                'statusHtml' => '<span class="pill pill-mute">Read</span>',
                'rowClass' => false,
            ]);
        }

        return redirect()->back();
    }

    public function markReplied(Request $request, $id)
    {
        $this->updateStatus($id, 'replied', 'Message marked as replied.');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'Marked as replied',
                'statusHtml' => '<span class="pill pill-leaf">Replied</span>',
                'rowClass' => false,
            ]);
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        $deleted = DB::table('contact_messages')->where('id', $id)->delete();
        abort_unless($deleted, 404);

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('success', 'Contact message deleted.');
    }

    private function updateStatus($id, string $status, string $flash): void
    {
        $message = DB::table('contact_messages')->where('id', $id)->first();
        abort_unless($message, 404);

        DB::table('contact_messages')->where('id', $id)->update([
            'status'     => $status,
            'updated_at' => now(),
        ]);

        session()->flash('success', $flash);
    }
}
