@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('breadcrumb')
    <span>Dashboard</span>
@endsection

@section('topbar_actions')
@endsection

@section('content')
    <div class="relative overflow-hidden rounded-2xl mb-7 px-6 py-5 flex items-center justify-between gap-6" style="background: linear-gradient(135deg, #5d2cb5 0%, #3237f0 100%);">
        <div class="absolute inset-0 opacity-15 pointer-events-none" aria-hidden="true" style="background-image: url(\"data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='260' height='140'><g fill='none' stroke='white' stroke-width='1.2' opacity='0.8'><circle cx='200' cy='70' r='60'/><circle cx='200' cy='70' r='40'/><circle cx='200' cy='70' r='22'/></g><g fill='white' opacity='0.6'><circle cx='200' cy='10' r='3'/><circle cx='260' cy='70' r='3'/><circle cx='200' cy='130' r='3'/><circle cx='140' cy='70' r='3'/></g></svg>\"); background-position: right -20px center; background-repeat: no-repeat;"></div>
        <div class="relative">
            <p class="text-white/70 text-xs uppercase tracking-[0.18em] font-semibold mb-1">Alliance for Behaviour Change · Chhattisgarh</p>
            <h2 class="font-display text-2xl text-white font-medium leading-snug tracking-tight mb-1">
                Welcome back, <em class="not-italic text-white/90">{{ auth()->user()->fname ?? 'Admin' }}</em>.
            </h2>
            <p class="text-white/65 text-sm max-w-lg">Here is what is happening on the platform today.</p>
        </div>
        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="shrink-0 hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white/15 hover:bg-white/25 border border-white/25 text-white text-sm font-semibold transition">
            <i class="bi bi-box-arrow-up-right text-xs"></i>
            <span>View site</span>
        </a>
    </div>

    <div class="stagger grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="stat">
            <div class="stat-label">Stories</div>
            <div class="stat-num mt-2">{{ number_format($stats['blogs_total']) }}</div>
            <div class="text-xs text-[var(--color-mute)] mt-1">All posts in the system</div>
        </div>

        <div class="stat">
            <div class="stat-label">Published</div>
            <div class="stat-num mt-2">{{ number_format($stats['blogs_published']) }}</div>
            <div class="text-xs text-[var(--color-mute)] mt-1">Live on the website</div>
        </div>

        <div class="stat">
            <div class="stat-label">Events</div>
            <div class="stat-num mt-2">{{ number_format($stats['events_total']) }}</div>
            <div class="text-xs text-[var(--color-mute)] mt-1">Listed across districts</div>
        </div>

        <div class="stat">
            <div class="stat-label">Members</div>
            <div class="stat-num mt-2">{{ number_format($stats['memberships_total']) }}</div>
            <div class="text-xs text-[var(--color-mute)] mt-1">Registered members</div>
        </div>

        <div class="stat">
            <div class="stat-label">Unread contacts</div>
            <div class="stat-num mt-2">{{ number_format($stats['unread_contacts']) }}</div>
            <div class="text-xs text-[var(--color-mute)] mt-1">Waiting for a reply</div>
        </div>

        <div class="stat">
            <div class="stat-label">Categories</div>
            <div class="stat-num mt-2">{{ number_format($stats['categories_total']) }}</div>
            <div class="text-xs text-[var(--color-mute)] mt-1">Story categories</div>
        </div>

        <div class="stat">
            <div class="stat-label">Learning hub</div>
            <div class="stat-num mt-2">{{ number_format($stats['learning_total']) }}</div>
            <div class="text-xs text-[var(--color-mute)] mt-1">Resources in the corner</div>
        </div>

        <div class="stat">
            <div class="stat-label">Drafts</div>
            <div class="stat-num mt-2">{{ number_format($stats['blogs_total'] - $stats['blogs_published']) }}</div>
            <div class="text-xs text-[var(--color-mute)] mt-1">Not yet published</div>
        </div>

        @if(($stats['pending_stories'] ?? 0) > 0)
        <a href="{{ route('admin.stories.index', ['approval_status' => 'pending']) }}" class="stat ring-2 ring-[var(--color-clay-200)] hover:ring-[var(--color-clay-400)] transition">
            <div class="stat-label">Needs review</div>
            <div class="stat-num mt-2 text-[var(--color-clay-600)]">{{ number_format($stats['pending_stories']) }}</div>
            <div class="text-xs text-[var(--color-mute)] mt-1">Author submissions awaiting approval</div>
        </a>
        @endif
        @if(($stats['new_contact_msgs'] ?? 0) > 0)
        <a href="{{ route('admin.contact-messages.index', ['status' => 'new']) }}" class="stat ring-2 ring-[var(--color-river-200)] hover:ring-[var(--color-river-400)] transition">
            <div class="stat-label">New enquiries</div>
            <div class="stat-num mt-2 text-[var(--color-river-600)]">{{ number_format($stats['new_contact_msgs']) }}</div>
            <div class="text-xs text-[var(--color-mute)] mt-1">Volunteer, NGO and general messages</div>
        </a>
        @endif
        @if(($stats['new_applications'] ?? 0) > 0)
        <a href="{{ route('admin.registrations.index', ['status' => 'new']) }}" class="stat ring-2 ring-[var(--color-clay-200)] hover:ring-[var(--color-clay-400)] transition">
            <div class="stat-label">New applications</div>
            <div class="stat-num mt-2 text-[var(--color-clay-600)]">{{ number_format($stats['new_applications']) }}</div>
            <div class="text-xs text-[var(--color-mute)] mt-1">Intern and fellowship sign-ups</div>
        </a>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card p-5 lg:p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="font-display text-lg text-[var(--color-ink-2)]">Latest stories</h2>
                    <p class="text-xs text-[var(--color-mute)] mt-0.5">Recently added posts</p>
                </div>
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-ghost btn-sm">
                    <span>View all</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            @if($recentBlogs->isEmpty())
                <div class="py-10 text-center">
                    <i class="bi bi-journal-richtext text-3xl text-[var(--color-mute-2)]"></i>
                    <div class="font-display text-base mt-3">No stories yet</div>
                    <div class="text-sm text-[var(--color-mute)] mt-1">Your first story will show up here.</div>
                </div>
            @else
                <ul class="divide-y divide-[var(--color-line)]">
                    @foreach($recentBlogs as $b)
                        <li class="py-3 flex items-start gap-3">
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('admin.blogs.edit', $b->id) }}" class="block text-sm font-semibold text-[var(--color-ink-2)] hover:text-[var(--color-clay-600)] truncate">
                                    {{ $b->title }}
                                </a>
                                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                    @if($b->category_name)
                                        <span class="pill pill-clay">
                                            <i class="bi bi-tag"></i>
                                            {{ $b->category_name }}
                                        </span>
                                    @endif
                                    @if((int) $b->status === 1)
                                        <span class="pill pill-leaf">Published</span>
                                    @else
                                        <span class="pill pill-flame">Draft</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-xs text-[var(--color-mute-2)] shrink-0 mt-1">
                                {{ \Illuminate\Support\Carbon::parse($b->date_created)->format('d M Y') }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="card p-5 lg:p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="font-display text-lg text-[var(--color-ink-2)]">Latest members</h2>
                    <p class="text-xs text-[var(--color-mute)] mt-0.5">Recent membership sign-ups</p>
                </div>
                <a href="{{ route('admin.memberships.index') }}" class="btn btn-ghost btn-sm">
                    <span>View all</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            @if($recentMembers->isEmpty())
                <div class="py-10 text-center">
                    <i class="bi bi-people text-3xl text-[var(--color-mute-2)]"></i>
                    <div class="font-display text-base mt-3">No members yet</div>
                    <div class="text-sm text-[var(--color-mute)] mt-1">New sign-ups will appear here.</div>
                </div>
            @else
                <ul class="divide-y divide-[var(--color-line)]">
                    @foreach($recentMembers as $m)
                        @php
                            $type = $m->type ?: 'Individual';
                            $typeClass = match(strtolower($type)) {
                                'cso/ngo', 'ngo'                 => 'pill-river',
                                'volunteer'                      => 'pill-leaf',
                                'firm/organization', 'firm'      => 'pill-amber',
                                default                          => 'pill-clay',
                            };
                        @endphp
                        <li class="py-3 flex items-start gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-[var(--color-ink-2)] truncate">{{ $m->name }}</div>
                                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                    <span class="pill {{ $typeClass }}">{{ $type }}</span>
                                    <span class="font-mono text-xs text-[var(--color-mute)]">{{ $m->code }}</span>
                                </div>
                            </div>
                            <div class="text-xs text-[var(--color-mute-2)] shrink-0 mt-1">
                                {{ \Illuminate\Support\Carbon::parse($m->date)->format('d M Y') }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection
