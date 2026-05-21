@extends('layouts.app')

@section('title', $pageContent['meta_title'] ?? 'SBC Resource Pool · ChhattisgarhABC')
@section('meta_description', $pageContent['meta_description'] ?? 'Meet the SBC Resource Pool of ChhattisgarhABC: resource people supporting social and behaviour change learning, facilitation and field practice.')

@section('content')
<main id="main">
  <section class="rp-intro section-tight" aria-labelledby="rp-intro-title">
    <div class="container-x rp-intro__grid">
      <div>
        {{--
        <span class="chapter"><b>03</b> · Resource Kit</span>
        <h2 id="rp-intro-title">SBC Resource <em>Pool</em></h2>
        --}}
      </div>
      {{--
      <p>
        A shared pool of practitioners, trainers and community-facing communicators who can support learning sessions, field conversations and behaviour-change practice across the alliance.
      </p>
      --}}
    </div>
  </section>

  <section class="rp-directory section-tight" aria-labelledby="rp-directory-title">
    <div class="container-x">
      <div class="rp-directory__head">
        <div>
          <span class="eyebrow">{{ $pageSections['resource_directory']['eyebrow'] ?? 'Resource people' }}</span>
          <h2 id="rp-directory-title">{{ $pageSections['resource_directory']['title'] ?? 'Connect with practitioners' }}</h2>
        </div>
        <p>
          {{ $pageSections['resource_directory']['description'] ?? 'Browse the current resource pool and reach out by email for learning support, facilitation and collaboration.' }}
        </p>
      </div>

      <div class="rp-grid">
        @foreach (($resourcePeople ?? []) as $person)
          @php
            $initial = strtoupper(substr(str_replace('.', '', $person['name']), 0, 1));
          @endphp
          <article class="rp-card" data-aos="fade-up">
            <div class="rp-card__image">
              @if (!empty($person['image_url']))
                <img src="{{ $person['image_url'] }}" alt="{{ $person['name'] }}" width="124" height="124" loading="lazy" decoding="async">
              @else
                <span class="rp-card__fallback" aria-hidden="true">{{ $initial }}</span>
              @endif
            </div>
            <div class="rp-card__body rp-card__info">
              <h3>{{ $person['name'] }}</h3>
              @if (!empty($person['email']))
                <p class="rp-card__email-line">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <path d="m22 6-10 7L2 6"/>
                  </svg>
                  <a href="mailto:{{ $person['email'] }}">{{ $person['email'] }}</a>
                </p>
              @endif
              @include('partials.member-social-icons', [
                'social' => $person['social'] ?? [],
                'name' => $person['name'],
                'wrapperClass' => 'rp-card__social',
              ])
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>
</main>
@endsection
