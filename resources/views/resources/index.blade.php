@extends('layouts.app')

@section('title', $metaTitle)
@section('meta_description', $metaDescription)

@section('content')
<main id="main">
  <section class="rp-directory section-tight" aria-labelledby="rp-directory-title">
    <div class="container-x">
      <div class="rp-directory__head">
        <div>
          <span class="eyebrow">{{ $sections['resource_directory']['eyebrow'] ?? 'Resource people' }}</span>
          <h2 id="rp-directory-title">{{ $sections['resource_directory']['title'] ?? 'Connect with practitioners' }}</h2>
        </div>
        <p>{{ $sections['resource_directory']['description'] ?? 'Browse the current resource pool and reach out by email for learning support, facilitation and collaboration.' }}</p>
      </div>

      <div class="rp-grid">
        @foreach ($resourcePeople as $person)
          <article class="rp-card" data-aos="fade-up">
            <div class="rp-card__image">
              @if ($person['image'] !== '')
                <img src="{{ $person['image'] }}" alt="{{ $person['name'] }}" width="124" height="124" loading="lazy" decoding="async">
              @else
                <span class="rp-card__fallback" aria-hidden="true">{{ $person['initial'] }}</span>
              @endif
            </div>
            <div class="rp-card__body rp-card__info">
              <h3>{{ $person['name'] }}</h3>
              @if ($person['email'] !== '')
                <p class="rp-card__email-line">
                  <a href="mailto:{{ $person['email'] }}">{{ $person['email'] }}</a>
                </p>
              @endif
              @include('partials.member-social-icons', [
                'social' => $person['social'],
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
