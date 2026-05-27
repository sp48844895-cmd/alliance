@extends('layouts.auth')

@section('title', $config['label'] . ' sign in')

@section('content')
<div class="min-h-screen grid lg:grid-cols-2">

    <div class="hidden lg:flex relative overflow-hidden bg-[var(--color-paper-2)]">
        <div class="relative z-10 flex flex-col justify-between p-10 xl:p-14 w-full">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[var(--color-clay-500)] flex items-center justify-center">
                    <span class="font-display text-white text-xl leading-none">A</span>
                </div>
                <div>
                    <div class="font-display text-lg leading-tight text-[var(--color-ink-2)] font-medium">Alliance for Behavior Change</div>
                    <div class="text-xs uppercase tracking-[0.2em] text-[var(--color-mute-2)]">Chhattisgarh</div>
                </div>
            </div>

            <div class="max-w-md">
                <p class="text-xs uppercase tracking-[0.25em] text-[var(--color-clay-500)] font-semibold mb-4">{{ $config['chapter'] }}</p>
                <h1 class="font-display text-4xl xl:text-5xl leading-[1.05] text-[var(--color-ink-2)] mb-5">{{ $config['headline'] }}</h1>
                <p class="text-[var(--color-mute)] text-base leading-relaxed">{{ $config['lede'] }}</p>
            </div>

            <div class="text-xs text-[var(--color-mute-2)]">
                A network for social and behavior change communication, organising volunteers and partners across 33 districts of Chhattisgarh.
            </div>
        </div>

        <div class="absolute -top-32 -right-32 w-[480px] h-[480px] rounded-full bg-[var(--color-clay-100)] opacity-60 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-20 w-[420px] h-[420px] rounded-full bg-[var(--color-river-soft)] opacity-70 blur-3xl"></div>
    </div>

    <div class="flex items-center justify-center p-6 lg:p-10">
        <div class="w-full max-w-lg">
            <div class="lg:hidden flex items-center gap-3 mb-8">
                <div class="w-9 h-9 rounded-lg bg-[var(--color-clay-500)] flex items-center justify-center">
                    <span class="font-display text-white text-lg leading-none">A</span>
                </div>
                <div>
                    <div class="font-display text-sm leading-tight font-medium">Alliance for Behavior Change</div>
                    <div class="text-xs uppercase tracking-[0.2em] text-[var(--color-mute-2)]">Chhattisgarh</div>
                </div>
            </div>

            <div class="mb-7">
                <p class="text-xs uppercase tracking-[0.25em] text-[var(--color-clay-500)] font-semibold mb-2">Sign in</p>
                <h2 class="font-display text-3xl text-[var(--color-ink-2)]">{{ $config['label'] }} access</h2>
                <p class="text-sm text-[var(--color-mute)] mt-2">Choose your role and continue.</p>
            </div>

            <div class="mb-6">
                <p class="label !mb-2">Sign in as</p>
                <div class="rounded-xl border border-[var(--color-line)] bg-[var(--color-paper-2)] p-1.5">
                    <div class="grid grid-cols-3 gap-1 sm:grid-cols-5" role="tablist" aria-label="Choose login role">
                        @foreach($portals as $portal)
                            <a href="{{ route('login.show', $portal['slug']) }}"
                               role="tab"
                               @if($type === $portal['type']) aria-selected="true" @else aria-selected="false" @endif
                               class="flex items-center justify-center min-h-10 px-2 py-2 rounded-lg text-xs sm:text-sm font-medium leading-tight text-center transition {{ $type === $portal['type'] ? 'bg-white text-[var(--color-clay-700)] shadow-sm ring-1 ring-[var(--color-line)]' : 'bg-white/40 text-[var(--color-mute)] hover:bg-white hover:text-[var(--color-ink-2)] ring-1 ring-transparent hover:ring-[var(--color-line)]' }}"
                               title="{{ $portal['label'] }}">
                                {{ $portal['switcher_label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            @if(session('status'))
                <div class="alert alert-success mb-4 text-sm">
                    <i class="bi bi-info-circle"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form id="login-form" method="POST" action="{{ route('login.attempt', $typeSlug) }}" class="space-y-4">
                @csrf

                <div>
                    <label class="label" for="identifier">{{ $config['idLabel'] }}</label>
                    <input
                        id="identifier"
                        type="{{ $config['idType'] }}"
                        name="{{ $config['identifier'] }}"
                        value="{{ old($config['identifier']) }}"
                        class="input"
                        autocomplete="username"
                        autofocus
                        required
                    >
                    @error($config['identifier'])
                        <p class="err">{{ $message }}</p>
                    @enderror
                    @error('email')
                        <p class="err">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="label !mb-0" for="password">Password</label>
                        <a href="#" class="text-xs text-[var(--color-mute)] hover:text-[var(--color-clay-500)]">Forgot?</a>
                    </div>
                    <input id="password" type="password" name="password" class="input" autocomplete="current-password" required minlength="8">
                    @error('password')
                        <p class="err">{{ $message }}</p>
                    @enderror
                </div>

                @if($turnstileEnabled || $turnstileMisconfigured)
                    <div>
                        <label class="label">Security check</label>
                        @if($turnstileEnabled)
                            <div class="cf-turnstile" data-sitekey="{{ $turnstileSiteKey }}" data-theme="auto"></div>
                        @else
                            <p class="text-sm text-[var(--color-flame)]">
                                Turnstile is enabled but keys are missing. Set TURNSTILE_SITE_KEY and TURNSTILE_SECRET_KEY in .env.
                            </p>
                        @endif
                        @error('cf-turnstile-response')
                            <p class="err">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <label class="flex items-center gap-2 text-xs text-[var(--color-mute)]">
                    <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} class="rounded border-[var(--color-line-2)] text-[var(--color-clay-500)] focus:ring-[var(--color-clay-500)]">
                    Keep me signed in for 30 days on this device
                </label>

                <button type="submit" class="btn btn-primary w-full !py-3" @if($turnstileMisconfigured) disabled @endif>
                    Continue as {{ $config['label'] }}
                    <i class="bi bi-arrow-right text-base"></i>
                </button>

                <p class="text-center text-xs text-[var(--color-mute-2)]">
                    <a href="{{ route('login') }}" class="text-[var(--color-clay-500)] font-semibold hover:underline">All login portals</a>
                    · <a href="{{ route('home') }}" class="hover:underline">Public site</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection

@if($turnstileEnabled)
    @push('head')
        <link rel="preconnect" href="https://challenges.cloudflare.com" />
    @endpush
    @push('scripts')
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endpush
@endif
