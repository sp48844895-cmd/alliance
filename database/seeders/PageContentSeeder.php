<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\SeedsStoriesEventsKnowledgeGetInvolved;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageContentSeeder extends Seeder
{
    use SeedsStoriesEventsKnowledgeGetInvolved;

    public function run(): void
    {
        DB::table('page_sections')->delete();
        DB::table('pages')->delete();

        $pages = $this->pages();

        foreach ($pages as $page) {
            $sections = $page['sections'];
            unset($page['sections']);

            $pageId = DB::table('pages')->insertGetId(array_merge($page, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));

            foreach ($sections as $index => $section) {
                DB::table('page_sections')->insert([
                    'page_id' => $pageId,
                    'section_key' => $section['key'],
                    'section_type' => $section['type'],
                    'content' => json_encode($section['content'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'sort_order' => $section['sort'] ?? ($index + 1),
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function pages(): array
    {
        return [
            $this->page('home', 'home', 'ChhattisgarhABC · Alliance for Behaviour Change Chhattisgarh', null, require __DIR__.'/content/home_sections.php'),
            $this->page('about', 'about', 'About the Alliance · ChhattisgarhABC', 'ChhattisgarhABC is an open, non-financial alliance of youth, professionals, civil society and government — co-creating Social & Behaviour Change Communication across Chhattisgarh, with a deep focus on PVTG villages.', require __DIR__.'/content/about_sections.php'),
            $this->page('campaigns', 'campaigns', 'Campaigns · From Behaviour to Habit · ChhattisgarhABC', 'Six in-progress SBC campaigns across Chhattisgarh — Role of Males, Children & Education, Life Cycle Nutrition, Gender & Behaviour, Adolescent Health, and Community Participation. Filter by theme, district, and stage.', require __DIR__.'/content/campaigns_sections.php'),
            $this->page('stories', 'stories', 'Impact Stories · ChhattisgarhABC', 'Champions of Change, Stories from the Field, Case Studies and Voices from across Chhattisgarh — the slow, stubborn arc of behaviour becoming habit, told by the people who lived it.', array_merge([
                $this->jumbotron('stories', 'Stories & updates', 'Stories', 'Voices, case studies and field moments that show how behaviour-change work feels on the ground.', 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?q=80&w=1600&auto=format&fit=crop', 'story'),
            ], $this->storiesPageSections())),
            $this->page('events', 'events', 'Events · ChhattisgarhABC', 'A calendar of upcoming and completed SBC events — workshops, trainings, webinars and conferences across Chhattisgarh. Register, attend, download recap reports.', array_merge([
                $this->jumbotron('events', 'Events & engagement', 'Events', 'Upcoming gatherings, hands-on trainings and webinars that keep the alliance learning in public.', 'https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=1600&auto=format&fit=crop', 'calendar'),
            ], $this->eventsPageSections())),
            $this->page('programs', 'programs-and-initiatives', 'Programs and Initiatives · ChhattisgarhABC', 'Flagship SBC programs and initiatives across Chhattisgarh — youth volunteer networks, learning resources and community-led campaigns.', [
                $this->jumbotron('programs', 'Programs & initiatives', 'Programs and Initiatives', 'Explore flagship behaviour-change programmes from youth networks to district learning resources and community action.', 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?q=80&w=1600&auto=format&fit=crop', 'target'),
                $this->section('page_header', 'page_header', [
                    'pageTitle' => 'Programs and Initiatives',
                    'pageLede' => 'A focused view of flagship SBC initiatives across Chhattisgarh.',
                ]),
            ]),
            $this->page('get-involved', 'get-involved', 'Get Involved · ChhattisgarhABC', 'Four pathways to join the alliance — volunteer, intern, fellowship or organisation partner. Pick yours and register to support social and behavioural change communication.', array_merge([
                $this->jumbotron('get-involved', 'Participation pathways', 'Get Involved', 'Join as a volunteer, intern, fellow or organisation partner and help strengthen community-led change across Chhattisgarh.', 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1600&auto=format&fit=crop', 'users'),
            ], $this->getInvolvedPageSections())),
            $this->page('resources', 'resources', 'SBC Resource Pool · ChhattisgarhABC', 'Meet the SBC Resource Pool of ChhattisgarhABC: resource people supporting social and behaviour change learning, facilitation and field practice.', [
                $this->jumbotron('resources', 'Open resource pool', 'SBC Resource Pool', 'Connect with resource people who support social and behaviour change learning, facilitation and field practice across Chhattisgarh.', 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?q=80&w=1600&auto=format&fit=crop', 'book'),
                $this->section('page_header', 'page_header', [
                    'pageTitle' => 'SBC Resource Pool',
                    'pageLede' => 'Connect with resource people who support social and behaviour change learning, facilitation and field practice across Chhattisgarh.',
                ]),
                $this->section('resource_directory', 'resource_directory', [
                    'eyebrow' => 'Resource people',
                    'title' => 'Connect with practitioners',
                    'description' => 'Browse the current resource pool and reach out by email for learning support, facilitation and collaboration.',
                ]),
            ]),
            $this->page('members', 'members', 'Our Members · ChhattisgarhABC', 'Browse ChhattisgarhABC members by district and member type, including individuals, volunteers, NGOs, CSOs, firms and organisations.', [
                $this->jumbotron('members', 'Alliance network', 'Our Members', 'Meet the volunteers, individuals, NGOs, CSOs and organisations strengthening social and behavioural change across Chhattisgarh.', 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?q=80&w=1600&auto=format&fit=crop', 'users'),
                $this->section('page_header', 'page_header', [
                    'pageTitle' => 'Our Members',
                    'pageLede' => 'Meet the volunteers, individuals, NGOs, CSOs and organisations strengthening social and behavioural change across Chhattisgarh.',
                ]),
                $this->section('members_overview', 'members_overview', [
                    'chapter' => '01',
                    'title' => 'People behind the <em>alliance.</em>',
                    'description' => 'Browse members district-wise, search by name, or filter by member type. The directory brings individuals, volunteers, NGOs, CSOs and organisations into one easy view.',
                ]),
                $this->section('members_filters', 'members_filters', [
                    'districts' => $this->memberDistricts(),
                    'member_types' => $this->memberTypes(),
                ]),
                $this->section('members_directory', 'members_directory', [
                    'chapter' => '02',
                    'title' => 'Find a member.',
                    'members' => $this->memberProfiles(),
                ]),
            ]),
            $this->page('contact', 'contact', 'Contact Us · ChhattisgarhABC', 'Contact ChhattisgarhABC in Raipur, Chhattisgarh for partnerships, volunteering, resources, events and social behaviour change communication collaboration.', [
                $this->jumbotron('contact', 'Reach the alliance', 'Contact Us', 'Write to the ChhattisgarhABC team for partnerships, volunteering, resources or event collaboration.', 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1600&auto=format&fit=crop', 'mail'),
                $this->section('page_header', 'page_header', [
                    'pageTitle' => 'Contact Us',
                    'pageLede' => 'Write to the ChhattisgarhABC team for partnerships, volunteering, resources or event collaboration.',
                ]),
                $this->section('contact_cards', 'contact_cards', [
                    'cards' => [
                        [
                            'label' => 'Email us',
                            'value' => 'info@chhttisgarhabc.org',
                            'href' => 'mailto:info@chhttisgarhabc.org',
                            'note' => 'Best for partnerships, media, resources and programme queries.',
                            'icon' => 'mail',
                        ],
                        [
                            'label' => 'Call us',
                            'value' => '+91 90984 98822',
                            'href' => 'tel:+919098498822',
                            'note' => 'Reach the team for quick coordination and event support.',
                            'icon' => 'phone',
                        ],
                        [
                            'label' => 'Visit base',
                            'value' => 'Raipur, Chhattisgarh',
                            'href' => 'https://www.google.com/maps/search/?api=1&query=Raipur%2C%20Chhattisgarh',
                            'note' => 'Our alliance work is coordinated from Raipur across districts.',
                            'icon' => 'map',
                        ],
                    ],
                ]),
                $this->section('contact_form', 'contact_form', [
                    'chapter' => '01',
                    'title' => 'Send a message to the alliance.',
                    'description' => 'Your email address will not be published. Share a few details and the team will respond on email or phone.',
                ]),
                $this->section('contact_map', 'contact_map', [
                    'chapter' => '03',
                    'title' => 'Based in Raipur, connected across Chhattisgarh.',
                    'description' => 'The alliance works with local members, partners and community networks across districts. Use the map to locate Raipur or open it in Google Maps.',
                    'button_text' => 'Open map',
                    'map_url' => 'https://www.google.com/maps/search/?api=1&query=Raipur%2C%20Chhattisgarh',
                    'iframe_src' => 'https://www.google.com/maps?q=Raipur%2C%20Chhattisgarh&output=embed',
                ]),
            ]),
            $this->page('learning-corner', 'learning-corner', 'Learning Corner · ChhattisgarhABC', 'Learning Corner brings together SBC learning themes, short modules, videos, posters, flipbooks and training material for community teams and alliance members.', [
                $this->jumbotron('learning-corner', 'Learning space', 'Learning Corner', 'Explore short modules, videos, posters, flipbooks and training material for strengthening SBC practice.', 'https://images.unsplash.com/photo-1513258496099-48168024aec0?q=80&w=1600&auto=format&fit=crop', 'book'),
                $this->section('page_header', 'page_header', [
                    'pageTitle' => 'Learning Corner',
                    'pageLede' => 'Explore short modules, videos, posters, flipbooks and training material for strengthening SBC practice.',
                ]),
                $this->section('learning_overview', 'learning_overview', [
                    'chapter' => '01',
                    'title' => 'Learn fast. <em>Use it in the field.</em>',
                    'description' => 'Learning Corner is a practical shelf of books, videos, banners, flipbooks, training modules and quick material that teams can reuse during community sessions.',
                ]),
                $this->section('learning_categories', 'learning_categories', [
                    'categories' => $this->learningCategories(),
                ]),
                $this->section('learning_material_types', 'learning_material_types', [
                    'types' => $this->learningMaterialTypes(),
                ]),
                $this->section('learning_resources', 'learning_resources', [
                    'resources' => $this->learningResources(),
                ]),
                $this->section('learning_categories_section', 'learning_categories_section', [
                    'chapter' => '02',
                    'title' => 'Choose a theme.',
                ]),
            ]),
            $this->page('reports', 'reports', 'Reports and Insights · ChhattisgarhABC', 'Reports, newsletters and success stories from ChhattisgarhABC documenting social and behaviour change work across Chhattisgarh.', [
                $this->jumbotron('reports', 'Evidence & insight', 'Reports and Insights', 'Read reports, newsletters and success stories that document social and behaviour change work across Chhattisgarh.', 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=1600&auto=format&fit=crop', 'chart'),
                $this->section('page_header', 'page_header', [
                    'pageTitle' => 'Reports and Insights',
                    'pageLede' => 'Read reports, newsletters and success stories that document social and behaviour change work across Chhattisgarh.',
                ]),
                $this->section('reports_grid', 'reports_grid', [
                    'reports' => [
                        [
                            'title' => 'Report: Noni Johar 2024',
                            'preview' => 'magazine/Report_Noni_Johar_24.html',
                            'download' => 'images/book/Report_Noni_Johar_24_compressed.pdf',
                            'cover' => 'images/book/bookcover_3.jpg',
                            'type' => 'Report',
                        ],
                        [
                            'title' => 'Agricons Foundation Newsletter',
                            'preview' => 'magazine/af_newsletter.html',
                            'download' => 'images/book/AF_Newsletter_Vol 1_compressed.pdf',
                            'cover' => 'images/book/bookcover_2.jpg',
                            'type' => 'Newsletter',
                        ],
                        [
                            'title' => 'ChhattisgarhABC Success Stories',
                            'preview' => 'magazine/slider.html',
                            'download' => 'images/book/ChhattisgarhABC_SuccessStories_compressed.pdf',
                            'cover' => 'images/book/bookcover.JPG',
                            'type' => 'Stories',
                        ],
                    ],
                ]),
            ]),
        ];
    }

    private function page(string $slug, string $routeName, string $metaTitle, ?string $metaDescription, array $sections, int $sortOrder = 0): array
    {
        return [
            'slug' => $slug,
            'route_name' => $routeName,
            'title' => $metaTitle,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'sort_order' => $sortOrder,
            'sections' => $sections,
        ];
    }

    private function section(string $key, string $type, array $content, int $sort = 10): array
    {
        return [
            'key' => $key,
            'type' => $type,
            'content' => $content,
            'sort' => $sort,
        ];
    }

    private function jumbotron(string $slug, string $eyebrow, string $title, string $lede, string $image, string $icon): array
    {
        $highlights = match ($slug) {
            'about' => [
                ['label' => 'Vision & mission', 'value' => 'See the shared purpose shaping the alliance across Chhattisgarh.'],
                ['label' => 'How it works', 'value' => 'Understand the open rotation model and collaborative rhythm.'],
                ['label' => 'Partners & members', 'value' => 'Meet the volunteers, NGOs, firms and academia in the network.'],
            ],
            'campaigns' => [
                ['label' => 'Live themes', 'value' => 'Explore campaigns across health, gender, nutrition, children and community action.'],
                ['label' => 'District filters', 'value' => 'Browse the work by theme and district to focus on what matters most.'],
                ['label' => 'Timelines', 'value' => 'Follow how each campaign moves from first listen to lived habit.'],
            ],
            'stories' => [
                ['label' => 'Field voices', 'value' => 'Read direct accounts from communities, facilitators and youth leaders.'],
                ['label' => 'Case studies', 'value' => 'Follow grounded stories with practical learning and replicable insight.'],
                ['label' => 'Recent updates', 'value' => 'Stay close to milestones, reflections and fresh movement across districts.'],
            ],
            'events' => [
                ['label' => 'Upcoming events', 'value' => 'Track what is happening next across districts and partner spaces.'],
                ['label' => 'Calendar view', 'value' => 'Browse scheduled workshops, trainings and alliance sessions.'],
                ['label' => 'Past recaps', 'value' => 'Revisit completed events through summaries, photos and report links.'],
            ],
            'programs' => [
                ['label' => 'Flagship work', 'value' => 'See district programmes and youth-led initiatives in one place.'],
                ['label' => 'Field networks', 'value' => 'From volunteer circles to learning corners and community campaigns.'],
                ['label' => 'Open to partners', 'value' => 'Co-design and scale behaviour change with the alliance.'],
            ],
            'get-involved' => [
                ['label' => 'Volunteer', 'value' => 'Register for field outreach, mobilisation and programme support.'],
                ['label' => 'Intern & fellowship', 'value' => 'Apply for structured learning and district-level practice roles.'],
                ['label' => 'Organisation partner', 'value' => 'CSOs, NGOs and firms can co-design campaigns with the alliance.'],
            ],
            'resources' => [
                ['label' => 'Templates', 'value' => 'Access reusable assets for planning, printing and facilitation.'],
                ['label' => 'Field-ready packs', 'value' => 'Use simple resources designed to work in real programme settings.'],
                ['label' => 'Open access', 'value' => 'Download and adapt materials without friction.'],
            ],
            'members' => [
                ['label' => '33 districts', 'value' => 'Find members by district and local area of work.'],
                ['label' => '4 member types', 'value' => 'Individuals, volunteers, NGOs / CSOs and firms in one network.'],
                ['label' => 'Open to join', 'value' => 'Choose the pathway that fits your skills and contribution.'],
            ],
            'contact' => [
                ['label' => 'Partnerships', 'value' => 'Start a conversation around campaigns, events and support.'],
                ['label' => 'Direct support', 'value' => 'Reach the team for questions about access, resources and participation.'],
                ['label' => 'Raipur base', 'value' => 'Connect with the alliance from its operational base in Chhattisgarh.'],
            ],
            'learning-corner' => [
                ['label' => '10 themes', 'value' => 'Browse nutrition, hygiene, life skills, mental health, SBC and more.'],
                ['label' => '9 formats', 'value' => 'Use books, videos, posters, brochures, training modules and flipbooks.'],
                ['label' => 'Field ready', 'value' => 'Simple material for trainings, onboarding and community sessions.'],
            ],
            'reports' => [
                ['label' => 'Field reports', 'value' => 'Review practical lessons and programme learnings from the ground.'],
                ['label' => 'Insight notes', 'value' => 'See concise takeaways that support decisions and next steps.'],
                ['label' => 'Research links', 'value' => 'Connect data, observation and behaviour-change design.'],
            ],
            default => [],
        };

        return $this->section('jumbotron', 'jumbotron', [
            'eyebrow' => $eyebrow,
            'title' => $title,
            'lede' => $lede,
            'image' => $image,
            'icon' => $icon,
            'highlights' => $highlights,
        ], 2);
    }

    private function memberDistricts(): array
    {
        return [
            ['value' => 'surguja', 'label' => 'SURGUJA'],
            ['value' => 'surajpur', 'label' => 'SURAJPUR'],
            ['value' => 'sukma', 'label' => 'SUKMA'],
            ['value' => 'sarangarh-bilaigarh', 'label' => 'SARANGARH BILAIGARH'],
            ['value' => 'sakti', 'label' => 'SAKTI'],
            ['value' => 'rajnandagon', 'label' => 'RAJNANDAGON'],
            ['value' => 'raipur', 'label' => 'RAIPUR'],
            ['value' => 'raigarh', 'label' => 'RAIGARH'],
            ['value' => 'narayanpur', 'label' => 'NARAYANPUR'],
            ['value' => 'mungeli', 'label' => 'MUNGELI'],
            ['value' => 'mohla-manpur-ambagarh-chowki', 'label' => 'MOHLA MANPUR AMBAGARH CHOWKI'],
            ['value' => 'manendragarh-chirmiri-bharatpur', 'label' => 'MANENDRAGARH CHIRMIRI BHARATPUR'],
            ['value' => 'mahasamund', 'label' => 'MAHASAMUND'],
            ['value' => 'korea', 'label' => 'KOREA'],
            ['value' => 'korba', 'label' => 'KORBA'],
            ['value' => 'kondagaon', 'label' => 'KONDAGAON'],
            ['value' => 'khairagarh-chhuikhadan-gandai', 'label' => 'KHAIRAGARH CHHUIKHADAN GANDAI'],
            ['value' => 'kawardha', 'label' => 'KAWARDHA'],
            ['value' => 'kanker', 'label' => 'KANKER'],
            ['value' => 'jashpur', 'label' => 'JASHPUR'],
            ['value' => 'janjgir-champa', 'label' => 'JANJGIR-CHAMPA'],
            ['value' => 'gaurela-pendra-marwahi', 'label' => 'GAURELA PENDRA MARWAHI'],
            ['value' => 'gariyaband', 'label' => 'GARIYABAND'],
            ['value' => 'durg', 'label' => 'DURG'],
            ['value' => 'dhamtari', 'label' => 'DHAMTARI'],
            ['value' => 'dantewada', 'label' => 'DANTEWADA'],
            ['value' => 'bilaspur', 'label' => 'BILASPUR'],
            ['value' => 'bijapur', 'label' => 'BIJAPUR'],
            ['value' => 'bemetara', 'label' => 'BEMETARA'],
            ['value' => 'bastar', 'label' => 'BASTAR'],
            ['value' => 'balrampur', 'label' => 'BALRAMPUR'],
            ['value' => 'baloda-bazar', 'label' => 'BALODA BAZAR'],
            ['value' => 'balod', 'label' => 'BALOD'],
        ];
    }

    private function memberTypes(): array
    {
        return [
            ['value' => 'individual', 'label' => 'Individual'],
            ['value' => 'volunteer', 'label' => 'Volunteer'],
            ['value' => 'ngo-cso', 'label' => 'NGO/CSO'],
            ['value' => 'firm-organization', 'label' => 'Firm/Organization'],
        ];
    }

    private function memberProfiles(): array
    {
        return [
            ['name' => 'Mahesh Nirmalkar', 'initial' => 'M', 'district' => 'BALODA BAZAR', 'district_value' => 'baloda-bazar', 'type' => 'individual', 'phone' => '7869870005', 'phone_link' => '7869870005', 'email' => 'maheshnirmalkar12@gmail.com', 'focus' => 'Community mobilisation'],
            ['name' => 'Mamta Chouhan', 'initial' => 'M', 'district' => 'BEMETARA', 'district_value' => 'bemetara', 'type' => 'volunteer', 'phone' => '9981574213', 'phone_link' => '9981574213', 'email' => 'cgademp.surguja@gmail.com', 'focus' => 'Volunteer coordination'],
            ['name' => 'Monika Baghel', 'initial' => 'M', 'district' => 'DURG', 'district_value' => 'durg', 'type' => 'individual', 'phone' => '9131388122', 'phone_link' => '9131388122', 'email' => 'baghelmonika03@gmail.com', 'focus' => 'Documentation and outreach'],
            ['name' => 'Abdul Dalam', 'initial' => 'A', 'district' => 'NARAYANPUR', 'district_value' => 'narayanpur', 'type' => 'volunteer', 'phone' => '7000258101', 'phone_link' => '7000258101', 'email' => 'nagmanojkumar@gmail.com', 'focus' => 'Field engagement'],
            ['name' => 'Abhinay Singh Thakur', 'initial' => 'A', 'district' => 'DURG', 'district_value' => 'durg', 'type' => 'individual', 'phone' => '7974031154', 'phone_link' => '7974031154', 'email' => 'abhinaysinghthakur1995@gmail.com', 'focus' => 'Youth engagement'],
            ['name' => 'Ajay Kalyani', 'initial' => 'A', 'district' => 'BALRAMPUR', 'district_value' => 'balrampur', 'type' => 'ngo-cso', 'phone' => '9425556961', 'phone_link' => '9425556961', 'email' => 'kalyaniindiaorg@gmail.com', 'focus' => 'NGO partnership'],
            ['name' => 'Arjun Kashyap', 'initial' => 'A', 'district' => 'GARIYABAND', 'district_value' => 'gariyaband', 'type' => 'volunteer', 'phone' => '7999746337', 'phone_link' => '7999746337', 'email' => 'arjunkashyap540@gmail.com', 'focus' => 'Local facilitation'],
            ['name' => 'Arjun Kumeti', 'initial' => 'A', 'district' => 'KONDAGAON', 'district_value' => 'kondagaon', 'type' => 'volunteer', 'phone' => '9770509454', 'phone_link' => '9770509454', 'email' => 'kaushikpandey85@mail.com', 'focus' => 'District outreach'],
            ['name' => 'Ashok Kumar', 'initial' => 'A', 'district' => 'BALRAMPUR', 'district_value' => 'balrampur', 'type' => 'ngo-cso', 'phone' => '8877886673', 'phone_link' => '8877886673', 'email' => 'sahityalayalifengo@gmail.com', 'focus' => 'CSO collaboration'],
            ['name' => 'Bhoopendra Mishra', 'initial' => 'B', 'district' => 'DURG', 'district_value' => 'durg', 'type' => 'individual', 'phone' => '7999917885', 'phone_link' => '7999917885', 'email' => 'bhupendramishra18@gmail.com', 'focus' => 'Behaviour change communication'],
            ['name' => 'Chetan Prasad', 'initial' => 'C', 'district' => 'JASHPUR', 'district_value' => 'jashpur', 'type' => 'individual', 'phone' => '9617316469', 'phone_link' => '9617316469', 'email' => 'chetansahu121092@gmail.com', 'focus' => 'Training support'],
            ['name' => 'Chintamani Suryavanshi', 'initial' => 'C', 'district' => 'GARIYABAND', 'district_value' => 'gariyaband', 'type' => 'volunteer', 'phone' => '6265751502', 'phone_link' => '6265751502', 'email' => 'www.chintamanisuryavanshi@gmail.com', 'focus' => 'Community learning'],
            ['name' => 'Deepika Singh Bais', 'initial' => 'D', 'district' => 'KAWARDHA', 'district_value' => 'kawardha', 'type' => 'individual', 'phone' => '8839528173', 'phone_link' => '8839528173', 'email' => 'neha9691likes@gmail.com', 'focus' => 'Women and child health'],
            ['name' => 'Dr. Ashish Majumdar', 'initial' => 'D', 'district' => 'KAWARDHA', 'district_value' => 'kawardha', 'type' => 'individual', 'phone' => '8518888811', 'phone_link' => '8518888811', 'email' => 'ashishmajumdar27@gmail.com', 'focus' => 'Public health guidance'],
            ['name' => 'Gaurav Parimal', 'initial' => 'G', 'district' => 'DURG', 'district_value' => 'durg', 'type' => 'firm-organization', 'phone' => '6260932280', 'phone_link' => '6260932280', 'email' => 'gauravparimal@gmail.com', 'focus' => 'Organisation support'],
            ['name' => 'Komal Baghel', 'initial' => 'K', 'district' => 'DURG', 'district_value' => 'durg', 'type' => 'volunteer', 'phone' => '7828529562', 'phone_link' => '7828529562', 'email' => 'Kb8503726@gmail.com', 'focus' => 'Volunteer outreach'],
            ['name' => 'Kuber Yadu', 'initial' => 'K', 'district' => 'KANKER', 'district_value' => 'kanker', 'type' => 'individual', 'phone' => '6260900097', 'phone_link' => '6260900097', 'email' => 'kuberyadu81@gmail.com', 'focus' => 'District coordination'],
            ['name' => 'Lalbahadur Pandey', 'initial' => 'L', 'district' => 'GARIYABAND', 'district_value' => 'gariyaband', 'type' => 'individual', 'phone' => '6260792102', 'phone_link' => '6260792102', 'email' => 'lalbahadurpandey377@gmail.com', 'focus' => 'Field documentation'],
            ['name' => 'Mahesh Komram', 'initial' => 'M', 'district' => 'KHAIRAGARH CHHUIKHADAN GANDAI', 'district_value' => 'khairagarh-chhuikhadan-gandai', 'type' => 'volunteer', 'phone' => '9691649800', 'phone_link' => '9691649800', 'email' => 'maheshkomram2192@gmail.com', 'focus' => 'Grassroots mobilisation'],
        ];
    }

    private function learningCategories(): array
    {
        return [
            ['value' => 'nutrition', 'label' => 'Nutrition', 'short' => 'NU', 'count' => 1],
            ['value' => 'hygiene', 'label' => 'ODF+ and Hygiene', 'short' => 'OH', 'count' => 2],
            ['value' => 'child-health', 'label' => 'Child Health and Nutrition', 'short' => 'CH', 'count' => 1],
            ['value' => 'maternal-health', 'label' => 'Maternal Health and Nutrition', 'short' => 'MH', 'count' => 1],
            ['value' => 'education', 'label' => 'Education and Parental engagement', 'short' => 'EP', 'count' => 1],
            ['value' => 'life-skills', 'label' => 'Life skills', 'short' => 'LS', 'count' => 1],
            ['value' => 'mission-life', 'label' => 'Mission Life', 'short' => 'ML', 'count' => 1],
            ['value' => 'adolescent-health', 'label' => 'Adolescent health and Nutrition', 'short' => 'AH', 'count' => 1],
            ['value' => 'mental-health', 'label' => 'Mental Health', 'short' => 'ME', 'count' => 1],
            ['value' => 'sbc', 'label' => 'SBC', 'short' => 'SB', 'count' => 2],
        ];
    }

    private function learningMaterialTypes(): array
    {
        return [
            ['value' => 'book', 'label' => 'Book'],
            ['value' => 'video', 'label' => 'Video'],
            ['value' => 'banner', 'label' => 'Banner'],
            ['value' => 'flipbook', 'label' => 'Flipbook'],
            ['value' => 'training-module', 'label' => 'Training Module'],
            ['value' => 'mobile-kunji', 'label' => 'Mobile Kunji'],
            ['value' => 'posters', 'label' => 'Posters'],
            ['value' => 'leaflet', 'label' => 'Leaflet'],
            ['value' => 'brochure', 'label' => 'Brochure'],
        ];
    }

    private function learningResources(): array
    {
        return [
            ['title' => 'Nutrition Basics for Community Sessions', 'category' => 'nutrition', 'material' => 'training-module', 'date' => '2026-04-26', 'duration' => '45 min', 'level' => 'Beginner', 'description' => 'A simple session plan for explaining food groups, local meals and behaviour cues during village meetings.', 'keywords' => 'nutrition training module local meals community food groups'],
            ['title' => 'ODF+ Hygiene Checklist', 'category' => 'hygiene', 'material' => 'leaflet', 'date' => '2026-04-12', 'duration' => '2 pages', 'level' => 'Field ready', 'description' => 'A quick checklist for sanitation, handwashing, greywater and solid waste follow-up visits.', 'keywords' => 'odf hygiene leaflet sanitation handwashing checklist'],
            ['title' => 'Child Growth Monitoring Flipbook', 'category' => 'child-health', 'material' => 'flipbook', 'date' => '2026-03-29', 'duration' => '18 frames', 'level' => 'Trainer', 'description' => 'Visual prompts for explaining weight tracking, referral signs and family follow-up for young children.', 'keywords' => 'child health nutrition flipbook growth monitoring referral'],
            ['title' => 'Maternal Nutrition Counselling Guide', 'category' => 'maternal-health', 'material' => 'book', 'date' => '2026-03-18', 'duration' => '28 pages', 'level' => 'Intermediate', 'description' => 'Counselling prompts for pregnancy, meal diversity, anaemia prevention and home support.', 'keywords' => 'maternal health nutrition book pregnancy anaemia counselling'],
            ['title' => 'Parent Engagement Meeting Module', 'category' => 'education', 'material' => 'training-module', 'date' => '2026-02-27', 'duration' => '60 min', 'level' => 'Facilitator', 'description' => 'A ready-to-use module for parent meetings around attendance, learning routines and school support.', 'keywords' => 'education parental engagement training module attendance learning'],
            ['title' => 'Life Skills Activity Cards', 'category' => 'life-skills', 'material' => 'posters', 'date' => '2026-02-16', 'duration' => '12 cards', 'level' => 'Youth', 'description' => 'Short activities for communication, decision-making, self-awareness and peer support sessions.', 'keywords' => 'life skills posters youth decision communication cards'],
            ['title' => 'Mission Life Behaviour Banner Pack', 'category' => 'mission-life', 'material' => 'banner', 'date' => '2026-01-30', 'duration' => '6 banners', 'level' => 'Campaign', 'description' => 'Printable banner copy for community conversations on waste, water, energy and climate-friendly habits.', 'keywords' => 'mission life banner climate waste water energy'],
            ['title' => 'Adolescent Health Video Session', 'category' => 'adolescent-health', 'material' => 'video', 'date' => '2026-01-18', 'duration' => '11 min', 'level' => 'Youth', 'description' => 'A short video session starter covering nutrition, questions, myths and supportive peer discussion.', 'keywords' => 'adolescent health nutrition video youth myths'],
            ['title' => 'Mental Health First Conversation', 'category' => 'mental-health', 'material' => 'mobile-kunji', 'date' => '2025-12-22', 'duration' => '8 prompts', 'level' => 'Beginner', 'description' => 'Mobile-ready prompts for starting supportive conversations and identifying when referral is needed.', 'keywords' => 'mental health mobile kunji conversation referral support'],
            ['title' => 'SBC Planning Brochure', 'category' => 'sbc', 'material' => 'brochure', 'date' => '2025-12-08', 'duration' => '6 pages', 'level' => 'Planner', 'description' => 'A concise introduction to audience insight, barrier mapping, message design and field testing.', 'keywords' => 'sbc brochure planning audience insight barrier mapping'],
            ['title' => 'SBC Resource Mapping Workbook', 'category' => 'sbc', 'material' => 'book', 'date' => '2025-11-20', 'duration' => '34 pages', 'level' => 'Practitioner', 'description' => 'A practical workbook for mapping themes, channels, influencers and district learning needs.', 'keywords' => 'sbc book workbook resource mapping district influencers'],
            ['title' => 'Handwashing Reminder Posters', 'category' => 'hygiene', 'material' => 'posters', 'date' => '2025-11-05', 'duration' => '5 posters', 'level' => 'Field ready', 'description' => 'Simple visual reminders for schools, anganwadi centres and community meeting spaces.', 'keywords' => 'hygiene posters handwashing school anganwadi'],
        ];
    }
}
