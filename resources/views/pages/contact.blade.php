@extends('layouts.app')

@section('title', $pageContent['meta_title'] ?? 'Contact Us · ChhattisgarhABC')
@section('meta_description', $pageContent['meta_description'] ?? 'Contact ChhattisgarhABC in Raipur, Chhattisgarh for partnerships, volunteering, resources, events and social behaviour change communication collaboration.')

@php
    $pathwaySubjects = [
        'volunteer' => 'Volunteer registration — ChhattisgarhABC',
        'intern' => 'Intern application — ChhattisgarhABC',
        'fellow' => 'Fellowship application — ChhattisgarhABC',
        'partner' => 'Organisation partnership — ChhattisgarhABC',
    ];
    $activePathway = old('pathway', request('pathway'));
    $defaultSubject = $pathwaySubjects[$activePathway] ?? old('subject', '');
    $requiresAccount = in_array($activePathway, ['volunteer', 'partner'], true);
@endphp

@section('content')
<main id="main">
  {{--
  <section class="contact-hero" aria-labelledby="contact-hero-h">
    <div class="container-x">
      <div class="contact-hero-grid">
        <div class="contact-hero-copy">
          <span class="chapter fade-up" data-delay="1"><b>09</b> · Contact</span>
          <h2 id="contact-hero-h" class="contact-title fade-up" data-delay="2">
            Tell us what you are building. <em>We will help you find the right door.</em>
          </h2>
          <p class="contact-lede fade-up" data-delay="3">
            ChhattisgarhABC connects youth, professionals, civil society, government and community teams working on social and behaviour change communication. Send a message for partnerships, volunteering, resource support or event collaboration.
          </p>

          <div class="contact-actions fade-up" data-delay="4">
            <a class="btn btn-primary" href="#contact-form">
              Send a message
              <svg class="arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
            </a>
            <a class="btn btn-ghost" href="tel:+919098498822">Call the team</a>
          </div>
        </div>

        <aside class="contact-note fade-up" data-delay="3" aria-label="Contact summary">
          <span class="contact-note-kicker">Raipur coordination desk</span>
          <p>Use the form for detailed requests. For urgent coordination, call the team directly.</p>
          <div class="contact-note-links">
            <a href="mailto:info@chhttisgarhabc.org">info@chhttisgarhabc.org</a>
            <a href="tel:+919098498822">+91 90984 98822</a>
          </div>
          <div class="contact-social">
            @include('partials.social-icon-links')
          </div>
        </aside>
      </div>
    </div>
  </section>
  --}}

  <section class="contact-main" aria-labelledby="contact-form-h">
    <div class="container-x">
      <div class="contact-main-grid">
        <div class="contact-form-card" data-aos="fade-up">
          <span class="chapter"><b>{{ $pageSections['contact_form']['chapter'] ?? '01' }}</b> · Message</span>
          <h2 id="contact-form-h">
            @if($activePathway === 'volunteer')
              Volunteer <em>registration</em>
            @elseif($activePathway === 'partner')
              Organisation <em>partnership</em>
            @else
              {{ $pageSections['contact_form']['title'] ?? 'Send a message to the alliance.' }}
            @endif
          </h2>
          <p>
            @if($requiresAccount)
              Create your account and send your application in one step. Choose a password (at least 8 characters) to sign in after the team approves your registration.
            @else
              {{ $pageSections['contact_form']['description'] ?? 'Your email address will not be published. Share a few details and the team will respond on email or phone.' }}
            @endif
          </p>

          <form id="contact-form" class="contact-form" action="{{ route('contact.submit') }}" method="POST">
            @csrf
            @if($activePathway)
              <input type="hidden" name="pathway" value="{{ $activePathway }}">
            @endif
            <div class="contact-field-group">
              <label for="contact-name">Full name</label>
              <input id="contact-name" name="name" type="text" placeholder="Your name" autocomplete="name" maxlength="120" value="{{ old('name') }}" required>
            </div>

            <div class="contact-field-group">
              <label for="contact-email">Email address</label>
              <input id="contact-email" name="email" type="email" placeholder="your@email.com" autocomplete="email" maxlength="150" value="{{ old('email') }}" required>
            </div>

            <div class="contact-field-group">
              <label for="contact-phone">Phone</label>
              <input id="contact-phone" name="phone" type="tel" placeholder="+91 98765 43210" autocomplete="tel" maxlength="20" value="{{ old('phone') }}" required>
            </div>

            @if($requiresAccount)
            <div class="contact-field-group">
              <label for="contact-password">Password</label>
              <input id="contact-password" name="password" type="password" placeholder="At least 8 characters" autocomplete="new-password" minlength="8" required>
            </div>

            <div class="contact-field-group">
              <label for="contact-password-confirm">Confirm password</label>
              <input id="contact-password-confirm" name="password_confirmation" type="password" placeholder="Repeat password" autocomplete="new-password" minlength="8" required>
            </div>
            @endif

            <div class="contact-field-group contact-field-group--wide">
              <label for="contact-subject">Subject</label>
              <input id="contact-subject" name="subject" type="text" placeholder="Partnership, volunteering, resources..." maxlength="160" value="{{ old('subject', $defaultSubject) }}" required>
            </div>

            <div class="contact-field-group contact-field-group--wide">
              <label for="contact-message">Message</label>
              <textarea id="contact-message" name="message" placeholder="Write your message" rows="6" maxlength="1200" required>{{ old('message') }}</textarea>
            </div>

            <button class="btn btn-primary" type="submit">
              {{ $requiresAccount ? 'Submit registration' : 'Send message' }}
              <svg class="arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
            </button>
          </form>
        </div>

        <aside class="contact-aside" data-aos="fade-up" data-aos-delay="120" aria-label="Contact details">
          @foreach ($pageSections['contact_cards']['cards'] ?? [] as $card)
            <article class="contact-card">
              <span class="contact-card-icon" aria-hidden="true">
                @if ($card['icon'] === 'mail')
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
                @elseif ($card['icon'] === 'phone')
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07A19.5 19.5 0 0 1 5.15 12 19.8 19.8 0 0 1 2.08 3.36 2 2 0 0 1 4.06 1h3a2 2 0 0 1 2 1.72c.13.96.35 1.9.66 2.8a2 2 0 0 1-.45 2.11L8 8.9a16 16 0 0 0 7.1 7.1l1.27-1.27a2 2 0 0 1 2.11-.45c.9.31 1.84.53 2.8.66A2 2 0 0 1 22 16.92Z"/></svg>
                @else
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                @endif
              </span>
              <span class="contact-card-label">{{ $card['label'] }}</span>
              <a class="contact-card-value" href="{{ $card['href'] }}" @if ($card['icon'] === 'map') target="_blank" rel="noopener" @endif>{{ $card['value'] }}</a>
              <p>{{ $card['note'] }}</p>
            </article>
          @endforeach
        </aside>
      </div>
    </div>
  </section>

  <section class="contact-map-section" aria-labelledby="contact-map-h">
    <div class="container-x">
      <div class="contact-map-grid">
        <div class="contact-map-copy" data-aos="fade-up">
          <span class="chapter"><b>{{ $pageSections['contact_map']['chapter'] ?? '03' }}</b> · Location</span>
          <h2 id="contact-map-h">{{ $pageSections['contact_map']['title'] ?? 'Based in Raipur, connected across Chhattisgarh.' }}</h2>
          <p>{{ $pageSections['contact_map']['description'] ?? 'The alliance works with local members, partners and community networks across districts. Use the map to locate Raipur or open it in Google Maps.' }}</p>
          <a class="btn btn-ghost" href="{{ $pageSections['contact_map']['map_url'] ?? 'https://www.google.com/maps/search/?api=1&query=Raipur%2C%20Chhattisgarh' }}" target="_blank" rel="noopener">{{ $pageSections['contact_map']['button_text'] ?? 'Open map' }}</a>
        </div>

        <div class="contact-map-card" data-aos="fade-up" data-aos-delay="120">
          <iframe title="Map showing Raipur, Chhattisgarh" src="{{ $pageSections['contact_map']['iframe_src'] ?? 'https://www.google.com/maps?q=Raipur%2C%20Chhattisgarh&output=embed' }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>
    </div>
  </section>
