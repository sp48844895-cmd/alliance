@extends('layouts.app')

@section('title', $metaTitle ?? 'Campaigns · From Behaviour to Habit · ChhattisgarhABC')
@section('meta_description', $sections['meta']['meta_description'] ?? 'Six in-progress SBC campaigns across Chhattisgarh — Role of Males, Children & Education, Life Cycle Nutrition, Gender & Behaviour, Adolescent Health, and Community Participation. Filter by theme, district, and stage.')

@section('content')
<main id="main">

{{-- Section: hero --}}
<section class="cmp-hero"  aria-labelledby="cmp-hero-h">
@if(!empty($sections['hero']['html']))
{!! $sections['hero']['html'] !!}
@else
<div class="cmp-hero-deco" aria-hidden="true">
    <svg viewBox="0 0 240 240" xmlns="http://www.w3.org/2000/svg">
      <g fill="none" stroke="#5d2cb5" stroke-width="1.4" opacity="0.5">
        <circle cx="120" cy="120" r="110"/>
        <circle cx="120" cy="120" r="78"/>
        <circle cx="120" cy="120" r="46"/>
      </g>
      <g fill="#ff6b35">
        <circle cx="120" cy="10" r="4"/>
        <circle cx="230" cy="120" r="4"/>
        <circle cx="120" cy="230" r="4"/>
        <circle cx="10" cy="120" r="4"/>
      </g>
    </svg>
  </div>

  <div class="container-x">
    <div class="cmp-hero-grid">
      <div class="cmp-hero-text">
        <span class="chapter fade-up" data-delay="1"><b>01</b> · Campaigns &amp; What We Do</span>

        <h2 id="cmp-hero-h" class="cmp-hero-title type-hero fade-up" data-delay="2">
          <span class="line">From <span class="cmp-underline">behaviour</span></span>
          <span class="line">to <em>habit:</em></span>
          <span class="line cmp-hero-sub-line">A collective <em>journey.</em></span>
        </h2>

        <p class="cmp-hero-lede type-lede fade-up" data-delay="3">
          Six live campaigns. Twenty-three districts. Hundreds of villages where the alliance, communities and government walk the <b>same slow loop</b> — listen, frame, design, measure — until a one-time action becomes a daily habit.
        </p>

        <div class="hero-cta fade-up" data-delay="4">
          <a class="btn btn-primary" href="#cmp-grid">
            Explore campaigns
            <svg class="arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
          </a>
          <a class="btn btn-ghost" href="#cmp-tl-h">See timelines</a>
        </div>
      </div>

      <ul class="cmp-hero-stats fade-up" data-delay="4" aria-label="Campaign overview">
        <li>
          <span class="cmp-stat-num">6</span>
          <span class="cmp-stat-lbl">Live campaigns</span>
          <span class="cmp-stat-foot">Across nutrition, gender, education, health</span>
        </li>
        <li>
          <span class="cmp-stat-num">23<i>/33</i></span>
          <span class="cmp-stat-lbl">Districts active</span>
          <span class="cmp-stat-foot">From Bastar in the south to Surguja in the north</span>
        </li>
        <li>
          <span class="cmp-stat-num">82<small>%</small></span>
          <span class="cmp-stat-lbl">Average reach</span>
          <span class="cmp-stat-foot">Across target households in active districts</span>
        </li>
        <li>
          <span class="cmp-stat-num">1.4<small>M</small></span>
          <span class="cmp-stat-lbl">Lives touched</span>
          <span class="cmp-stat-foot">Households, schools, panchayats &amp; PVTG hamlets</span>
        </li>
      </ul>
    </div>
  </div>
