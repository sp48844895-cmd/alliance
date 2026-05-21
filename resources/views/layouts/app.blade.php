<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'ChhattisgarhABC · Alliance for Behaviour Change Chhattisgarh')</title>
  <meta name="description" content="@yield('meta_description', 'ChhattisgarhABC is a community platform where youth, professionals, civil society and government come together to share experiences and advance Social and Behaviour Change Communication (SBC) across Chhattisgarh.')" />
  <meta name="keywords" content="Chhattisgarh abc, Alliance for behaviour change, sbc, sbc, behaviour change, behaviour" />
  <meta name="author" content="ChhattisgarhABC" />
  <meta name="theme-color" content="#faf8ff" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght,SOFT,WONK@0,9..144,300..700,30..100,0..1;1,9..144,300..700,30..100,0..1&family=Manrope:wght@400;500;600;700&family=Caveat:wght@500;600&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap-grid.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

  <link rel="stylesheet" href="{{ asset('assets/css/tokens.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}" />
  @if (request()->routeIs('home'))
  <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}?v={{ filemtime(public_path('assets/css/home.css')) }}" />
  @endif
  @if (request()->routeIs('login.*'))
  <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}" />
  @endif
  @if (request()->routeIs('about'))
  <link rel="stylesheet" href="{{ asset('assets/css/about.css') }}?v={{ filemtime(public_path('assets/css/about.css')) }}" />
  @endif
  @if (request()->routeIs('campaigns'))
  <link rel="stylesheet" href="{{ asset('assets/css/campaigns.css') }}?v={{ filemtime(public_path('assets/css/campaigns.css')) }}" />
  @endif
  @if (request()->routeIs('stories*'))
  <link rel="stylesheet" href="{{ asset('assets/css/stories.css') }}?v={{ filemtime(public_path('assets/css/stories.css')) }}" />
  @endif
  @if (request()->routeIs('events'))
  <link rel="stylesheet" href="{{ asset('assets/css/events.css') }}?v={{ filemtime(public_path('assets/css/events.css')) }}" />
  @endif
  @if (request()->routeIs('knowledge-hub'))
  <link rel="stylesheet" href="{{ asset('assets/css/knowledge-hub.css') }}?v={{ filemtime(public_path('assets/css/knowledge-hub.css')) }}" />
  @endif
  @if (request()->routeIs('get-involved'))
  <link rel="stylesheet" href="{{ asset('assets/css/get-involved.css') }}?v={{ filemtime(public_path('assets/css/get-involved.css')) }}" />
  @endif
  @if (request()->routeIs('members'))
  <link rel="stylesheet" href="{{ asset('assets/css/members.css') }}?v={{ filemtime(public_path('assets/css/members.css')) }}" />
  @endif
  @if (request()->routeIs('resources'))
  <link rel="stylesheet" href="{{ asset('assets/css/resources.css') }}?v={{ filemtime(public_path('assets/css/resources.css')) }}" />
  @endif
  @if (request()->routeIs('learning-corner'))
  <link rel="stylesheet" href="{{ asset('assets/css/learning-corner.css') }}?v={{ filemtime(public_path('assets/css/learning-corner.css')) }}" />
  @endif
  @if (request()->routeIs('reports'))
  <link rel="stylesheet" href="{{ asset('assets/css/reports.css') }}?v={{ filemtime(public_path('assets/css/reports.css')) }}" />
  @endif
  @if (request()->routeIs('contact'))
  <link rel="stylesheet" href="{{ asset('assets/css/contact.css') }}?v={{ filemtime(public_path('assets/css/contact.css')) }}" />
  @endif

  @stack('styles')
</head>
<body>

<a href="#main" class="sr-only">Skip to main content</a>

<div class="announce">
  <div class="container-x announce-row">
    <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
      <span class="pill">CONTACT</span>
      <span>{{ $settings['contact_city'] ?? 'Raipur, Chhattisgarh' }} · <a href="mailto:{{ $settings['contact_email'] ?? 'info@chhttisgarhabc.org' }}" style="color:inherit; text-decoration:none;">{{ $settings['contact_email'] ?? 'info@chhttisgarhabc.org' }}</a> · <a href="tel:{{ $settings['contact_phone_raw'] ?? '+919098498822' }}" style="color:inherit; text-decoration:none;">{{ $settings['contact_phone'] ?? '+91 90984 98822' }}</a></span>
    </div>
    <nav class="announce-links social-icons" aria-label="Social links">
      @include('partials.social-icon-links', ['layout' => 'announce'])
    </nav>
  </div>
</div>

