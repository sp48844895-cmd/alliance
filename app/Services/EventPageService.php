<?php

namespace App\Services;

use App\Support\MediaUrl;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventPageService
{
    private ?Collection $publishedEventsCache = null;
    public function recentForHome(int $limit = 5): array
    {
        return $this->publishedEvents()
            ->take($limit)
            ->values()
            ->map(fn ($event, $index) => $this->formatHomeTile($event, $index))
            ->all();
    }

    public function relatedEvents(int $exceptId, int $limit = 3): array
    {
        return $this->publishedEvents()
            ->filter(fn ($event) => (int) $event->id !== $exceptId)
            ->take($limit)
            ->map(fn ($event) => $this->formatForDetail($event))
            ->values()
            ->all();
    }

    public function boardData(): array
    {
        $events = $this->orderedEvents();
        $today = Carbon::today();
        $timeline = [];
        $hasToday = false;

        foreach ($events as $event) {
            $date = $event->parsed_date;
            $status = $date->isFuture() ? 'upcoming' : ($date->isToday() ? 'today' : 'past');

            if ($status === 'today') {
                $hasToday = true;
            }

            $timeline[] = [
                'status' => $status === 'today' ? 'past' : $status,
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('d'),
                'month' => strtoupper($date->format('M')),
                'tag' => $this->eventTag($event),
                'tag_type' => $this->eventTagType($event),
                'title' => $event->event_name,
                'description' => Str::limit($event->summary, 220),
                'link' => route('events.show', $this->slugForRow($event)),
                'link_text' => $status === 'upcoming' ? 'Register →' : 'View event →',
                'featured' => false,
            ];
        }

        if ($hasToday) {
            $timeline[] = ['status' => 'today', 'label' => 'TODAY · '.strtoupper($today->format('M j'))];
        }

        $calendarMonth = $events->first()?->parsed_date ?? $today;
        $calendar = $this->buildCalendar($events, $calendarMonth);

        return [
            'timeline' => $timeline,
            'calendar' => $calendar,
        ];
    }

    public function upcomingCard(): ?array
    {
        $event = $this->publishedEvents()
            ->filter(fn ($event) => $event->parsed_date->isFuture() || $event->parsed_date->isToday())
            ->sortBy(fn ($event) => $event->parsed_date->timestamp)
            ->first();

        return $event ? $this->formatEventCard($event) : null;
    }

    public function pastCards(): array
    {
        return $this->publishedEvents()
            ->filter(fn ($event) => $event->parsed_date->isPast() && ! $event->parsed_date->isToday())
            ->sortByDesc(fn ($event) => $event->parsed_date->timestamp)
            ->values()
            ->map(fn ($event, $index) => array_merge(
                $this->formatEventCard($event),
                ['aos_delay' => $index > 0 ? (string) ($index * 100) : null]
            ))
            ->all();
    }

    private function formatEventCard(object $event): array
    {
        $date = $event->parsed_date;
        $tagType = $this->eventTagType($event);

        return [
            'image' => $this->imageUrl((string) $event->event_image),
            'title' => $event->event_name,
            'date' => $date->format('Y-m-d'),
            'date_label' => $date->format('M d, Y'),
            'day' => $date->format('d'),
            'month' => strtoupper($date->format('M')),
            'mode' => $this->eventMode($event),
            'tag' => $this->eventTag($event),
            'tag_type' => $tagType,
            'url' => route('events.show', $this->slugForRow($event)),
            'link_text' => ($date->isFuture() || $date->isToday()) ? 'Register →' : 'View event →',
        ];
    }

    private function eventMode(object $event): string
    {
        $haystack = strtolower(
            (string) ($event->googlemap ?? '').' '.
            (string) ($event->location ?? '').' '.
            (string) ($event->description ?? '')
        );

        $onlineMarkers = ['zoom', 'webinar', 'fb.me', 'meet.google', 'teams.microsoft', 'virtual', 'online', 'live stream', 'livestream'];

        foreach ($onlineMarkers as $marker) {
            if (str_contains($haystack, $marker)) {
                return 'Online';
            }
        }

        return 'Offline';
    }

    public function galleryTiles(): array
    {
        $tiles = [];
        $index = 0;

        foreach ($this->publishedEvents() as $event) {
            $image = $this->imageUrl((string) $event->event_image);

            if (str_contains($image, 'images/home/1.jpg')) {
                continue;
            }

            $tiles[] = [
                'class' => 'ev-gal-tile--'.(($index % 9) + 1),
                'index' => $index,
                'image' => $image,
                'label' => $event->event_name.' · '.$event->parsed_date->format('F Y'),
            ];
            $index++;
        }

        if ($tiles === []) {
            foreach ($this->publishedEvents()->take(9) as $event) {
                $tiles[] = [
                    'class' => 'ev-gal-tile--'.(($index % 9) + 1),
                    'index' => $index,
                    'image' => $this->imageUrl((string) $event->event_image),
                    'label' => $event->event_name.' · '.$event->parsed_date->format('F Y'),
                ];
                $index++;
            }
        }

        return $tiles;
    }

    public function findPublishedBySlug(string $slug): ?object
    {
        if (preg_match('/^(\d+)(?:-|$)/', $slug, $matches)) {
            $row = DB::table('event')
                ->where('id', (int) $matches[1])
                ->where('event_status', 1)
                ->first();

            if ($row && $this->slugForRow($this->hydrateEvent($row)) === $slug) {
                return $this->hydrateEvent($row);
            }
        }

        foreach ($this->publishedEvents() as $event) {
            if ($this->slugForRow($event) === $slug) {
                return $event;
            }
        }

        return null;
    }

    public function slugForRow(object $row): string
    {
        return $row->id.'-'.Str::slug($row->event_name);
    }

    public function calendarPayload(?string $monthParam): array
    {
        try {
            $date = $monthParam !== null && $monthParam !== ''
                ? Carbon::createFromFormat('Y-m', $monthParam)->startOfMonth()
                : Carbon::now()->startOfMonth();
        } catch (\Throwable) {
            $date = Carbon::now()->startOfMonth();
        }

        $year = $date->year;
        $month = $date->month;
        $end = $date->copy()->endOfMonth();
        $events = [];

        foreach ($this->publishedEvents() as $event) {
            $startDate = trim((string) ($event->start_date ?? ''));

            if ($startDate === '') {
                continue;
            }

            $parsed = $event->parsed_date;

            if ($parsed->year !== $year || $parsed->month !== $month) {
                continue;
            }

            $events[] = [
                'date' => $parsed->format('Y-m-d'),
                'day' => $parsed->day,
                'title' => $event->event_name,
                'type' => $this->eventTagType($event),
                'url' => route('events.show', $this->slugForRow($event)),
            ];
        }

        return [
            'year' => $year,
            'month' => $month,
            'label' => $date->format('F Y'),
            'days_in_month' => $end->day,
            'first_day_offset' => ($date->copy()->startOfMonth()->dayOfWeek + 6) % 7,
            'events' => $events,
            'today' => Carbon::today()->format('Y-m-d'),
        ];
    }

    public function formatForDetail(object $event): array
    {
        $date = $event->parsed_date;
        $image = $this->imageUrl((string) $event->event_image);
        $isUpcoming = $date->isFuture() || $date->isToday();
        $link = ! empty($event->googlemap) ? $event->googlemap : route('contact');

        return [
            'id' => $event->id,
            'title' => $event->event_name,
            'slug' => $this->slugForRow($event),
            'summary' => $event->summary,
            'tag' => $this->eventTag($event),
            'image_url' => $image,
            'status' => $isUpcoming ? 'Upcoming' : 'Completed',
            'status_value' => $isUpcoming ? 'active' : 'inactive',
            'date_label' => $date->format('d M Y'),
            'month_label' => strtoupper($date->format('M')),
            'day_label' => $date->format('d'),
            'year_label' => $date->format('Y'),
            'time_label' => $event->time ?: $date->format('h:i A'),
            'location' => $event->location ?: 'Chhattisgarh',
            'cta_link' => $link,
            'cta_label' => ! empty($event->googlemap) ? 'Open event link' : 'Contact the alliance',
            'content' => nl2br(e($event->summary)),
        ];
    }

    private function publishedEvents(): Collection
    {
        if ($this->publishedEventsCache !== null) {
            return $this->publishedEventsCache;
        }

        $rawRows = Cache::remember('events.published_raw', 300, fn () => DB::table('event')
            ->where('event_status', 1)
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get()
            ->all());

        $this->publishedEventsCache = collect($rawRows)->map(fn ($row) => $this->hydrateEvent(clone $row));

        return $this->publishedEventsCache;
    }

    private function orderedEvents(): Collection
    {
        $events = $this->publishedEvents();
        $today = Carbon::today();

        $upcoming = $events
            ->filter(fn ($event) => $event->parsed_date->isFuture() || $event->parsed_date->isToday())
            ->sortBy(fn ($event) => $event->parsed_date->timestamp);

        $past = $events
            ->filter(fn ($event) => $event->parsed_date->isPast() && ! $event->parsed_date->isToday())
            ->sortByDesc(fn ($event) => $event->parsed_date->timestamp);

        return $upcoming->values()->concat($past->values());
    }

    private function formatHomeTile(object $event, int $index): array
    {
        return [
            'tile_class' => 'tile tile-'.($index + 1),
            'image' => $this->imageUrl((string) $event->event_image),
            'tag' => $this->eventTag($event),
            'title' => $event->event_name,
            'description' => Str::limit($event->summary, $this->homeTileDescriptionLimit($index)),
            'url' => route('events.show', $this->slugForRow($event)),
        ];
    }

    private function homeTileDescriptionLimit(int $index): int
    {
        return match ($index) {
            0 => 185,
            1 => 98,
            2 => 68,
            3 => 40,
            4 => 98,
            default => 72,
        };
    }

    private function cleanEventText(string $value): string
    {
        $text = str_replace(['&nbsp;', '&#160;', "\xc2\xa0"], ' ', $value);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/u', ' ', strip_tags($text)) ?? '';

        return trim($text);
    }

    private function hydrateEvent(object $row): object
    {
        $summary = $this->cleanEventText((string) $row->description);

        if ($summary === '') {
            $summary = 'A public alliance event designed to bring practitioners, volunteers and partner organisations together for grounded learning and community action.';
        }

        $parsedDate = $this->parseEventDate($row);

        $row->summary = $summary;
        $row->parsed_date = $parsedDate;
        $row->location = trim((string) ($row->location ?? ''));
        $row->time = trim((string) ($row->time ?? ''));

        return $row;
    }

    private function parseEventDate(object $row): Carbon
    {
        $startDate = trim((string) ($row->start_date ?? ''));

        if ($startDate !== '' && preg_match('/^\d{4}-\d{2}-\d{2}/', $startDate)) {
            return Carbon::parse($startDate);
        }

        $displayDate = trim((string) ($row->date ?? ''));

        if ($displayDate !== '') {
            try {
                return Carbon::createFromFormat('d-m-y', $displayDate);
            } catch (\Throwable) {
                try {
                    return Carbon::parse($displayDate);
                } catch (\Throwable) {
                }
            }
        }

        return Carbon::today();
    }

    private function buildCalendar(Collection $events, Carbon $month): array
    {
        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();
        $eventDates = $events
            ->filter(fn ($event) => $event->parsed_date->between($start, $end))
            ->keyBy(fn ($event) => $event->parsed_date->format('Y-m-d'));

        $days = [];
        $leading = ($start->dayOfWeek + 5) % 7;

        for ($i = 0; $i < $leading; $i++) {
            $days[] = ['type' => 'out', 'day' => ''];
        }

        for ($day = 1; $day <= $end->day; $day++) {
            $date = $start->copy()->day($day);
            $key = $date->format('Y-m-d');

            if ($eventDates->has($key)) {
                $days[] = [
                    'type' => 'has',
                    'day' => (string) $day,
                    'date' => $key,
                    'dot' => $this->eventTagType($eventDates->get($key)),
                ];
            } else {
                $days[] = ['type' => 'empty', 'day' => (string) $day];
            }
        }

        while (count($days) % 6 !== 0) {
            $days[] = ['type' => 'out', 'day' => ''];
        }

        return [
            'month' => $start->format('F Y'),
            'event_count' => $eventDates->count(),
            'days' => $days,
        ];
    }

    private function eventTag(object $event): string
    {
        $type = $this->eventTagType($event);

        return match ($type) {
            'webinar' => 'Webinar · Event',
            'conf' => 'Conference · Event',
            default => 'Workshop · Event',
        };
    }

    private function eventTagType(object $event): string
    {
        $map = strtolower((string) ($event->googlemap ?? '').' '.(string) ($event->description ?? ''));

        if (str_contains($map, 'zoom') || str_contains($map, 'webinar') || str_contains($map, 'fb.me')) {
            return 'webinar';
        }

        if (str_contains($map, 'conference') || str_contains($map, 'summit')) {
            return 'conf';
        }

        return 'workshop';
    }

    public function resolveImageUrl(string $image): string
    {
        return MediaUrl::resolve('event', $image);
    }

    private function imageUrl(string $image): string
    {
        return MediaUrl::resolve('event', $image);
    }
}