@endif
</section>
{{-- Section: filters --}}
<section class="cmp-filters"  aria-label="Filter campaigns by theme and district">
@if(!empty($sections['filters']['html']))
{!! $sections['filters']['html'] !!}
@else
<div class="container-x">
    <div class="cmp-filters-row">
      <div class="cmp-filters-block">
        <label class="cmp-filters-lbl" for="cmp-filter-theme"><b>Theme</b></label>
        <div class="cmp-select">
          <select id="cmp-filter-theme" data-cmp-filter="theme">
            <option value="all">All themes</option>
            <option value="males">Role of Males</option>
            <option value="children">Children &amp; Education</option>
            <option value="nutrition">Life Cycle Nutrition</option>
            <option value="gender">Gender &amp; Behaviour</option>
            <option value="adolescent">Adolescent Health</option>
            <option value="community">Community Participation</option>
          </select>
          <svg class="cmp-select-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
        </div>
      </div>

      <div class="cmp-filters-block">
        <label class="cmp-filters-lbl" for="cmp-filter-district"><b>District</b></label>
        <div class="cmp-select">
          <select id="cmp-filter-district" data-cmp-filter="district">
            <option value="all">All districts</option>
            <option value="balod">Balod</option>
            <option value="bilaspur">Bilaspur</option>
            <option value="jashpur">Jashpur</option>
            <option value="raipur">Raipur</option>
            <option value="surguja">Surguja</option>
            <option value="bastar">Bastar</option>
            <option value="kabirdham">Kabirdham</option>
          </select>
          <svg class="cmp-select-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
        </div>
      </div>

      <div class="cmp-filters-meta">
        <span class="cmp-filters-count" aria-live="polite"><b data-cmp-count>6</b> campaigns</span>
        <button type="button" class="cmp-filters-reset" data-cmp-reset>Reset filters</button>
      </div>
    </div>
  </div>
