<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesUploadedMedia;
use App\Http\Controllers\Concerns\TogglesRecordStatus;
use App\Http\Controllers\Controller;
use App\Services\EventPageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventController extends Controller
{
    use HandlesUploadedMedia;
    use TogglesRecordStatus;

    public function index(Request $request)
    {
        $q      = trim((string) $request->query('q', ''));
        $status = $request->query('status', '');

        $query = DB::table('event');

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('event_name', 'like', "%{$q}%")
                  ->orWhere('location', 'like', "%{$q}%");
            });
        }

        if ($status === '1' || $status === '0') {
            $query->where('event_status', (int) $status);
        }

        $events = $query->orderBy('id', 'desc')->get();

        return view('admin.events.index', compact('events', 'q', 'status'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_name'   => 'required|max:100',
            'start_date'   => 'required|date',
            'time'         => 'required|max:100',
            'end_date'     => 'required',
            'location'     => 'required|max:100',
            'googlemap'    => 'nullable',
            'description'  => 'required',
            'event_image'  => 'nullable|image',
            'event_status' => 'required|in:0,1',
        ]);

        $user = auth()->user();
        $admin = trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''));

        $endDate = $request->end_date;
        if (substr_count($endDate, ':') === 1) {
            $endDate .= ':00';
        }

        $filename = '';
        if ($request->hasFile('event_image')) {
            $filename = $this->storeUploadedFile('event', $request->file('event_image'));
        }

        DB::table('event')->insert([
            'event_name'   => $request->event_name,
            'date'         => date('d-m-y', strtotime($request->start_date)),
            'start_date'   => $request->start_date,
            'time'         => $request->time,
            'end_date'     => $endDate,
            'location'     => $request->location,
            'googlemap'    => $request->googlemap ?? '',
            'description'  => $request->description,
            'event_image'  => $filename,
            'event_status' => (int) $request->event_status,
            'admin'        => $admin !== '' ? $admin : 'Admin',
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    public function show($id)
    {
        $event = DB::table('event')->where('id', $id)->first();

        if (! $event) {
            abort(404);
        }

        $publicUrl = $event->event_status
            ? route('events.show', $event->id.'-'.Str::slug($event->event_name))
            : null;

        $imageUrl = app(EventPageService::class)->resolveImageUrl((string) ($event->event_image ?? ''));

        return view('admin.events.show', compact('event', 'publicUrl', 'imageUrl'));
    }

    public function edit($id)
    {
        $event = DB::table('event')->where('id', $id)->first();
        if (!$event) {
            abort(404);
        }

        $imageUrl = app(EventPageService::class)->resolveImageUrl((string) ($event->event_image ?? ''));

        return view('admin.events.edit', compact('event', 'imageUrl'));
    }

    public function update(Request $request, $id)
    {
        $event = DB::table('event')->where('id', $id)->first();
        if (!$event) {
            abort(404);
        }

        $request->validate([
            'event_name'   => 'required|max:100',
            'start_date'   => 'required|date',
            'time'         => 'required|max:100',
            'end_date'     => 'required',
            'location'     => 'required|max:100',
            'googlemap'    => 'nullable',
            'description'  => 'required',
            'event_image'  => 'nullable|image',
            'remove_image' => 'nullable|in:1',
            'event_status' => 'required|in:0,1',
        ]);

        $endDate = $request->end_date;
        if (substr_count($endDate, ':') === 1) {
            $endDate .= ':00';
        }

        $filename = $event->event_image;

        if ($request->boolean('remove_image')) {
            $this->deleteUploadedFile('event', $event->event_image);
            $filename = '';
        } elseif ($request->hasFile('event_image')) {
            $filename = $this->replaceUploadedFile('event', $request->file('event_image'), $event->event_image);
        }

        DB::table('event')->where('id', $id)->update([
            'event_name'   => $request->event_name,
            'date'         => date('d-m-y', strtotime($request->start_date)),
            'start_date'   => $request->start_date,
            'time'         => $request->time,
            'end_date'     => $endDate,
            'location'     => $request->location,
            'googlemap'    => $request->googlemap ?? '',
            'description'  => $request->description,
            'event_image'  => $filename,
            'event_status' => (int) $request->event_status,
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function toggleStatus($id)
    {
        return $this->toggleRecordStatus('event', $id, 'event_status', [], 'Event status updated.');
    }

    public function destroy($id)
    {
        $event = DB::table('event')->where('id', $id)->first();
        if (!$event) {
            abort(404);
        }

        $this->deleteUploadedFile('event', $event->event_image);

        DB::table('event')->where('id', $id)->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted.');
    }
}
