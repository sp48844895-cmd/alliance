@extends('layouts.app')

@section('title', $pageContent['meta_title'] ?? 'About the Alliance · ChhattisgarhABC')
@section('meta_description', $pageSections['meta']['meta_description'] ?? 'ChhattisgarhABC is an open, non-financial alliance of youth, professionals, civil society and government — co-creating Social & Behaviour Change Communication across Chhattisgarh, with a deep focus on PVTG villages.')

@section('content')
<main id="main">

<section class="ab-hero" aria-labelledby="ab-hero-h">
<div class="ab-hero-bg" aria-hidden="true">
    <svg class="ab-hero-deco ab-hero-deco--a" viewBox="0 0 240 240" xmlns="http://www.w3.org/2000/svg">
      <g fill="none" stroke="#5d2cb5" stroke-width="1.4" opacity="0.55">
        <circle cx="120" cy="120" r="110"/>
        <circle cx="120" cy="120" r="78"/>
        <circle cx="120" cy="120" r="46"/>
      </g>
      <g fill="#ff6b35">
        <circle cx="120" cy="10" r="4"/>
        <circle cx="230" cy="120" r="4"/>
        <circle cx="120" cy="230" r="4"/>
        <circle cx="10"  cy="120" r="4"/>
      </g>
    </svg>
    <svg class="ab-hero-deco ab-hero-deco--b" viewBox="0 0 320 220" xmlns="http://www.w3.org/2000/svg">
      <g fill="none" stroke="#3237f0" stroke-width="1.2" opacity="0.4">
        <path d="M10 110 L40 70 L70 110 L100 70 L130 110 L160 70 L190 110 L220 70 L250 110 L280 70 L310 110"/>
        <path d="M10 140 L40 180 L70 140 L100 180 L130 140 L160 180 L190 140 L220 180 L250 140 L280 180 L310 140"/>
      </g>
    </svg>
  </div>

  <div class="container-x">
    <div class="ab-hero-grid">
      <div>
        <span class="chapter fade-up" data-delay="1"><b>01</b> · About the Alliance</span>

        <h2 id="ab-hero-h" class="ab-hero-title type-hero fade-up" data-delay="2">
          <span class="line">An <em>open</em> alliance.</span>
          <span class="line">A shared <em>practice.</em></span>
          <span class="line">A state-wide story of <span class="ab-underline">behaviour change.</span></span>
        </h2>

        <p class="ab-hero-lede ab-hero-lede--intro type-lede fade-up" data-delay="3">
          ChhattisgarhABC is a dynamic platform dedicated to strengthening <em>Social and Behaviour Change Communication (SBC)</em> across Chhattisgarh. It serves as a collaborative community space where youth, professionals, civil society organizations, development partners, media, and government institutions come together to learn, connect, share experiences, and drive meaningful social change.
        </p>
        <details class="ab-hero-more fade-up" data-delay="4">
          <summary>
            <span class="ab-hero-more__open">Read more</span>
            <span class="ab-hero-more__close">Read less</span>
          </summary>
          <p class="ab-hero-lede ab-hero-more__content type-lede">
            SBC is more than posters, advertisements, or awareness messages. It is a people-centered approach that helps individuals, families, and communities make informed choices and adopt healthier, safer, and more positive behaviours in everyday life. From villages surrounded by forests to growing urban neighborhoods, SBC helps connect policies with people through communication that is local, relatable, and impactful.
          </p>
        </details>

      </div>

      <div class="ab-hero-art fade-up" data-delay="3" aria-hidden="true">
        <div class="ab-hero-panel ab-hero-panel--1" style="background-image:url('{{ asset('uploads/banners/A2gibx2hN4hbj74ZAwcB.png') }}');"></div>
        <div class="ab-hero-panel ab-hero-panel--2" style="background-image:url('{{ asset('uploads/story/696641b1e3114.png') }}');"></div>
        <div class="ab-hero-panel ab-hero-panel--3" style="background-image:url('{{ asset('uploads/story/69b2a3de375ea.png') }}');"></div>
      </div>
    </div>
  </div>
