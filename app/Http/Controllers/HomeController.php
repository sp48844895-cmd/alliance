<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\Event;
use App\Models\HomeSliderSlide;
use App\Models\Program;
use App\Services\PageContentService;
use App\Support\HomeIntroSection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $intro = HomeIntroSection::build(
            app(PageContentService::class)->section('home', 'intro', [])
        );

        $homeSliderSlides = Cache::remember('home.slider-slides', 3600, fn () => HomeSliderSlide::activeForHome());

        $programRows = Program::where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $programsCards = [];
        $accents = ['grad', 'orange', 'black'];

        foreach ($programRows as $index => $program) {
            $description = trim((string) $program->short_desc);
            $full = trim((string) $program->full_desc);
            if ($full !== '' && $full !== $description) {
                $description = $description !== '' ? $description.' '.$full : $full;
            }

            $programsCards[] = [
                'title' => $program->title,
                'description' => $description,
                'image_url' => $program->image ? asset('uploads/programs/'.$program->image) : '',
                'accent' => $accents[$index % 3],
                'is_active' => $index === 0,
                'delay' => $index * 80,
            ];
        }

        $programsUseSlider = count($programsCards) > 0;
        $programsDetailsUrl = route('programs');

        $programsSection = app(PageContentService::class)->section('home', 'programs', []);
        $programsHeader = [
            'chapter_num' => $programsSection['chapter_num'] ?? '03',
            'chapter_label' => $programsSection['chapter_label'] ?? 'Programs & Initiatives',
            'heading_html' => $programsSection['heading_html'] ?? 'Programs &amp; Initiatives',
            'lede_text' => strip_tags($programsSection['lede_html'] ?? 'A focused view of flagship SBC initiatives across Chhattisgarh — from youth volunteer networks to local learning resources and community led campaigns.'),
            'explore_url' => route('programs'),
            'explore_label' => $programsSection['explore_label'] ?? 'Explore More',
        ];

        $storyRows = Blog::where('status', 1)
            ->orderByDesc('date_created')
            ->orderByDesc('id')
            ->take(6)
            ->get();

        $stories = [];
        foreach ($storyRows as $story) {
            $slug = $story->id.'-'.Str::slug($story->title);

            $stories[] = [
                'title' => $story->title,
                'image' => $story->image ? asset('uploads/story/'.$story->image) : '',
                'url' => route('stories.show', $slug),
                'description' => Str::limit(strip_tags($story->content), 150),
                'location' => trim((string) $story->location) ?: 'Chhattisgarh',
                'author' => $story->admin ?: 'ChhattisgarhABC',
            ];
        }

        $eventRows = Event::where('event_status', 1)
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        $events = [];
        foreach ($eventRows as $index => $event) {
            $slug = $event->id.'-'.Str::slug($event->event_name);
            $summary = Str::limit(strip_tags((string) $event->description), 120);

            $events[] = [
                'title' => $event->event_name,
                'image' => $event->event_image ? asset('uploads/events/'.$event->event_image) : '',
                'url' => route('events.show', $slug),
                'description' => $summary !== '' ? $summary : 'A public alliance event for grounded learning and community action.',
                'tile_class' => 'tile tile-'.($index + 1),
                'tag' => 'Workshop · Event',
            ];
        }

        $storiesUrl = route('stories');
        $eventsUrl = route('events');

        $heroSection = app(PageContentService::class)->section('home', 'hero', []);
        $hero = [
            'chapter_num' => $heroSection['chapter_num'] ?? '01',
            'chapter_label' => $heroSection['chapter_label'] ?? 'Welcome',
            'headline_html' => $heroSection['headline_html'] ?? '<span class="line">Social &amp;</span><span class="line line-nowrap"><span class="underline"><em>Behaviour Change</em></span></span><span class="line">Communication for all.</span>',
            'lede_html' => $heroSection['lede_html'] ?? 'ChhattisgarhABC is a community platform where <b>youth, professionals, civil society and government</b> come together to share experiences and understand <em>Social &amp; Behaviour Change Communication</em> across Chhattisgarh.',
            'panels' => $heroSection['panels'] ?? [
                ['class' => 'panel-1', 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/banner/1714379236.png'],
                ['class' => 'panel-2', 'image' => 'https://www.chhattisgarhabc.org/stories/uploads/banner/1714379202.png'],
            ],
            'impact_banner' => [
                'background_url' => $heroSection['impact_banner_bg'] ?? asset('investcg-slider/images/image_1.jpg'),
            ],
            'panel_tag' => $heroSection['panel_tag'] ?? ['kicker' => 'District coverage', 'value' => '33/33', 'small' => 'Districts engaged'],
            'panel_stat' => $heroSection['panel_stat'] ?? ['kicker' => 'Beneficiaries reached', 'value' => '1.4M', 'small' => 'Across households, schools and panchayats'],
            'video_poster' => $heroSection['video_poster'] ?? asset('assets/img/hero-poster.png'),
            'video_src' => $heroSection['video_src'] ?? 'assets/videos/hero.mp4',
        ];

        $heroCarouselSlides = Cache::remember('home.banners', 3600, fn () => Banner::activeForHome($hero));
        $hasBanners = count($heroCarouselSlides) > 0;

        $hubSection = app(PageContentService::class)->section('home', 'hub', []);
        $knowledgeHub = [
            'chapter_num' => $hubSection['chapter_num'] ?? '06',
            'chapter_label' => $hubSection['chapter_label'] ?? 'Knowledge Hub',
            'heading_html' => $hubSection['heading_html'] ?? 'Knowledge assets around <em>behaviour change</em>.',
            'side_text' => $hubSection['side_text'] ?? 'Learning materials, evidence, toolkits and practitioner support — organised for teams across the alliance.',
            'library_url' => route('learning-corner'),
            'library_label' => $hubSection['library_label'] ?? 'Open the full library →',
            'resources' => [
                [
                    'url' => route('learning-corner'),
                    'type' => 'Learning',
                    'title' => 'Learning Corner',
                    'meta' => 'Modules, videos, posters and training material',
                    'icon' => 'book',
                    'aos_delay' => null,
                ],
                [
                    'url' => route('reports'),
                    'type' => 'Evidence',
                    'title' => 'Reports & Insights',
                    'meta' => 'Reports, newsletters and success stories',
                    'icon' => 'chart',
                    'aos_delay' => 100,
                ],
                [
                    'url' => route('programs'),
                    'type' => 'Toolkits',
                    'title' => 'Resource Kit',
                    'meta' => 'Open-licensed guides, IEC and campaign assets',
                    'icon' => 'layers',
                    'aos_delay' => 200,
                ],
                [
                    'url' => route('resources'),
                    'type' => 'Practitioners',
                    'title' => 'SBC Resource Pool',
                    'meta' => 'Connect with trainers and field communicators',
                    'icon' => 'users',
                    'aos_delay' => 300,
                ],
            ],
        ];

        $getInvolvedSection = app(PageContentService::class)->section('home', 'get_involved_cta', []);
        $getInvolvedUrl = route('get-involved');
        $getInvolved = [
            'chapter_num' => $getInvolvedSection['chapter_num'] ?? '07',
            'chapter_label' => $getInvolvedSection['chapter_label'] ?? 'Get Involved',
            'heading_html' => $getInvolvedSection['heading_html'] ?? 'Join the <em>Alliance</em> for behaviour change.',
            'lede_html' => $getInvolvedSection['lede_html'] ?? 'Volunteer, partner as an NGO or organisation, or contribute as a professional — four clear pathways to strengthen community-led SBC across Chhattisgarh.',
            'button_label' => $getInvolvedSection['button_label'] ?? 'Explore ways to join',
            'button_url' => $getInvolvedUrl,
            'paths' => [
                [
                    'title' => 'Guest',
                    'description' => 'Support campaigns and field circles',
                    'url' => $getInvolvedUrl.'#gi-guest',
                ],
                [
                    'title' => 'Intern',
                    'description' => 'Communications, research and programme support',
                    'url' => $getInvolvedUrl.'#gi-intern',
                ],
                [
                    'title' => 'Fellowship',
                    'description' => 'Structured learning and district-level impact',
                    'url' => $getInvolvedUrl.'#gi-fellow',
                ],
                [
                    'title' => 'NGO / organisation',
                    'description' => 'Co-design campaigns and scale behaviour change',
                    'url' => $getInvolvedUrl.'#gi-partner',
                ],
            ],
        ];

        return view('home.index', compact(
            'heroCarouselSlides',
            'intro',
            'homeSliderSlides',
            'programsUseSlider',
            'programsHeader',
            'programsCards',
            'programsDetailsUrl',
            'stories',
            'events',
            'hasBanners',
            'storiesUrl',
            'eventsUrl',
            'hero',
            'knowledgeHub',
            'getInvolved'
        ));
    }
}
