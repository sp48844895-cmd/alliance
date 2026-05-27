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
            <p>Tell us about your background, areas of work and why you want to join the fellowship programme.</p>

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
                    <label for="fellow-org">Organization / university <span class="reg-optional">(if any)</span></label>
                    <input id="fellow-org" name="organization" type="text" placeholder="Current employer or institution" maxlength="255" value="{{ old('organization') }}">
                </div>

                <div class="contact-field-group">
                    <label for="fellow-experience">Years of experience</label>
                    <input id="fellow-experience" name="years_experience" type="text" placeholder="e.g. 3 years" maxlength="50" value="{{ old('years_experience') }}" required>
                </div>

                <div class="contact-field-group contact-field-group--wide reg-domain-field">
                    <fieldset>
                        <legend>Domain area <span class="reg-domain-hint">(select all that apply)</span></legend>
                        @php $oldDomains = old('domain_areas', []); @endphp
                        <div class="reg-domain-grid">
                            @foreach ($domainAreas as $area)
                                <label class="reg-domain-check">
                                    <input type="checkbox" name="domain_areas[]" value="{{ $area }}" @checked(in_array($area, $oldDomains, true))>
                                    <span class="reg-domain-mark" aria-hidden="true">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    </span>
                                    <span>{{ $area }}</span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
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
                <li>Select every domain where you have meaningful experience.</li>
                <li>Choose a password now — you will use it to sign in after we approve your application.</li>
                <li>After approval, sign in at <a href="{{ route('login.show', 'fellow') }}">fellowship login</a>.</li>
                <li>Organization / university is optional if you are applying independently.</li>
                <li>We respond within 3 working days when your profile matches an open cohort.</li>
            </ul>
            <p style="margin-top: var(--s-4);">Questions? <a href="{{ route('contact') }}">Contact the alliance</a>.</p>
        </aside>
    </div>
</main>

@include('pages.partials.register.toast')
@endsection

@push('scripts')
<script src="{{ asset('assets/js/register.js') }}?v={{ filemtime(public_path('assets/js/register.js')) }}"></script>
@endpush