</section>
{{-- Section: vision_mission --}}
<section class="ab-vm container-x" aria-labelledby="ab-vm-h">
@if(!empty($pageSections['vision_mission']['html']))
{!! $pageSections['vision_mission']['html'] !!}
@else
<div class="ab-vm-head">
    <span class="chapter"><b>02</b> · Nature &amp; Mission</span>
    <h2 id="ab-vm-h" data-aos="fade-up">How the alliance <em>works</em> and why it exists.</h2>
    <p class="ab-vm-sub" data-aos="fade-up" data-aos-delay="100">
      ChhattisgarhABC brings people and institutions together through shared learning, trust, participation and coordinated action.
    </p>
  </div>

  <div class="ab-vm-grid">
    <article class="ab-vm-card ab-vm-card--vision" data-aos="fade-up">
      <div class="ab-vm-stamp" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/></svg>
      </div>
      <span class="ab-vm-kicker">Nature of the Alliance</span>
      <h3 class="ab-vm-title">
        A collaborative and non-financial alliance built on shared vision, common objectives and mutual commitment.
      </h3>
      <p class="ab-vm-copy">
        ChhattisgarhABC brings together youth, professionals, civil society organizations, development partners, academic institutions, media, and government bodies on an equal platform without rigid hierarchy or reporting structures.
      </p>
      <p class="ab-vm-copy">
        The alliance functions through participation, collective learning, knowledge sharing, and coordinated action. Leadership and facilitation responsibilities may rotate periodically to encourage inclusiveness, shared ownership, and diverse perspectives.
      </p>
    </article>

    <article class="ab-vm-card ab-vm-card--mission" data-aos="fade-up" data-aos-delay="120">
      <div class="ab-vm-stamp" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12l4 4L21 6"/><path d="M3 18l4 4L21 8"/></svg>
      </div>
      <span class="ab-vm-kicker">Mission of the Alliance</span>
      <h3 class="ab-vm-title">
        To leverage the art of behavioural science and strategic communication to expedite desired changes and actions in the community.
      </h3>
      <p class="ab-vm-copy">
        The alliance works to ensure meaningful communication across all spaces.
      </p>
      <p class="ab-vm-copy">
        Grounded in local realities, the alliance works to understand, design, and sustain community-led behavioural change.
      </p>
    </article>
  </div>
@endif
</section>
{{-- Section: approach --}}
<section class="ab-approach container-x" aria-labelledby="ab-app-h">
@if(!empty($pageSections['approach']['html']))
{!! $pageSections['approach']['html'] !!}
@else
@php
  $steps = [
    [
      'num' => '01',
      'title' => 'Understand Local Realities',
      'text' => 'Understanding local challenges, beliefs, and community needs before designing any communication.',
      'image' => asset('assets/img/about/approach/step-01-understand-local-realities.png'),
      'alt' => 'Team members talking with community members in a rural setting',
    ],
    [
      'num' => '02',
      'title' => 'User Insights',
      'text' => 'Using research and real-world insights—including mobile data collection—to design communication that responds to lived experience.',
      'image' => asset('assets/img/about/approach/step-02-user-insights.png'),
      'alt' => 'Community members sharing insights using mobile phones',
    ],
    [
      'num' => '03',
      'title' => 'Create Local Messages',
      'text' => 'Creating culturally relevant messages, wall art, and local design so communication feels relatable in every space.',
      'image' => asset('assets/img/about/approach/step-03-create-local-messages.png'),
      'alt' => 'Local campaign messaging and community-designed communication materials',
    ],
    [
      'num' => '04',
      'title' => 'Build People Together',
      'text' => 'Engaging communities, frontline workers, youth, media, and policymakers together through group meetings and shared platforms.',
      'image' => asset('assets/img/about/approach/step-04-build-people-together.png'),
      'alt' => 'Community group discussion during a collaborative workshop',
    ],
    [
      'num' => '05',
      'title' => 'Building Lasting Change',
      'text' => 'Promoting lasting behaviour change through participation, trust, and shared ownership—reaching families and children across Chhattisgarh.',
      'image' => asset('assets/img/about/approach/step-05-lasting-change.jpg'),
      'alt' => 'Mother and child representing healthier futures through community-led change',
    ],
  ];
@endphp

<div class="ab-app-head">
  <span class="chapter"><b>04</b> · How We Work</span>
  <h2 id="ab-app-h" data-aos="fade-up">Grounded in people, place and <em>trust.</em></h2>
  <p class="ab-app-sub" data-aos="fade-up" data-aos-delay="100">
    ChhattisgarhABC designs communication through local understanding, evidence, culture and collective participation.
  </p>
</div>

<ol class="ab-app-steps">
  @foreach ($steps as $step)
    <li class="ab-app-step" data-aos="fade-up">
      <div class="ab-app-step-pin" aria-hidden="true">
        <span class="ab-app-step-num">{{ $step['num'] }}</span>
      </div>
      <article class="ab-app-step-panel">
        <figure class="ab-app-step-fig">
          <img
            src="{{ $step['image'] }}"
            alt="{{ $step['alt'] }}"
            width="640"
            height="480"
            loading="lazy"
            decoding="async"
          />
        </figure>
        <div class="ab-app-step-body">
          <h3 class="ab-app-step-title">{{ $step['title'] }}</h3>
          <p class="ab-app-step-text">{{ $step['text'] }}</p>
        </div>
      </article>
    </li>
  @endforeach
</ol>

