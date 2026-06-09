@php
use App\Support\PageRoute;
@endphp
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'ChhattisgarhABC · Alliance for Behaviour Change Chhattisgarh')</title>
  <meta name="description" content="@yield('meta_description', 'ChhattisgarhABC is a community platform where youth, professionals, civil society and government come together to share experiences and advance Social and Behaviour Change Communication (SBC) across Chhattisgarh.')" />
  <x-social-meta :meta="$socialMeta" />
  <meta name="keywords" content="Chhattisgarh abc, Alliance for behaviour change, sbc, sbc, behaviour change, behaviour" />
  <meta name="author" content="ChhattisgarhABC" />
  <meta name="theme-color" content="#faf8ff" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,400;1,500;1,700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap-grid.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

  <link rel="stylesheet" href="{{ asset('assets/css/tokens.css') }}?v={{ filemtime(public_path('assets/css/tokens.css')) }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}?v={{ filemtime(public_path('assets/css/base.css')) }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}?v={{ filemtime(public_path('assets/css/components.css')) }}" />
  @if (PageRoute::is('home'))
  <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}?v={{ filemtime(public_path('assets/css/home.css')) }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/investcg-slider.css') }}?v={{ filemtime(public_path('assets/css/investcg-slider.css')) }}" />
  @endif
  @if (PageRoute::is('login', 'login.*'))
  <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}" />
  @endif
  @if (PageRoute::is('about'))
  <link rel="stylesheet" href="{{ asset('assets/css/about.css') }}?v={{ filemtime(public_path('assets/css/about.css')) }}" />
  @endif
  @if (PageRoute::is('campaigns'))
  <link rel="stylesheet" href="{{ asset('assets/css/campaigns.css') }}?v={{ filemtime(public_path('assets/css/campaigns.css')) }}" />
  @endif
  @if (PageRoute::is('stories*'))
  <link rel="stylesheet" href="{{ asset('assets/css/stories.css') }}?v={{ filemtime(public_path('assets/css/stories.css')) }}" />
  @endif
  @if (PageRoute::is('events'))
  <link rel="stylesheet" href="{{ asset('assets/css/events.css') }}?v={{ filemtime(public_path('assets/css/events.css')) }}" />
  @endif
  @if (PageRoute::is('programs'))
  <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}?v={{ filemtime(public_path('assets/css/home.css')) }}" />
  @endif
  @if (PageRoute::is('get-involved'))
  <link rel="stylesheet" href="{{ asset('assets/css/get-involved.css') }}?v={{ filemtime(public_path('assets/css/get-involved.css')) }}" />
  @endif
  @if (PageRoute::is('members'))
  <link rel="stylesheet" href="{{ asset('assets/css/members.css') }}?v={{ filemtime(public_path('assets/css/members.css')) }}" />
  @endif
  @if (PageRoute::is('resources'))
  <link rel="stylesheet" href="{{ asset('assets/css/resources.css') }}?v={{ filemtime(public_path('assets/css/resources.css')) }}" />
  @endif
  @if (PageRoute::is('learning-corner', 'learning-corner.main', 'learning-corner.sub'))
  <link rel="stylesheet" href="{{ asset('assets/css/learning-corner.css') }}?v={{ filemtime(public_path('assets/css/learning-corner.css')) }}" />
  @endif
  @if (PageRoute::is('reports'))
  <link rel="stylesheet" href="{{ asset('assets/css/reports.css') }}?v={{ filemtime(public_path('assets/css/reports.css')) }}" />
  @endif
  @if (PageRoute::is('contact'))
  <link rel="stylesheet" href="{{ asset('assets/css/contact.css') }}?v={{ filemtime(public_path('assets/css/contact.css')) }}" />
  @endif
  @if (PageRoute::is('register.*'))
  <link rel="stylesheet" href="{{ asset('assets/css/contact.css') }}?v={{ filemtime(public_path('assets/css/contact.css')) }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}?v={{ filemtime(public_path('assets/css/register.css')) }}" />
  @endif

  @stack('styles')
</head>
<body>

<a href="#main" class="sr-only">Skip to main content</a>