@endif
</section>
{{-- Section: grid --}}
<section class="cmp-grid container-x" id="cmp-grid" aria-labelledby="cmp-grid-h">
@if(!empty($sections['grid']['html']))
{!! $sections['grid']['html'] !!}
@else
<h2 id="cmp-grid-h" class="sr-only">Campaign cards</h2>

  <div class="cmp-grid-empty" data-cmp-empty hidden>
    <span class="chapter"><b>—</b> · No matches</span>
    <p>No campaigns match these filters yet. Try a different theme or district, or <button type="button" class="cmp-link" data-cmp-reset>reset all filters</button>.</p>
  </div>

  <div class="cmp-grid-wrap" data-cmp-list>

    <article class="cmp-card cmp-card--males" data-aos="fade-up" data-theme="males" data-district="raipur,bilaspur,kabirdham">
      <div class="cmp-card-img" style="background-image:url('https://www.chhattisgarhabc.org/stories/uploads/story/67909992e8e60.png');">
        <span class="cmp-card-status cmp-card-status--scaling">Scaling</span>
        <span class="cmp-card-theme">01 · Role of Males</span>
      </div>
      <div class="cmp-card-body">
        <h3 class="cmp-card-title">Mardon ki <em>baat</em>: re-imagining the household</h3>
        <p class="cmp-card-lede">Engaging fathers, husbands and brothers as caregivers — challenging the idea that nutrition, schoolwork and healthcare are women's work alone.</p>

        <ul class="cmp-card-pills" aria-label="Active districts">
          <li>Raipur</li><li>Bilaspur</li><li>Kabirdham</li>
        </ul>

        <div class="cmp-card-foot">
          <a href="#cmp-tl-males" class="btn-link">See timeline →</a>
          <span class="cmp-card-meta">Started · Mar 2023</span>
        </div>
      </div>
    </article>

    <article class="cmp-card cmp-card--children" data-aos="fade-up" data-aos-delay="80" data-theme="children" data-district="jashpur,raipur,bastar">
      <div class="cmp-card-img" style="background-image:url('https://www.chhattisgarhabc.org/stories/uploads/story/69a086247a963.png');">
        <span class="cmp-card-status cmp-card-status--active">Active</span>
        <span class="cmp-card-theme">02 · Children &amp; Education</span>
      </div>
      <div class="cmp-card-body">
        <h3 class="cmp-card-title">Aaj <em>kya seekha?</em> — one question, every classroom</h3>
        <p class="cmp-card-lede">A simple daily question reshaping classroom conversations across Jashpur and beyond — peer-led, teacher-supported, parent-engaged.</p>

        <ul class="cmp-card-pills" aria-label="Active districts">
          <li>Jashpur</li><li>Raipur</li><li>Bastar</li>
        </ul>

        <div class="cmp-card-foot">
          <a href="#cmp-tl-children" class="btn-link">See timeline →</a>
          <span class="cmp-card-meta">Started · Aug 2024</span>
        </div>
      </div>
    </article>

    <article class="cmp-card cmp-card--nutrition" data-aos="fade-up" data-aos-delay="160" data-theme="nutrition" data-district="balod,bastar,surguja,kabirdham">
      <div class="cmp-card-img" style="background-image:url('https://www.chhattisgarhabc.org/images/home/1.jpg');">
        <span class="cmp-card-status cmp-card-status--scaling">Scaling</span>
        <span class="cmp-card-theme">03 · Life Cycle Nutrition</span>
      </div>
      <div class="cmp-card-body">
        <h3 class="cmp-card-title">Poshan, <em>din ke har waqt</em></h3>
        <p class="cmp-card-lede">Nutrition for the full life cycle — pregnancy to old age — through Poshan Maah, anganwadi circles and PVTG-hamlet kitchen counselling.</p>

        <ul class="cmp-card-pills" aria-label="Active districts">
          <li>Balod</li><li>Bastar</li><li>Surguja</li><li>Kabirdham</li>
        </ul>

        <div class="cmp-card-foot">
          <a href="#cmp-tl-nutrition" class="btn-link">See timeline →</a>
          <span class="cmp-card-meta">Started · Sep 2022</span>
        </div>
      </div>
    </article>

    <article class="cmp-card cmp-card--gender" data-aos="fade-up" data-theme="gender" data-district="balod,bilaspur,jashpur">
      <div class="cmp-card-img" style="background-image:url('https://www.chhattisgarhabc.org/stories/uploads/story/696641b1e3114.png');">
        <span class="cmp-card-status cmp-card-status--milestone">Milestone</span>
        <span class="cmp-card-theme">04 · Gender &amp; Behaviour</span>
      </div>
      <div class="cmp-card-body">
        <h3 class="cmp-card-title">Balod model: <em>child-marriage-free</em>, district-wide</h3>
        <p class="cmp-card-lede">A multi-year SBC effort, community-owned and government-backed — Balod becomes India's first verified child-marriage-free district. Now scaling.</p>

        <ul class="cmp-card-pills" aria-label="Active districts">
          <li>Balod</li><li>Bilaspur</li><li>Jashpur</li>
        </ul>

        <div class="cmp-card-foot">
          <a href="#cmp-tl-gender" class="btn-link">See timeline →</a>
          <span class="cmp-card-meta">Started · Apr 2020</span>
        </div>
      </div>
    </article>

    <article class="cmp-card cmp-card--adolescent" data-aos="fade-up" data-aos-delay="80" data-theme="adolescent" data-district="raipur,bilaspur,surguja,bastar">
      <div class="cmp-card-img" style="background-image:url('https://www.chhattisgarhabc.org/stories/uploads/event/427748.png');">
        <span class="cmp-card-status cmp-card-status--active">Active</span>
        <span class="cmp-card-theme">05 · Adolescent Health</span>
      </div>
      <div class="cmp-card-body">
        <h3 class="cmp-card-title">Kishor, <em>khulkar</em> baat</h3>
        <p class="cmp-card-lede">Peer-led adolescent health circles in schools — menstrual health, anaemia, mental wellbeing, consent — with parent and teacher allies.</p>

        <ul class="cmp-card-pills" aria-label="Active districts">
          <li>Raipur</li><li>Bilaspur</li><li>Surguja</li><li>Bastar</li>
        </ul>

        <div class="cmp-card-foot">
          <a href="#cmp-tl-adolescent" class="btn-link">See timeline →</a>
          <span class="cmp-card-meta">Started · Jul 2023</span>
        </div>
      </div>
    </article>

    <article class="cmp-card cmp-card--community" data-aos="fade-up" data-aos-delay="160" data-theme="community" data-district="balod,jashpur,kabirdham,surguja,bastar">
      <div class="cmp-card-img" style="background-image:url('https://www.chhattisgarhabc.org/stories/uploads/story/67ce9ac3493da.png');">
        <span class="cmp-card-status cmp-card-status--pilot">Pilot</span>
        <span class="cmp-card-theme">06 · Community Participation</span>
      </div>
      <div class="cmp-card-body">
        <h3 class="cmp-card-title">Kavir Lab — <em>community owns</em> the change</h3>
        <p class="cmp-card-lede">Participatory action labs in PVTG hamlets — community designs, community runs, alliance documents. Now in 5 districts.</p>

        <ul class="cmp-card-pills" aria-label="Active districts">
          <li>Balod</li><li>Jashpur</li><li>Kabirdham</li><li>Surguja</li><li>Bastar</li>
        </ul>

        <div class="cmp-card-foot">
          <a href="#cmp-tl-community" class="btn-link">See timeline →</a>
          <span class="cmp-card-meta">Started · Jan 2025</span>
        </div>
      </div>
    </article>

  </div>
