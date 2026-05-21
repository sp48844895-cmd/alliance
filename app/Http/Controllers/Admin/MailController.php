<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MailController extends Controller
{
    public function index(Request $request)
    {
        $q      = trim((string) $request->query('q', ''));
        $status = $request->query('status');

        $query = DB::table('mails');

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', '%' . $q . '%')
                  ->orWhere('email', 'like', '%' . $q . '%')
                  ->orWhere('subject', 'like', '%' . $q . '%');
            });
        }

        if ($status !== null && $status !== '' && in_array($status, ['0', '1'], true)) {
            $query->where('status', (int) $status);
        }

        $mails = $query->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $unreadCount = DB::table('mails')->where('status', 0)->count();

        return view('admin.mails.index', [
            'mails'       => $mails,
            'unreadCount' => $unreadCount,
            'filters'     => [
                'q'      => $q,
                'status' => $status,
            ],
        ]);
    }

    public function show($id)
    {
        $mail = DB::table('mails')->where('id', $id)->first();
        if (!$mail) {
            abort(404);
        }

        if ((int) $mail->status === 0) {
            DB::table('mails')->where('id', $id)->update(['status' => 1]);
            $mail->status = 1;
        }

        $replies = DB::table('replies')
            ->where('email_id', $id)
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.mails.show', compact('mail', 'replies'));
    }

    public function toggleRead($id)
    {
        $mail = DB::table('mails')->where('id', $id)->first();
        if (!$mail) {
            abort(404);
        }

        DB::table('mails')->where('id', $id)->update([
            'status' => $mail->status ? 0 : 1,
        ]);

        return redirect()->back()->with('success', 'Mail status updated');
    }

    public function storeReply(Request $request, $id)
    {
        $mail = DB::table('mails')->where('id', $id)->first();
        if (!$mail) {
            abort(404);
        }

        $data = $request->validate([
            'reply' => 'required|max:5000',
        ]);

        $user = auth()->user();
        $userName = trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''));

        DB::table('replies')->insert([
            'email_id' => (int) $id,
            'user'     => $userName !== '' ? $userName : ($user->fname ?? 'Admin'),
            'reply'    => $data['reply'],
            'date'     => now(),
        ]);

        DB::table('mails')->where('id', $id)->update(['status' => 1]);

        return redirect()->route('admin.mails.show', $id)->with('success', 'Reply sent');
    }

    public function destroyReply($id, $replyId)
    {
        DB::table('replies')
            ->where('id', $replyId)
            ->where('email_id', $id)
            ->delete();

        return redirect()->route('admin.mails.show', $id)->with('success', 'Reply deleted');
    }

    public function destroy($id)
    {
        $mail = DB::table('mails')->where('id', $id)->first();
        if (!$mail) {
            abort(404);
        }

        DB::table('replies')->where('email_id', $id)->delete();
        DB::table('mails')->where('id', $id)->delete();

        return redirect()->route('admin.mails.index')->with('success', 'Mail deleted');
    }
}