<div class="announce">
  <div class="container-x announce-row">
    <div class="announce-contact">
      <a href="mailto:{{ $settings['contact_email'] ?? 'info@chhttisgarhabc.org' }}">{{ $settings['contact_email'] ?? 'info@chhttisgarhabc.org' }}</a>
    </div>
    <nav class="announce-links social-icons" aria-label="Social links">
      @include('partials.social-icon-links', ['layout' => 'announce'])
    </nav>
  </div>
</div>

<header class="nav" role="banner">
  <div class="container-x nav-row">
    <a class="brand" href="{{ route(PageRoute::named('home')) }}" aria-label="ChhattisgarhABC — Alliance for Behaviour Change, home">
      <img class="brand-logo" src="{{ asset('assets/img/site-logo.png') }}" alt="ChhattisgarhABC — Alliance for Behaviour Change" />
    </a>

    <nav aria-label="Primary">
      <ul class="nav-menu" id="primary-menu">
        <li><a href="{{ route(PageRoute::named('home')) }}" class="{{ PageRoute::is('home') ? 'is-active' : '' }}">Home</a></li>
        <li><a href="{{ route(PageRoute::named('about')) }}" class="{{ PageRoute::is('about') ? 'is-active' : '' }}">About</a></li>
        {{--
        <li><a href="{{ route(PageRoute::named('campaigns')) }}" class="{{ PageRoute::is('campaigns') ? 'is-active' : '' }}">Campaigns</a></li>
        --}}
        <li><a href="{{ route(PageRoute::named('events')) }}" class="{{ PageRoute::is('events*') ? 'is-active' : '' }}">Events</a></li>
        <li><a href="{{ route(PageRoute::named('stories')) }}" class="{{ PageRoute::is('stories*') ? 'is-active' : '' }}">Stories</a></li>
        <li class="nav-dropdown">
          <details class="nav-dropdown__details {{ PageRoute::is('learning-corner', 'learning-corner.main', 'learning-corner.sub', 'reports', 'resources', 'programs') ? 'nav-dropdown--active' : '' }}">
            <summary class="nav-dropdown__trigger" aria-haspopup="menu">
              <span class="nav-dropdown__label">Knowledge Hub</span>
              <svg class="nav-dropdown__caret" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M6 9l6 6 6-6"/>
              </svg>
            </summary>
            <div class="nav-dropdown__panel" role="menu">
              <a class="nav-dropdown__item" href="{{ route(PageRoute::named('learning-corner')) }}" role="menuitem">
                Learning Corner
              </a>
              <a class="nav-dropdown__item" href="{{ route(PageRoute::named('resources')) }}" role="menuitem">
                SBC Resource Pool
              </a>
              <a class="nav-dropdown__item" href="{{ route(PageRoute::named('programs')) }}" role="menuitem">
                Programs and Initiatives
              </a>
              <a class="nav-dropdown__item" href="{{ route(PageRoute::named('reports')) }}" role="menuitem">
                Reports and Insights
              </a>
            </div>
          </details>
        </li>
        <li><a href="{{ route(PageRoute::named('get-involved')) }}" class="{{ PageRoute::is('get-involved') ? 'is-active' : '' }}">Get Involved</a></li>
        <li><a href="{{ route(PageRoute::named('members')) }}" class="{{ PageRoute::is('members') ? 'is-active' : '' }}">Our Members</a></li>

        <li class="nav-login">
          <details class="nav-login__details {{ PageRoute::is('login', 'login.show', 'login.attempt') ? 'nav-login--active' : '' }}">
            <summary class="nav-login__trigger" aria-haspopup="menu">
              Log in
              <svg class="nav-login__caret" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M6 9l6 6 6-6"/>
              </svg>
            </summary>
            <div class="nav-login__panel" role="menu">
              @foreach ($loginPortals ?? [] as $portal)
                <a class="nav-login__item {{ $portal['is_admin'] ? 'nav-login__item--admin' : '' }}" href="{{ route('login.show', $portal['slug']) }}" role="menuitem">
                  <span class="nav-login__text">
                    <b>{{ $portal['label'] }}</b>
                    <small>{{ $portal['subtitle'] }}</small>
                  </span>
                </a>
              @endforeach
            </div>
          </details>
        </li>
      </ul>
    </nav>

    <div class="nav-cta">
      <a class="btn btn-primary" href="{{ route(PageRoute::named('contact')) }}">
        Contact us
        <svg class="arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
      </a>
      <button class="nav-burger" aria-label="Toggle menu" aria-controls="primary-menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>