<header class="nav" role="banner">
  <div class="container-x nav-row">
    <a class="brand" href="{{ route('home') }}" aria-label="ChhattisgarhABC — Alliance for Behaviour Change, home">
      <img class="brand-logo" src="{{ asset('assets/img/site-logo.png') }}" alt="ChhattisgarhABC — Alliance for Behaviour Change" />
    </a>

    <nav aria-label="Primary">
      <ul class="nav-menu" id="primary-menu">
        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a></li>
        <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'is-active' : '' }}">About</a></li>
        {{--
        <li><a href="{{ route('campaigns') }}" class="{{ request()->routeIs('campaigns') ? 'is-active' : '' }}">Campaigns</a></li>
        --}}
        <li><a href="{{ route('events') }}" class="{{ request()->routeIs('events*') ? 'is-active' : '' }}">Events</a></li>
        <li><a href="{{ route('stories') }}" class="{{ request()->routeIs('stories*') ? 'is-active' : '' }}">Stories</a></li>
        <li class="nav-dropdown">
          <details class="nav-dropdown__details {{ request()->routeIs('knowledge-hub', 'learning-corner', 'reports', 'resources') ? 'nav-dropdown--active' : '' }}">
            <summary class="nav-dropdown__trigger" aria-haspopup="menu">
              <span class="nav-dropdown__label">Knowledge Hub</span>
              <svg class="nav-dropdown__caret" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M6 9l6 6 6-6"/>
              </svg>
            </summary>
            <div class="nav-dropdown__panel" role="menu">
              <a class="nav-dropdown__item" href="{{ route('knowledge-hub') }}?filter=toolkit#kh-search" role="menuitem">
                Program and Initiatives
              </a>
              <a class="nav-dropdown__item" href="{{ route('learning-corner') }}" role="menuitem">
                Learning Corner
              </a>
              <a class="nav-dropdown__item" href="{{ route('reports') }}" role="menuitem">
                Reports and Insights
              </a>
              <a class="nav-dropdown__item" href="{{ route('resources') }}" role="menuitem">
                SBC Resource Pool
              </a>
            </div>
          </details>
        </li>
        <li><a href="{{ route('get-involved') }}" class="{{ request()->routeIs('get-involved') ? 'is-active' : '' }}">Get Involved</a></li>
        <li><a href="{{ route('members') }}" class="{{ request()->routeIs('members') ? 'is-active' : '' }}">Our Members</a></li>

        <li class="nav-login">
          <details class="nav-login__details {{ request()->routeIs('login.*') ? 'nav-login--active' : '' }}">
            <summary class="nav-login__trigger" aria-haspopup="menu">
              Log in
              <svg class="nav-login__caret" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M6 9l6 6 6-6"/>
              </svg>
            </summary>
            <div class="nav-login__panel" role="menu">
              <a class="nav-login__item nav-login__item--admin" href="{{ route('login.show', 'admin') }}" role="menuitem">
                <span class="nav-login__icon" aria-hidden="true">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 8l9 6 9-6"/></svg>
                </span>
                <span class="nav-login__text">
                  <b>Admin</b>
                  <small>Platform administration</small>
                </span>
              </a>
              <a class="nav-login__item" href="{{ route('login.show', 'author') }}" role="menuitem">
                <span class="nav-login__icon" aria-hidden="true">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                </span>
                <span class="nav-login__text">
                  <b>Author</b>
                  <small>Submit stories for review</small>
                </span>
              </a>
              <a class="nav-login__item" href="{{ route('login.show', 'volunteer') }}" role="menuitem">
                <span class="nav-login__icon" aria-hidden="true">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-7 8-7s8 3 8 7"/></svg>
                </span>
                <span class="nav-login__text">
                  <b>Volunteer</b>
                  <small>Field volunteers &amp; youth leaders</small>
                </span>
              </a>
              <a class="nav-login__item" href="{{ route('login.show', 'intern') }}" role="menuitem">
                <span class="nav-login__icon" aria-hidden="true">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 7l2-3h12l2 3M4 7v13h16V7"/><path d="M9 12h6"/></svg>
                </span>
                <span class="nav-login__text">
                  <b>Intern</b>
                  <small>Student &amp; programme interns</small>
                </span>
              </a>
              <a class="nav-login__item" href="{{ route('login.show', 'pro') }}" role="menuitem">
                <span class="nav-login__icon" aria-hidden="true">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h18v13H3z"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </span>
                <span class="nav-login__text">
                  <b>Pro</b>
                  <small>SBC practitioners &amp; consultants</small>
                </span>
              </a>
              <a class="nav-login__item" href="{{ route('login.show', 'ngo') }}" role="menuitem">
                <span class="nav-login__icon" aria-hidden="true">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21V10l9-6 9 6v11"/><path d="M9 21v-7h6v7"/></svg>
                </span>
                <span class="nav-login__text">
                  <b>NGO</b>
                  <small>Civil society &amp; partner orgs</small>
                </span>
              </a>
              <a class="nav-login__item border-t border-[var(--color-line)] mt-1 pt-2" href="{{ route('login') }}" role="menuitem">
                <span class="nav-login__text w-full text-center">
                  <b>All login portals</b>
                </span>
              </a>
            </div>
          </details>
        </li>
      </ul>
    </nav>

    <div class="nav-cta">
      <a class="btn btn-primary" href="{{ route('contact') }}">
        Contact us
        <svg class="arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
      </a>
      <button class="nav-burger" aria-label="Toggle menu" aria-controls="primary-menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>

