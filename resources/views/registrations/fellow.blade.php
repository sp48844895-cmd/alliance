@extends('layouts.app')

@section('title', 'Fellowship Registration · ChhattisgarhABC')
@section('meta_description', 'Apply for a ChhattisgarhABC fellowship — share your experience, domain interests and motivation to deepen SBC practice with the alliance.')

@section('content')
<main id="main" class="reg-page">
    <div class="container-x reg-page__grid">
        <div class="reg-form-card" data-aos="fade-up">
            <a class="reg-back" href="{{ route('get-involved') }}#gi-fellow">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Back to Get Involved
            </a>
            <span class="chapter"><b>10</b> · Fellowship</span>
            <h2 id="reg-fellow-h">Fellowship <em>registration</em></h2>
            <p>Share your academic details, preferred domain and a short note on why you want to join the fellowship programme.</p>

            <form id="register-form" class="contact-form reg-form" action="{{ route('register.fellow.submit') }}" method="POST" data-tone="terracotta">
                @csrf
                <div class="contact-field-group">
                    <label for="fellow-name">Full name</label>
                    <input id="fellow-name" name="full_name" type="text" placeholder="Your full name" autocomplete="name" maxlength="120" value="{{ old('full_name') }}" required>
                </div>

                <div class="contact-field-group">
                    <label for="fellow-email">Email</label>
                    <input id="fellow-email" name="email" type="email" placeholder="your@email.com" autocomplete="email" maxlength="150" value="{{ old('email') }}" required>
                </div>

                <div class="contact-field-group">
                    <label for="fellow-phone">Phone number</label>
                    <input id="fellow-phone" name="phone" type="tel" placeholder="+91 98765 43210" autocomplete="tel" maxlength="20" value="{{ old('phone') }}" required>
                </div>

                <div class="contact-field-group">
                    <label for="fellow-password">Password</label>
                    <input id="fellow-password" name="password" type="password" placeholder="At least 8 characters" autocomplete="new-password" minlength="8" required>
                </div>

                <div class="contact-field-group">
                    <label for="fellow-password-confirm">Confirm password</label>
                    <input id="fellow-password-confirm" name="password_confirmation" type="password" placeholder="Repeat password" autocomplete="new-password" minlength="8" required>
                </div>

                <div class="contact-field-group contact-field-group--wide">
                    <label for="fellow-university">University / college name</label>
                    <input id="fellow-university" name="university" type="text" placeholder="Institution name" maxlength="255" value="{{ old('university') }}" required>
                </div>

                <div class="contact-field-group">
                    <label for="fellow-class">Current class / year</label>
                    <input id="fellow-class" name="class_year" type="text" placeholder="e.g. 2nd year BA, Final year MSc" maxlength="100" value="{{ old('class_year') }}" required>
                </div>

                <div class="contact-field-group">
                    <label for="fellow-domain">Domain area</label>
                    <select id="fellow-domain" name="domain_area" required>
                        <option value="" disabled {{ old('domain_area') ? '' : 'selected' }}>Select one domain</option>
                        @foreach ($domainAreas as $area)
                            <option value="{{ $area }}" @selected(old('domain_area') === $area)>{{ $area }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="contact-field-group contact-field-group--wide">
                    <label for="fellow-motivation">Brief motivation</label>
                    <textarea id="fellow-motivation" name="motivation" rows="6" placeholder="Why do you want to join the fellowship?" maxlength="5000" required>{{ old('motivation') }}</textarea>
                </div>

                <div class="contact-field-group contact-field-group--wide">
                    <button class="btn btn-primary" type="submit">
                        Submit application
                        <svg class="arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </form>
        </div>

        <aside class="reg-aside" data-aos="fade-up" data-aos-delay="100" aria-label="Fellowship information">
            <span class="chapter"><b>Notes</b> · Before you apply</span>
            <h3>What happens next</h3>
            <p>Fellowship applications are reviewed for fit with ongoing programmes and district priorities.</p>
            <ul>
                <li>Use an email you check regularly.</li>
                <li>Choose a password now — you will use it to sign in after we approve your application.</li>
                <li>After approval, sign in at <a href="{{ route('login.show', 'fellow') }}">fellowship login</a>.</li>
                <li>Pick the domain that best matches your skills and interest.</li>
                <li>Keep your motivation note specific and under 500 words.</li>
            </ul>
            <p style="margin-top: var(--s-4);">Questions? <a href="{{ route('contact') }}">Contact the alliance</a>.</p>
        </aside>
    </div>
</main>

@include('registrations.partials.toast')
@endsection

@push('scripts')
<script src="{{ asset('assets/js/register.js') }}?v={{ filemtime(public_path('assets/js/register.js')) }}"></script>
@endpush