@unless (PageRoute::is('home', 'events.show', 'stories.show', 'get-involved', 'learning-corner', 'learning-corner.main', 'learning-corner.sub'))
  @include('partials.page-jumbotron')
@endunless

@yield('content')

<footer class="footer" role="contentinfo">
  <div class="container-x">
    <div class="footer-grid">
      <div>
        <p class="footer-tag">A community <em>platform</em><br>for behaviour <em>change.</em></p>
        <form class="newsletter" aria-label="Subscribe to ChhattisgarhABC updates" action="{{ route(PageRoute::named('newsletter.subscribe')) }}" method="POST">
          @csrf
          <input type="email" name="email" required placeholder="your@email · monthly digest" aria-label="Email address" />
          <button type="submit">Subscribe</button>
        </form>
        <p class="footer-digest type-caption">Updates from the alliance — stories, events and resources, straight from the field.</p>
      </div>

      <div>
        <h5>Explore</h5>
        <ul>
          <li><a href="{{ route(PageRoute::named('about')) }}">About</a></li>
          <li><a href="{{ route(PageRoute::named('campaigns')) }}">Campaigns</a></li>
          <li><a href="{{ route(PageRoute::named('events')) }}">Events</a></li>
          <li><a href="{{ route(PageRoute::named('stories')) }}">Stories</a></li>
          <li><a href="{{ route(PageRoute::named('get-involved')) }}">Get Involved</a></li>
          <li><a href="{{ route(PageRoute::named('members')) }}">Members</a></li>
        </ul>
      </div>

      <div>
        <h5>Knowledge Hub</h5>
        <ul>
          <li><a href="{{ route(PageRoute::named('learning-corner')) }}">Learning Corner</a></li>
          <li><a href="{{ route(PageRoute::named('resources')) }}">SBC Resource Pool</a></li>
          <li><a href="{{ route(PageRoute::named('programs')) }}">Programs and Initiatives</a></li>
          <li><a href="{{ route(PageRoute::named('reports')) }}">Reports and Insights</a></li>
        </ul>
      </div>

      <div>
        <h5>Reach us</h5>
        <ul>
          <li>{{ $settings['contact_city'] ?? 'Raipur · Chhattisgarh' }}</li>
          <li><a href="mailto:{{ $settings['contact_email'] ?? 'info@chhttisgarhabc.org' }}">{{ $settings['contact_email'] ?? 'info@chhttisgarhabc.org' }}</a></li>
        </ul>
        <h5 class="footer-follow-heading">Follow</h5>
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
@if (PageRoute::is('home', 'programs'))
<script src="{{ asset('assets/js/home.js') }}?v={{ filemtime(public_path('assets/js/home.js')) }}"></script>
@endif
@if (PageRoute::is('campaigns'))
<script src="{{ asset('assets/js/campaigns.js') }}?v={{ filemtime(public_path('assets/js/campaigns.js')) }}"></script>
@endif
@if (PageRoute::is('stories*'))
<script src="{{ asset('assets/js/stories.js') }}?v={{ filemtime(public_path('assets/js/stories.js')) }}"></script>
@endif
@if (PageRoute::is('events'))
<script src="{{ asset('assets/js/events.js') }}?v={{ filemtime(public_path('assets/js/events.js')) }}"></script>
@endif
@if (PageRoute::is('get-involved'))
<script src="{{ asset('assets/js/get-involved.js') }}?v={{ filemtime(public_path('assets/js/get-involved.js')) }}"></script>
@endif
@if (PageRoute::is('members'))
<script src="{{ asset('assets/js/members.js') }}?v={{ filemtime(public_path('assets/js/members.js')) }}"></script>
@endif
@if (PageRoute::is('learning-corner', 'learning-corner.main', 'learning-corner.sub'))
<script src="https://unpkg.com/lucide@latest"></script>
<script src="{{ asset('assets/js/lucide-icons.js') }}?v={{ filemtime(public_path('assets/js/lucide-icons.js')) }}"></script>
@endif

@stack('scripts')

</body>
</html>