</main>

@if (session('contact_toast'))
  <div class="site-toast site-toast--{{ session('contact_toast.type') }}" id="site-toast" role="status" aria-live="polite">
    <div class="site-toast__icon" aria-hidden="true">
      @if(session('contact_toast.type') === 'success')
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4 12 14.01l-3-3"/></svg>
      @else
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
      @endif
    </div>
    <div class="site-toast__body">
      <p class="site-toast__title">{{ session('contact_toast.type') === 'success' ? 'Message sent' : 'Notice' }}</p>
      <p class="site-toast__message">{{ session('contact_toast.message') }}</p>
    </div>
    <button type="button" class="site-toast__close" data-toast-close aria-label="Close notification">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
  </div>
@endif

@if ($errors->any())
  <div class="site-toast site-toast--error" id="site-toast" role="alert" aria-live="assertive">
    <div class="site-toast__icon" aria-hidden="true">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
    </div>
    <div class="site-toast__body">
      <p class="site-toast__title">Could not send message</p>
      <p class="site-toast__message">Please check the form and try again.</p>
      <ul class="site-toast__errors">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    <button type="button" class="site-toast__close" data-toast-close aria-label="Close notification">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
  </div>
@endif

@push('scripts')
<script src="{{ asset('assets/js/contact.js') }}?v={{ filemtime(public_path('assets/js/contact.js')) }}"></script>
@endpush
@endsection
