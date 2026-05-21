<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $status = $request->query('status');
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

        if ($dateFrom !== null && $dateFrom !== '') {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo !== null && $dateTo !== '') {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $messages = $query
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'new'     => (int) DB::table('contact_messages')->where('status', 'new')->count(),
            'read'    => (int) DB::table('contact_messages')->where('status', 'read')->count(),
            'replied' => (int) DB::table('contact_messages')->where('status', 'replied')->count(),
            'total'   => (int) DB::table('contact_messages')->count(),
        ];

        return view('admin.contact-messages.index', [
            'messages' => $messages,
            'stats'    => $stats,
            'filters'  => [
                'q'         => $q,
                'status'    => $status,
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

    public function markRead($id)
    {
        $this->updateStatus($id, 'read', 'Message marked as read.');

        return redirect()->back();
    }

    public function markReplied($id)
    {
        $this->updateStatus($id, 'replied', 'Message marked as replied.');

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