@endif
</section>
{{-- Section: timelines --}}
<section class="cmp-tl-section"  aria-labelledby="cmp-tl-h">
@if(!empty($sections['timelines']['html']))
{!! $sections['timelines']['html'] !!}
@else
<div class="container-x">
    <div class="cmp-tl-head">
      <span class="chapter"><b>02</b> · Campaign timelines</span>
      <h2 id="cmp-tl-h" data-aos="fade-up">From <em>first listen</em> to lived habit.</h2>
      <p class="cmp-tl-sub" data-aos="fade-up" data-aos-delay="100">
        Each campaign moves through the same four phases — Listen · Frame · Co-create · Measure. Open any campaign below to walk its journey.
      </p>
    </div>

    <div class="cmp-tl-list" data-cmp-tl-list>

      <details class="cmp-tl-item cmp-tl-item--males" id="cmp-tl-males" data-theme="males" open>
        <summary class="cmp-tl-summary">
          <span class="cmp-tl-num">01</span>
          <div class="cmp-tl-summary-text">
            <span class="cmp-tl-eyebrow">Role of Males</span>
            <h4>Mardon ki baat — re-imagining the household</h4>
          </div>
          <span class="cmp-tl-summary-meta">
            <span>2023 → ongoing</span>
            <span class="cmp-tl-caret" aria-hidden="true">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </span>
          </span>
        </summary>
        <div class="cmp-tl-body">
          <ol class="cmp-tl-track">
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Listen · Mar 2023</span>
              <h5>Tea-stall conversations across 12 panchayats</h5>
              <p>Volunteer pairs sat at chai-stalls, mandi yards and barbershops — mapping how men talk about home, work and care.</p>
            </li>
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Frame · Aug 2023</span>
              <h5>One behaviour: showing up at the anganwadi</h5>
              <p>The alliance picked one specific, observable habit — fathers attending monthly anganwadi check-ups with their child.</p>
            </li>
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Co-create · Feb 2024</span>
              <h5>Father–child weighing days, posters in male voices</h5>
              <p>Anganwadis ran "Pita Diwas" weigh-in mornings; IEC posters used men's faces and phrases — "<em>aaj ka growth check, aaj ka pyaar.</em>"</p>
            </li>
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Measure · Q4 2024</span>
              <h5>Father attendance moves from 6% → 41%</h5>
              <p>Across the pilot districts, recorded male caregiver attendance lifted seven-fold over four quarters.</p>
            </li>
            <li class="cmp-tl-step cmp-tl-step--now">
              <span class="cmp-tl-step-tag">Now · 2026</span>
              <h5>Scaling to 14 districts &amp; 16,000 fathers</h5>
              <p>The model is being replicated district by district — with adapted local idioms, panchayat champions and quarterly review boards.</p>
            </li>
          </ol>
        </div>
      </details>

      <details class="cmp-tl-item cmp-tl-item--children" id="cmp-tl-children" data-theme="children">
        <summary class="cmp-tl-summary">
          <span class="cmp-tl-num">02</span>
          <div class="cmp-tl-summary-text">
            <span class="cmp-tl-eyebrow">Children &amp; Education</span>
            <h4>"Aaj kya seekha?" — one question, every classroom</h4>
          </div>
          <span class="cmp-tl-summary-meta">
            <span>2024 → ongoing</span>
            <span class="cmp-tl-caret" aria-hidden="true">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </span>
          </span>
        </summary>
        <div class="cmp-tl-body">
          <ol class="cmp-tl-track">
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Listen · Aug 2024</span>
              <h5>Why parents stop asking after Class 5</h5>
              <p>Observation: parents asked about marks but not learning. The team mapped the moment that question drops out of homes.</p>
            </li>
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Frame · Oct 2024</span>
              <h5>One ritual: a daily 30-second question</h5>
              <p>Designed around a single sentence — "<em>Aaj kya seekha?</em>" — printed on slates, postcards and panchayat boards.</p>
            </li>
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Co-create · Jan 2025</span>
              <h5>Teacher–student–parent triad in 600 schools</h5>
              <p>Schools ran "learning circles" — students reported one thing learned; teachers shared a parent script weekly.</p>
            </li>
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Measure · Sep 2025</span>
              <h5>Daily learning-talk in 71% of homes</h5>
              <p>Sample-survey: 71% of parents in pilot districts now ask the question at least 4× a week — up from a 9% baseline.</p>
            </li>
            <li class="cmp-tl-step cmp-tl-step--now">
              <span class="cmp-tl-step-tag">Now · 2026</span>
              <h5>1,820 schools live; 2,000 by mid-year</h5>
              <p>Currently across Jashpur, Raipur and Bastar — with state-education-department onboarding underway for the next 180 schools.</p>
            </li>
          </ol>
        </div>
      </details>

      <details class="cmp-tl-item cmp-tl-item--nutrition" id="cmp-tl-nutrition" data-theme="nutrition">
        <summary class="cmp-tl-summary">
          <span class="cmp-tl-num">03</span>
          <div class="cmp-tl-summary-text">
            <span class="cmp-tl-eyebrow">Life Cycle Nutrition</span>
            <h4>Poshan, din ke har waqt</h4>
          </div>
          <span class="cmp-tl-summary-meta">
            <span>2022 → ongoing</span>
            <span class="cmp-tl-caret" aria-hidden="true">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </span>
          </span>
        </summary>
        <div class="cmp-tl-body">
          <ol class="cmp-tl-track">
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Listen · Sep 2022</span>
              <h5>Anaemia is a story of meals, not pills</h5>
              <p>Field circles found families understood pills but not plates. Iron, protein and timing were missing from daily talk.</p>
            </li>
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Frame · Mar 2023</span>
              <h5>One plate, three colours, every meal</h5>
              <p>The alliance reframed nutrition as "<em>teen rang ki thali</em>" — green, yellow, red on every plate, twice a day.</p>
            </li>
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Co-create · Sep 2024</span>
              <h5>Poshan Maah — alliance-wide IEC drop</h5>
              <p>Anganwadis, panchayats, FM radio and schools ran the same plate-imagery — designed by the alliance, free to remix.</p>
            </li>
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Measure · Q3 2025</span>
              <h5>Adolescent-girl anaemia drops 18 ppt in pilots</h5>
              <p>In Balod and Kabirdham, surveyed adolescent-girl anaemia fell from 64% → 46% over four cycles of weekly counselling.</p>
            </li>
            <li class="cmp-tl-step cmp-tl-step--now">
              <span class="cmp-tl-step-tag">Now · 2026</span>
              <h5>4,300 anganwadis &amp; PVTG kitchen circles</h5>
              <p>Active across Balod, Bastar, Surguja and Kabirdham — with weekly "<em>thali tasveer</em>" check-ins on family WhatsApp groups.</p>
            </li>
          </ol>
        </div>
      </details>

      <details class="cmp-tl-item cmp-tl-item--gender" id="cmp-tl-gender" data-theme="gender">
        <summary class="cmp-tl-summary">
          <span class="cmp-tl-num">04</span>
          <div class="cmp-tl-summary-text">
            <span class="cmp-tl-eyebrow">Gender &amp; Behaviour</span>
            <h4>Balod — child-marriage-free, district-wide</h4>
          </div>
          <span class="cmp-tl-summary-meta">
            <span>2020 → verified 2026</span>
            <span class="cmp-tl-caret" aria-hidden="true">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </span>
          </span>
        </summary>
        <div class="cmp-tl-body">
          <ol class="cmp-tl-track">
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Listen · Apr 2020</span>
              <h5>Why families say "yes" earlier than they want</h5>
              <p>Door-step conversations across 200 villages mapped the social pressure points — debt, dowry, "log kya kahenge".</p>
            </li>
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Frame · Aug 2021</span>
              <h5>One ritual: panchayat &amp; school as joint signatories</h5>
              <p>Every class-10 girl signs an "18-tak-padhungi" pledge — countersigned by the sarpanch and the headmaster.</p>
            </li>
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Co-create · Apr 2023</span>
              <h5>"Beti Saath Saath" weekly circles</h5>
              <p>Mothers, daughters, Anganwadi workers and elders meet weekly — to track every adolescent girl, by name.</p>
            </li>
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Measure · Oct 2025</span>
              <h5>Zero recorded child marriages in 10 panchayats</h5>
              <p>An independent audit verified that ten gram panchayats in Balod recorded zero under-18 marriages over the last 12 months.</p>
            </li>
            <li class="cmp-tl-step cmp-tl-step--now cmp-tl-step--milestone">
              <span class="cmp-tl-step-tag">Milestone · Jan 2026</span>
              <h5>Balod = India's first child-marriage-free district</h5>
              <p>All 447 panchayats verified. The alliance is now adapting the model for Bilaspur and Jashpur — with a 24-month roadmap.</p>
            </li>
          </ol>
        </div>
      </details>

      <details class="cmp-tl-item cmp-tl-item--adolescent" id="cmp-tl-adolescent" data-theme="adolescent">
        <summary class="cmp-tl-summary">
          <span class="cmp-tl-num">05</span>
          <div class="cmp-tl-summary-text">
            <span class="cmp-tl-eyebrow">Adolescent Health</span>
            <h4>Kishor, khulkar baat</h4>
          </div>
          <span class="cmp-tl-summary-meta">
            <span>2023 → ongoing</span>
            <span class="cmp-tl-caret" aria-hidden="true">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </span>
          </span>
        </summary>
        <div class="cmp-tl-body">
          <ol class="cmp-tl-track">
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Listen · Jul 2023</span>
              <h5>Where adolescents go silent</h5>
              <p>Mapped which questions students avoid in class, at home and at the anganwadi — the silence map drove the curriculum.</p>
            </li>
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Frame · Dec 2023</span>
              <h5>Peer-led circles, teacher-supported, parent-engaged</h5>
              <p>Designed three-layer triads — same age peer leaders, two teacher-allies per school, monthly parent debrief.</p>
            </li>
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Co-create · May 2024</span>
              <h5>Open-floor "<em>khulkar</em>" sessions in 320 schools</h5>
              <p>Periods, mental health, anaemia, consent — addressed in age-appropriate, locally-translated curriculum kits.</p>
            </li>
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Measure · Q3 2025</span>
              <h5>54,000 students enrolled, 12,000 peer leaders</h5>
              <p>Mid-line survey: 78% of students report "I can ask my teacher one health question" — up from 22% baseline.</p>
            </li>
            <li class="cmp-tl-step cmp-tl-step--now">
              <span class="cmp-tl-step-tag">Now · 2026</span>
              <h5>Expanding to 80,000 students &amp; PVTG schools</h5>
              <p>Active across Raipur, Bilaspur, Surguja and Bastar — with PVTG-tribal-school adaptation underway for Hill Korwa &amp; Birhor schools.</p>
            </li>
          </ol>
        </div>
      </details>

      <details class="cmp-tl-item cmp-tl-item--community" id="cmp-tl-community" data-theme="community">
        <summary class="cmp-tl-summary">
          <span class="cmp-tl-num">06</span>
          <div class="cmp-tl-summary-text">
            <span class="cmp-tl-eyebrow">Community Participation</span>
            <h4>Kavir Lab — community owns the change</h4>
          </div>
          <span class="cmp-tl-summary-meta">
            <span>2025 → pilot</span>
            <span class="cmp-tl-caret" aria-hidden="true">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </span>
          </span>
        </summary>
        <div class="cmp-tl-body">
          <ol class="cmp-tl-track">
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Listen · Jan 2025</span>
              <h5>Co-design with Baiga &amp; Kamar gram sabhas</h5>
              <p>Three-day immersions in PVTG hamlets — the alliance brought no agenda, only listening tools and a notebook.</p>
            </li>
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Frame · Apr 2025</span>
              <h5>One rule: the lab is the community's, not ours</h5>
              <p>Each lab has a community-elected coordinator; alliance only documents and supplies craft tools when asked.</p>
            </li>
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Co-create · Aug 2025</span>
              <h5>104 hamlet labs designed by hamlets themselves</h5>
              <p>Some chose anaemia, some chose drinking-water hygiene, some chose girls'-school-attendance — all picked by the gram sabha.</p>
            </li>
            <li class="cmp-tl-step">
              <span class="cmp-tl-step-tag">Measure · Q1 2026</span>
              <h5>Self-reported "we own this" in 88% of labs</h5>
              <p>Reverse-survey design — communities rated alliance involvement; 88% rated the lab as "ours, with their support".</p>
            </li>
            <li class="cmp-tl-step cmp-tl-step--now">
              <span class="cmp-tl-step-tag">Now · 2026</span>
              <h5>Scaling to 200 hamlets across 5 districts</h5>
              <p>Active in Balod, Jashpur, Kabirdham, Surguja and Bastar — with a gram-sabha-only governance template open for replication.</p>
            </li>
          </ol>
        </div>
      </details>

    </div>
  </div>
