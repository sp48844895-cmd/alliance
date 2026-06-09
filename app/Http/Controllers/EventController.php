<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Page;
use App\Models\PageSection;
use App\Services\EventPageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $metaTitle = 'Events · ChhattisgarhABC';
        $metaDescription = 'Upcoming and completed SBC events — workshops, trainings and webinars across Chhattisgarh.';
        $sections = [];

        $page = Page::where('route_name', 'events')->where('is_active', 1)->first();
        if ($page) {
            if ($page->meta_title) {
                $metaTitle = $page->meta_title;
            }
            if ($page->meta_description) {
                $metaDescription = $page->meta_description;
            }
            $sectionRows = PageSection::where('page_id', $page->id)->where('is_active', 1)->orderBy('sort_order')->get();
            foreach ($sectionRows as $row) {
                $content = json_decode((string) $row->content, true);
                $sections[$row->section_key] = is_array($content) ? $content : [];
            }
        }

        $rows = Event::where('event_status', 1)
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        $events = [];
        $upcomingCard = null;
        $pastCards = [];

        foreach ($rows as $event) {
            $slug = $event->id.'-'.Str::slug($event->event_name);
            $card = [
                'image' => $event->event_image ? asset('uploads/events/'.$event->event_image) : '',
                'title' => $event->event_name,
                'date' => $event->start_date,
                'date_label' => $event->start_date ? date('M d, Y', strtotime($event->start_date)) : '',
                'day' => $event->start_date ? date('d', strtotime($event->start_date)) : '',
                'month' => $event->start_date ? strtoupper(date('M', strtotime($event->start_date))) : '',
                'mode' => 'Offline',
                'tag' => 'Workshop · Event',
                'tag_type' => 'workshop',
                'url' => route('events.show', $slug),
                'link_text' => 'View event →',
            ];

            $events[] = $card;
            $isFuture = $event->start_date && strtotime($event->start_date) >= strtotime('today');
            if ($isFuture && $upcomingCard === null) {
                $upcomingCard = $card;
                $upcomingCard['link_text'] = 'Register →';
            }
            if (! $isFuture) {
                $pastCards[] = $card;
            }
        }

        $galleryTiles = [];
        foreach ($rows as $index => $event) {
            if (! $event->event_image) {
                continue;
            }
            $galleryTiles[] = [
                'class' => 'ev-gal-tile--'.(($index % 9) + 1),
                'index' => $index,
                'image' => asset('uploads/events/'.$event->event_image),
                'label' => $event->event_name.' · '.($event->start_date ? date('F Y', strtotime($event->start_date)) : ''),
            ];
        }

        $eventsGalleryTotal = count($galleryTiles);
        $latestEventMonth = $rows->first()->start_date ?? date('Y-m');
        if ($latestEventMonth && strlen($latestEventMonth) >= 7) {
            $latestEventMonth = substr($latestEventMonth, 0, 7);
        }

        $board = $sections['board'] ?? [
            'chapter' => '06',
            'title' => 'Events <em>dashboard</em>',
            'subtitle' => 'Browse upcoming and past events. Use the calendar to jump to a date.',
        ];
        $upcoming = $upcomingCard;
        $gallery = [
            'chapter' => $sections['gallery']['chapter'] ?? '11',
            'title' => $sections['gallery']['title'] ?? 'Event <em>gallery</em>',
            'subtitle' => $sections['gallery']['subtitle'] ?? 'Moments from workshops, trainings and convenings across Chhattisgarh.',
            'tiles' => $galleryTiles,
            'total' => $eventsGalleryTotal,
        ];
        $calendarMonthLabel = $latestEventMonth ? date('F Y', strtotime($latestEventMonth.'-01')) : date('F Y');

        return view('events.index', compact(
            'metaTitle',
            'metaDescription',
            'board',
            'upcoming',
            'pastCards',
            'gallery',
            'latestEventMonth',
            'calendarMonthLabel'
        ));
    }

    public function calendarData(Request $request)
    {
        $monthParam = $request->query('month');
        $date = $monthParam ? strtotime($monthParam.'-01') : time();
        if ($date === false) {
            $date = time();
        }

        $year = (int) date('Y', $date);
        $month = (int) date('m', $date);
        $daysInMonth = (int) date('t', $date);
        $firstDayOffset = ((int) date('w', strtotime("$year-$month-01")) + 6) % 7;

        $rows = Event::where('event_status', 1)->get();
        $events = [];
        foreach ($rows as $event) {
            if (! $event->start_date) {
                continue;
            }
            $eventMonth = substr($event->start_date, 0, 7);
            if ($eventMonth !== sprintf('%04d-%02d', $year, $month)) {
                continue;
            }
            $slug = $event->id.'-'.Str::slug($event->event_name);
            $events[] = [
                'date' => substr($event->start_date, 0, 10),
                'day' => (int) date('j', strtotime($event->start_date)),
                'title' => $event->event_name,
                'type' => 'workshop',
                'url' => route('events.show', $slug),
            ];
        }

        return response()->json([
            'year' => $year,
            'month' => $month,
            'label' => date('F Y', $date),
            'days_in_month' => $daysInMonth,
            'first_day_offset' => $firstDayOffset,
            'events' => $events,
            'today' => date('Y-m-d'),
        ]);
    }

    public function show(string $slug, EventPageService $eventPage)
    {
        $eventRecord = $eventPage->findPublishedBySlug($slug);

        abort_unless($eventRecord, 404);

        $event = $eventPage->formatForDetail($eventRecord);
        $relatedEvents = $eventPage->relatedEvents((int) $eventRecord->id);

        return view('events.show', compact('event', 'relatedEvents'));
    }
}
