@extends('layouts.app')

@section('title', 'Guest Registration · ChhattisgarhABC')
@section('meta_description', 'Register as a guest with ChhattisgarhABC — support field outreach, events and community learning across Chhattisgarh.')

@section('content')
<main id="main" class="reg-page">
    <div class="container-x reg-page__grid">
        <div class="reg-form-card" data-aos="fade-up">
            <a class="reg-back" href="{{ route('get-involved') }}#gi-guest">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Back to Get Involved
            </a>
            <span class="chapter"><b>09</b> · Guest</span>
            <h2 id="reg-guest-h">Guest <em>registration</em></h2>
            <p>Create your account and tell us why you want to join. The team will review your application and activate your login.</p>

            <form id="register-form" class="contact-form reg-form" action="{{ route('register.guest.submit') }}" method="POST" data-tone="ochre">
                @csrf
                <div class="contact-field-group">
                    <label for="guest-name">Full name</label>
                    <input id="guest-name" name="full_name" type="text" placeholder="Your full name" autocomplete="name" maxlength="120" value="{{ old('full_name') }}" required>
                </div>

                <div class="contact-field-group">
                    <label for="guest-email">Email</label>
                    <input id="guest-email" name="email" type="email" placeholder="your@email.com" autocomplete="email" maxlength="150" value="{{ old('email') }}" required>
                </div>

                <div class="contact-field-group">
                    <label for="guest-phone">Phone number</label>
                    <input id="guest-phone" name="phone" type="tel" placeholder="+91 98765 43210" autocomplete="tel" maxlength="20" value="{{ old('phone') }}" required>
                </div>

                <div class="contact-field-group">
                    <label for="guest-password">Password</label>
                    <input id="guest-password" name="password" type="password" placeholder="At least 8 characters" autocomplete="new-password" minlength="8" required>
                </div>

                <div class="contact-field-group">
                    <label for="guest-password-confirm">Confirm password</label>
                    <input id="guest-password-confirm" name="password_confirmation" type="password" placeholder="Repeat password" autocomplete="new-password" minlength="8" required>
                </div>

                <div class="contact-field-group contact-field-group--wide">
                    <label for="guest-motivation">Why do you want to join?</label>
                    <textarea id="guest-motivation" name="motivation" rows="6" placeholder="Share your interest and how you would like to contribute" maxlength="5000" required>{{ old('motivation') }}</textarea>
                </div>

                <button class="btn btn-primary" type="submit">
                    Submit registration
                    <svg class="arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                </button>
            </form>
        </div>
    </div>
</main>

@include('registrations.partials.toast')
@endsection

@push('scripts')
<script src="{{ asset('assets/js/register.js') }}?v={{ filemtime(public_path('assets/js/register.js')) }}"></script>
@endpush