@endif
</section>
{{-- Section: cta --}}
<section class="cmp-cta"  aria-labelledby="cmp-cta-h">
@if(!empty($sections['cta']['html']))
{!! $sections['cta']['html'] !!}
@else
<div class="container-x">
    <div class="cmp-cta-grid">
      <div data-aos="fade-up">
        <span class="chapter" style="color:rgba(250,248,255,0.7);"><b style="color:var(--ochre);">04</b> · Get involved</span>
        <h2 id="cmp-cta-h">Pick a campaign. <em>Walk a quarter.</em></h2>
        <p class="cmp-cta-lede">Each campaign is open to volunteers, NGO partners, researchers and CSR allies. Pick one that resonates — and join the next monthly circle.</p>
      </div>

      <div class="cmp-cta-paths" data-aos="fade-up" data-aos-delay="120">
        <a href="{{ route('get-involved') }}" class="cmp-cta-path">
          <b>Volunteer with a campaign</b>
          <span>Pick the campaign that resonates &amp; join a field circle</span>
          <svg class="arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
        </a>
        <a href="{{ route('learning-corner') }}" class="cmp-cta-path">
          <b>Use our IEC openly</b>
          <span>All posters, scripts &amp; toolkits are open-licensed</span>
          <svg class="arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
        </a>
        <a href="{{ route('learning-corner') }}" class="cmp-cta-path">
          <b>Study the impact data</b>
          <span>Audit reports, mid-lines &amp; verified outcomes</span>
          <svg class="arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
        </a>
        <a href="{{ route('about') . '#ab-w-h' }}" class="cmp-cta-path">
          <b>How the alliance works</b>
          <span>Open, non-financial, rotating-anchor model</span>
          <svg class="arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
        </a>
      </div>
    </div>
  </div>
@endif
</section>
</main>
@endsection