@unless (request()->routeIs('home'))
  @include('partials.page-jumbotron')
@endunless

@yield('content')

<footer class="footer" role="contentinfo">
  <div class="container-x">
    <div class="footer-grid">
      <div>
        <p class="footer-tag">A community <em>platform</em><br>for behaviour <em>change.</em></p>
        <form class="newsletter" aria-label="Subscribe to ChhattisgarhABC updates" action="{{ route('newsletter.subscribe') }}" method="POST">
          @csrf
          <input type="email" name="email" required placeholder="your@email · monthly digest" aria-label="Email address" />
          <button type="submit">Subscribe</button>
        </form>
        <p style="font-size:13px; opacity:0.6; margin-top:16px; max-width:380px;">Updates from the alliance — stories, events and resources, straight from the field.</p>
      </div>

      <div>
        <h5>Explore</h5>
        <ul>
          <li><a href="{{ route('about') }}">About</a></li>
          <li><a href="{{ route('campaigns') }}">Campaigns</a></li>
          <li><a href="{{ route('events') }}">Events</a></li>
          <li><a href="{{ route('stories') }}">Stories</a></li>
          <li><a href="{{ route('knowledge-hub') }}">Knowledge Hub</a></li>
          <li><a href="{{ route('get-involved') }}">Get Involved</a></li>
          <li><a href="{{ route('members') }}">Members</a></li>
        </ul>
      </div>

      <div>
        <h5>Knowledge Hub</h5>
        <ul>
          <li><a href="{{ route('knowledge-hub') }}?filter=toolkit#kh-search">Program and Initiatives</a></li>
          <li><a href="{{ route('learning-corner') }}">Learning Corner</a></li>
          <li><a href="{{ route('reports') }}">Reports and Insights</a></li>
          <li><a href="{{ route('resources') }}">SBC Resource Pool</a></li>
        </ul>
      </div>

      <div>
        <h5>Reach us</h5>
        <ul>
          <li>{{ $settings['contact_city'] ?? 'Raipur · Chhattisgarh' }}</li>
          <li><a href="mailto:{{ $settings['contact_email'] ?? 'info@chhttisgarhabc.org' }}">{{ $settings['contact_email'] ?? 'info@chhttisgarhabc.org' }}</a></li>
          <li><a href="tel:{{ $settings['contact_phone_raw'] ?? '+919098498822' }}">{{ $settings['contact_phone'] ?? '+91 90984 98822' }}</a></li>
        </ul>
        <h5 style="margin-top:24px;">Follow</h5>
        @include('partials.social-icon-links', ['layout' => 'footer'])
      </div>
    </div>

    <div class="footer-base">
      <span>© <span data-year>{{ date('Y') }}</span> {{ $settings['footer_copyright'] ?? 'ChhattisgarhABC · Alliance for Behaviour Change' }}.</span>
      <span>Designed &amp; developed by {{ $settings['footer_developer'] ?? 'Ingenious Insights' }}.</span>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/countup.js@2.8.0/dist/countUp.umd.js"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
@if (request()->routeIs('home'))
<script src="{{ asset('assets/js/home.js') }}?v={{ filemtime(public_path('assets/js/home.js')) }}"></script>
@endif
@if (request()->routeIs('campaigns'))
<script src="{{ asset('assets/js/campaigns.js') }}?v={{ filemtime(public_path('assets/js/campaigns.js')) }}"></script>
@endif
@if (request()->routeIs('stories*'))
<script src="{{ asset('assets/js/stories.js') }}?v={{ filemtime(public_path('assets/js/stories.js')) }}"></script>
@endif
@if (request()->routeIs('events'))
<script src="{{ asset('assets/js/events.js') }}?v={{ filemtime(public_path('assets/js/events.js')) }}"></script>
@endif
@if (request()->routeIs('knowledge-hub'))
<script src="{{ asset('assets/js/knowledge-hub.js') }}?v={{ filemtime(public_path('assets/js/knowledge-hub.js')) }}"></script>
@endif
@if (request()->routeIs('get-involved'))
<script src="{{ asset('assets/js/get-involved.js') }}?v={{ filemtime(public_path('assets/js/get-involved.js')) }}"></script>
@endif
@if (request()->routeIs('members'))
<script src="{{ asset('assets/js/members.js') }}?v={{ filemtime(public_path('assets/js/members.js')) }}"></script>
@endif
@if (request()->routeIs('learning-corner'))
<script src="{{ asset('assets/js/learning-corner.js') }}?v={{ filemtime(public_path('assets/js/learning-corner.js')) }}"></script>
@endif

@stack('scripts')

</body>
</html>