@endif
</section>
{{-- Section: voices --}}
<section class="st-voices" id="st-voices" aria-labelledby="st-voices-h">
@if(!empty($pageSections['voices']['html']))
{!! $pageSections['voices']['html'] !!}
@else
<div class="container-x">
    <div class="st-voices-head">
      <span class="st-testimonials-label" data-aos="fade-up">Testimonials</span>
      <span class="chapter"><b>07</b> · Community voices</span>
      <h2 id="st-voices-h" data-aos="fade-up">A wall of <em>quiet</em> evidence.</h2>
      <p class="st-voices-sub" data-aos="fade-up" data-aos-delay="100">
        Pause the marquee, read what they said.
      </p>
    </div>
  </div>

  <div class="st-marquee" data-aos="fade-up" data-st-marquee>
    <div class="st-marquee-track">
      <figure class="st-quote-card st-quote-card--ochre">
        <blockquote>"Alliance for Behaviour Change is a shared platform for learning, collaboration, and collective action. By bringing together communities, youth, professionals, and institutions, it helps strengthen Social and Behaviour Change Communication through locally relevant and people-centered approaches. The alliance creates opportunities to exchange experiences, understand what works on the ground, and contribute towards lasting positive behaviour change across Chhattisgarh."</blockquote>
        <figcaption>
          <b>Abhishek Singh</b>
          <span>SBC Specialist, UNICEF Chhattisgarh</span>
        </figcaption>
      </figure>
    </div>
  </div>
@endif
</section>
{{-- Section: partners --}}
<section class="ab-partners" aria-labelledby="ab-p-h">
@if(!empty($pageSections['partners']['html']))
{!! $pageSections['partners']['html'] !!}
@else
<div class="container-x">
    <div class="ab-partners-head">
      <span class="chapter"><b>09</b> · Partners &amp; Members</span>
      <h2 id="ab-p-h" data-aos="fade-up">Built through <em>partnerships</em>, driven by people.</h2>
      <p class="ab-partners-sub" data-aos="fade-up" data-aos-delay="100">
        A growing alliance of changemakers working together to strengthen awareness, trust, and community action.
      </p>
    </div>

    <div class="ab-partners-grid">
      <article class="ab-partners-card" data-aos="fade-up">
        <span class="ab-partners-num">5,000<i>+</i></span>
        <h4>Volunteers</h4>
        <p>Field volunteers, peer educators &amp; youth leaders — the alliance's frontline.</p>
        <span class="ab-partners-tag">Yuvoday · Field circles · Peer leaders</span>
      </article>

      <article class="ab-partners-card" data-aos="fade-up" data-aos-delay="80">
        <span class="ab-partners-num">144<i>+</i></span>
        <h4>NGO &amp; CSO partners</h4>
        <p>Civil-society organisations co-designing district-level programmes.</p>
        <span class="ab-partners-tag">UNICEF · State CSOs · District NGOs</span>
      </article>

      <article class="ab-partners-card" data-aos="fade-up" data-aos-delay="160">
        <span class="ab-partners-num">35</span>
        <h4>Academic institutions</h4>
        <p>Universities and research bodies contributing studies and capacity-building.</p>
        <span class="ab-partners-tag">Public universities · Schools · Labs</span>
      </article>

      <article class="ab-partners-card" data-aos="fade-up" data-aos-delay="240">
        <span class="ab-partners-num">15</span>
        <h4>Firms &amp; bodies</h4>
        <p>Government departments and private firms partnering on specific behaviours.</p>
        <span class="ab-partners-tag">Health · Education · WCD · CSR</span>
      </article>
    </div>

    {{--
<div class="ab-partners-strip" aria-label="Partner organisations">
      <div class="ab-partners-track">
        <span>UNICEF</span><i>·</i>
        <span>WHO India</span><i>·</i>
        <span>State Health Mission</span><i>·</i>
        <span>WCD Chhattisgarh</span><i>·</i>
        <span>School Education Dept.</span><i>·</i>
        <span>Yuvoday</span><i>·</i>
        <span>NIT Raipur</span><i>·</i>
        <span>IIM Raipur</span><i>·</i>
        <span>UNICEF</span><i>·</i>
        <span>WHO India</span><i>·</i>
        <span>State Health Mission</span><i>·</i>
        <span>WCD Chhattisgarh</span><i>·</i>
        <span>School Education Dept.</span><i>·</i>
        <span>Yuvoday</span><i>·</i>
        <span>NIT Raipur</span><i>·</i>
        <span>IIM Raipur</span><i>·</i>
      </div>
    </div>
    --}}

    {{--
    <div class="ab-partners-cta" data-aos="fade-up">
      <div>
        <h3>Bring your chair to the circle.</h3>
        <p>The alliance is open. If you work on behaviour change in Chhattisgarh — as an NGO, a researcher, a CSR lead, or a single volunteer — write to us.</p>
      </div>
      <div class="hero-cta">
        <a class="btn btn-primary" href="{{ route('contact') }}">
          Join the alliance
          <svg class="arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
        </a>
        <a class="btn btn-ghost" href="{{ route('get-involved') }}">Explore ways to join</a>
      </div>
    </div>
    --}}
  </div>
@endif
</section>
</main>
@endsection
