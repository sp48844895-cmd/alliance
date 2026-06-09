@php
  $pageJumbotron = null;
  $jumbotronSection = $pageSections['jumbotron'] ?? null;

  if (request()->routeIs('login.*') && isset($config)) {
      $pageJumbotron = [
          'eyebrow' => 'Access portal',
          'title' => 'Log in as ' . $config['label'],
          'lede' => $config['lede'],
          'image' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=1600&auto=format&fit=crop',
          'icon' => 'shield',
          'highlights' => [
              ['label' => 'Role-based access', 'value' => 'Use the login path that matches your work with the alliance.'],
              ['label' => 'Quick support', 'value' => 'Switch easily between volunteer, intern, NGO and admin access.'],
              ['label' => 'Secure sign-in', 'value' => 'A focused entry point for your dashboard, updates and tasks.'],
          ],
      ];
  } elseif (is_array($jumbotronSection) && $jumbotronSection !== []) {
      $header = $pageSections['page_header'] ?? [];
      $pageJumbotron = $jumbotronSection;

      if (isset($header['pageTitle'])) {
          $pageJumbotron['title'] = $header['pageTitle'];
      }

      if (isset($header['pageLede'])) {
          $pageJumbotron['lede'] = $header['pageLede'];
      }
  } elseif (isset($pageTitle)) {
      $pageJumbotron = [
          'eyebrow' => $chapter ?? 'Page',
          'title' => $pageTitle,
          'lede' => $pageLede ?? 'This section is part of the alliance platform and will continue to grow with more content and resources.',
          'image' => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?q=80&w=1600&auto=format&fit=crop',
          'icon' => 'spark',
          'highlights' => [
              ['label' => 'What to expect', 'value' => 'A clearer entry point into the content and tools on this page.'],
              ['label' => 'Page focus', 'value' => 'Highlights that help visitors understand what matters first.'],
              ['label' => 'Next steps', 'value' => 'A simple bridge into the main content below.'],
          ],
      ];
  }
@endphp

@if ($pageJumbotron)
  @php
    $featuredHighlight = $pageJumbotron['highlights'][0] ?? null;
    $jumbotronTitleIsHeading = ! request()->routeIs('events.show');
    $featuredHighlightIcon = $pageJumbotron['icon'] ?? match (true) {
        request()->routeIs('about') => 'target',
        request()->routeIs('campaigns') => 'campaign',
        request()->routeIs('stories*') => 'story',
        request()->routeIs('events*') => 'calendar',
        request()->routeIs('programs') => 'target',
        request()->routeIs('resources') => 'book',
        request()->routeIs('learning-corner', 'learning-corner.main', 'learning-corner.sub') => 'book',
        request()->routeIs('get-involved') => 'users',
        request()->routeIs('members') => 'users',
        request()->routeIs('contact') => 'mail',
        request()->routeIs('reports') => 'chart',
        request()->routeIs('login.*') => 'shield',
        default => 'spark',
    };
  @endphp
  <section class="page-jumbotron" @if ($jumbotronTitleIsHeading) aria-labelledby="page-jumbotron-title" @else aria-label="{{ $pageJumbotron['title'] }}" @endif style="--page-jumbotron-image: url('{{ $pageJumbotron['image'] }}');">
    <div class="container-x">
      <div class="page-jumbotron__grid">
        <div class="page-jumbotron__copy">
          <span class="page-jumbotron__eyebrow">{{ $pageJumbotron['eyebrow'] }}</span>
          @if ($jumbotronTitleIsHeading)
            <h1 id="page-jumbotron-title" class="page-jumbotron__title">{{ $pageJumbotron['title'] }}</h1>
          @else
            <p id="page-jumbotron-title" class="page-jumbotron__title">{{ $pageJumbotron['title'] }}</p>
          @endif
        </div>

        <aside class="page-jumbotron__aside" aria-label="Page highlights">
          <span class="page-jumbotron__aside-label">Highlights</span>
          @if ($featuredHighlight)
            <article class="page-jumbotron__feature">
              <div class="page-jumbotron__feature-visual" aria-hidden="true">
                <span class="page-jumbotron__feature-blob"></span>
                <span class="page-jumbotron__feature-icon">
                  @include('partials.page-jumbotron-icon', ['icon' => $featuredHighlightIcon])
                </span>
              </div>
              <div class="page-jumbotron__feature-body">
                <h3 class="page-jumbotron__feature-title">{{ $featuredHighlight['label'] }}</h3>
                <p>{{ $featuredHighlight['value'] }}</p>
              </div>
            </article>
          @endif
        </aside>
      </div>
    </div>
  </section>
@endif
