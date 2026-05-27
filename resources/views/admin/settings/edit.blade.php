@extends('layouts.admin')

@section('title', 'Site Settings')
@section('page_title', 'Site Settings')

@section('breadcrumb')
    <span>Configuration</span>
    <i class="bi bi-chevron-right text-xs"></i>
    <span>Site Settings</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-ghost btn-sm">
        <i class="bi bi-box-arrow-up-right"></i>
        <span>View public site</span>
    </a>
@endsection

@php
    $logoExisting = !empty($site->logo) && is_file(public_path('uploads/site/' . $site->logo))
        ? asset('uploads/site/' . $site->logo)
        : '';
@endphp

@section('content')
    <div class="grid grid-cols-1 gap-6 max-w-4xl">

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="card p-6">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-lg bg-[var(--color-clay-100)] text-[var(--color-clay-700)] flex items-center justify-center shrink-0">
                    <i class="bi bi-gear text-lg"></i>
                </div>
                <div>
                    <h2 class="font-display text-lg text-[var(--color-ink-2)]">General settings</h2>
                    <p class="text-xs text-[var(--color-mute)]">Site identity, logo, and content limits.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="label" for="title">Site title <span class="text-[var(--color-flame)]">*</span></label>
                    <input id="title" type="text" name="title" maxlength="255" required
                           value="{{ old('title', $site->title ?? '') }}"
                           class="input" placeholder="Alliance for Behavior Change">
                    @error('title') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="label" for="footer">Footer text</label>
                    <input id="footer" type="text" name="footer" maxlength="255"
                           value="{{ old('footer', $site->footer ?? '') }}"
                           class="input" placeholder="© 2026 ABC Chhattisgarh. All rights reserved.">
                    <p class="help">Shown at the bottom of every public page.</p>
                    @error('footer') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label" for="postdisplay">Posts per page <span class="text-[var(--color-flame)]">*</span></label>
                    <input id="postdisplay" type="number" name="postdisplay" min="1" max="100" required
                           value="{{ old('postdisplay', $site->postdisplay ?? 10) }}"
                           class="input">
                    <p class="help">Number of items shown on listing pages (1–100).</p>
                    @error('postdisplay') <p class="err">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label" for="logo">Site logo</label>
                    <div class="flex items-center gap-4 mb-3">
                        <div class="w-20 h-20 rounded-lg bg-[var(--color-paper)] border border-[var(--color-line)] flex items-center justify-center overflow-hidden shrink-0">
                            <img id="logoPreview"
                                 src="{{ $logoExisting }}"
                                 alt="Logo preview"
                                 class="w-full h-full object-contain {{ $logoExisting ? '' : 'hidden' }}">
                            @if (!$logoExisting)
                                <i class="bi bi-image text-2xl text-[var(--color-mute-2)]"></i>
                            @endif
                        </div>
                        <input type="file" name="logo" id="logo" accept="image/*"
                               class="input"
                               data-image-preview="#logoPreview">
                    </div>
                    <p class="help">PNG / SVG recommended.</p>
                    @error('logo') <p class="err">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 mt-6 pt-5 border-t border-[var(--color-line)]">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check2"></i>
                    <span>Save settings</span>
                </button>
            </div>
        </form>

        <form id="social" method="POST" action="{{ route('admin.settings.social.update') }}" class="card p-6">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-lg bg-[var(--color-clay-100)] text-[var(--color-clay-700)] flex items-center justify-center shrink-0">
                    <i class="bi bi-share text-lg"></i>
                </div>
                <div>
                    <h2 class="font-display text-lg text-[var(--color-ink-2)]">Social links</h2>
                    <p class="text-xs text-[var(--color-mute)]">Profile URLs shown in header and footer.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @php
                    $socialFields = [
                        ['name' => 'facebook',  'label' => 'Facebook',  'icon' => 'bi-facebook',  'placeholder' => 'https://facebook.com/yourpage', 'type' => 'url'],
                        ['name' => 'twitter',   'label' => 'Twitter / X','icon' => 'bi-twitter-x', 'placeholder' => 'https://twitter.com/yourhandle', 'type' => 'url'],
                        ['name' => 'instagram', 'label' => 'Instagram', 'icon' => 'bi-instagram', 'placeholder' => 'https://instagram.com/yourhandle', 'type' => 'url'],
                        ['name' => 'linkedin',  'label' => 'LinkedIn',  'icon' => 'bi-linkedin',  'placeholder' => 'https://linkedin.com/company/yourcompany', 'type' => 'url'],
                        ['name' => 'github',    'label' => 'GitHub',    'icon' => 'bi-github',    'placeholder' => 'https://github.com/yourorg', 'type' => 'url'],
                        ['name' => 'footerlink','label' => 'Footer link','icon' => 'bi-link-45deg','placeholder' => 'https://example.com', 'type' => 'url'],
                    ];
                @endphp

                @foreach ($socialFields as $f)
                    <div>
                        <label class="label flex items-center gap-2" for="{{ $f['name'] }}">
                            <i class="bi {{ $f['icon'] }}"></i>
                            <span>{{ $f['label'] }}</span>
                        </label>
                        <input id="{{ $f['name'] }}" type="{{ $f['type'] }}" name="{{ $f['name'] }}" maxlength="255"
                               value="{{ old($f['name'], $social->{$f['name']} ?? '') }}"
                               class="input" placeholder="{{ $f['placeholder'] }}">
                        @error($f['name']) <p class="err">{{ $message }}</p> @enderror
                    </div>
                @endforeach

                <div class="md:col-span-2">
                    <label class="label flex items-center gap-2" for="footertxt">
                        <i class="bi bi-card-text"></i>
                        <span>Footer link label</span>
                    </label>
                    <input id="footertxt" type="text" name="footertxt" maxlength="255"
                           value="{{ old('footertxt', $social->footertxt ?? '') }}"
                           class="input" placeholder="Built by ABC Tech Team">
                    <p class="help">Plain text label displayed next to the footer link.</p>
                    @error('footertxt') <p class="err">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 mt-6 pt-5 border-t border-[var(--color-line)]">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check2"></i>
                    <span>Save social links</span>
                </button>
            </div>
        </form>

    </div>
@endsection
