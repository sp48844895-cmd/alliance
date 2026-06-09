@extends('layouts.app')

@section('title', $metaTitle)
@section('meta_description', $metaDescription)

@section('content')
<main id="main">
  <section class="members-overview" aria-labelledby="members-overview-h">
    <div class="container-x members-overview__grid">
      <div class="members-overview__copy" data-aos="fade-up">
        <span class="chapter"><b>{{ $overviewChapter }}</b> · Member directory</span>
        <h2 id="members-overview-h">{!! $overviewTitle !!}</h2>
        <p class="type-lede">{{ $overviewDescription }}</p>
      </div>

      <div class="members-stats" data-aos="fade-up" data-aos-delay="100" aria-label="Members overview">
        <div>
          <b>{{ $districtCount }}</b>
          <span>Districts</span>
        </div>
        <div>
          <b>{{ $memberTypeCount }}</b>
          <span>Member types</span>
        </div>
        <div>
          <b>{{ $totalMembers }}</b>
          <span>Listed profiles</span>
        </div>
      </div>
    </div>
  </section>

  <section class="members-filter" aria-label="Filter members">
    <div class="container-x">
      <form class="members-filter__panel" data-members-filters action="{{ route('members') }}" method="get">
        <div class="members-field">
          <label for="member-district">By district</label>
          <select id="member-district" name="district" data-members-district>
            @foreach ($districtOptions as $district)
              <option value="{{ $district['value'] }}" @selected($activeDistrict === $district['value'])>{{ $district['label'] }}</option>
            @endforeach
          </select>
        </div>

        <div class="members-field">
          <label for="member-type">Sort by</label>
          <select id="member-type" name="type" data-members-type>
            @foreach ($typeOptions as $typeOption)
              <option value="{{ $typeOption['value'] }}" @selected($activeType === $typeOption['value'])>{{ $typeOption['label'] }}</option>
            @endforeach
          </select>
        </div>

        <div class="members-field members-field--search">
          <label for="member-search">Search</label>
          <input id="member-search" name="search" type="search" value="{{ $activeSearch }}" data-members-search placeholder="Search member name or district" autocomplete="off">
        </div>

        <button class="btn btn-primary members-filter__reset" type="reset" data-members-reset>Reset</button>
      </form>
    </div>
  </section>

  <section class="members-directory" aria-labelledby="members-directory-h">
    <div class="container-x">
      <div class="members-section-head">
        <span class="chapter"><b>{{ $directoryChapter }}</b> · Active profiles</span>
        <h2 id="members-directory-h">{{ $directoryTitle }}</h2>
        <p><span data-members-count>{{ $memberTotal }}</span> profiles match your filters.</p>
      </div>

      @if ($hasMembers)
        <div class="members-grid" data-members-list>
          @foreach ($members as $member)
            <article class="member-card" data-member-card data-aos="fade-up">
              <div class="member-card__top">
                @if ($member['logo_url'] !== '')
                  <span class="member-card__avatar member-card__avatar--photo" aria-hidden="true">
                    <img src="{{ $member['logo_url'] }}" alt="" width="68" height="68" loading="lazy" decoding="async">
                  </span>
                @else
                  <span class="member-card__avatar" aria-hidden="true">{{ $member['initial'] }}</span>
                @endif
                <span class="member-card__type">{{ $memberTypes[$member['type']] ?? $member['type_label'] }}</span>
              </div>

              <div class="member-card__body">
                <h3>{{ $member['name'] }}</h3>
                <p class="member-card__district">{{ $member['district'] }}</p>
                @include('partials.member-social-icons', ['social' => $member['social'], 'name' => $member['name']])
              </div>

              <div class="member-card__contact">
                <a href="tel:+91{{ $member['phone_link'] }}" aria-label="Call {{ $member['name'] }}">
                  <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.69 2.8a2 2 0 0 1-.45 2.11L8.08 9.9a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.33 1.84.56 2.8.69A2 2 0 0 1 22 16.92z"/></svg>
                  {{ $member['phone'] }}
                </a>
                <a href="mailto:{{ $member['email'] }}" aria-label="Email {{ $member['name'] }}">
                  <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="m22 6-10 7L2 6"/></svg>
                  Email
                </a>
              </div>
            </article>
          @endforeach
        </div>

        <x-pagination :paginator="$memberPaginator" noun="members" prefix="mb-pagination" />
      @else
        <p class="members-empty">No members match your filters. Try resetting the form.</p>
      @endif
    </div>
  </section>
</main>
@endsection
