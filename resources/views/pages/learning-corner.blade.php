@extends('layouts.app')

@section('title', 'Learning Corner · ChhattisgarhABC')
@section('meta_description', 'Explore SBC learning themes, videos, books, posters and training modules through an organised knowledge tree — browse by category, subtopic, or material type.')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
@endpush

@section('content')
<main id="main" class="lc-main">

  {{-- ── Explorer: sidebar + content ─────────────────────── --}}
  <div class="lc-explorer" id="lc-explorer">

    {{-- ── Sidebar toggle (mobile) ──────────────────────── --}}
    <div class="lc-sidebar-backdrop" id="lc-backdrop" aria-hidden="true"></div>

    <button type="button" class="lc-sidebar-fab" id="lc-sidebar-fab" aria-label="Open category tree">
      <i class="bi bi-diagram-3-fill" aria-hidden="true"></i>
      <span>Topics</span>
    </button>

    {{-- ── Left Sidebar ──────────────────────────────────── --}}
    <aside class="lc-sidebar" id="lc-sidebar" aria-label="Category navigation tree">

      <div class="lc-sidebar__header">
        <span class="lc-sidebar__title">
          <i class="bi bi-diagram-3" aria-hidden="true"></i>
          Browse topics
        </span>
        <button type="button" class="lc-sidebar__close" id="lc-sidebar-close" aria-label="Close sidebar">
          <i class="bi bi-x-lg" aria-hidden="true"></i>
        </button>
      </div>

      {{-- Search within tree --}}
      <div class="lc-tree-search">
        <i class="bi bi-search lc-tree-search__icon" aria-hidden="true"></i>
        <input type="search"
               class="lc-tree-search__input"
               id="lc-tree-search"
               placeholder="Filter topics…"
               autocomplete="off"
               aria-label="Search topics" />
      </div>

      {{-- Collapse all control --}}
      <div class="lc-tree-controls">
        <button type="button" class="lc-tree-ctrl-btn" id="lc-expand-all" aria-label="Expand all categories">
          <i class="bi bi-arrows-expand" aria-hidden="true"></i> Expand all
        </button>
        <button type="button" class="lc-tree-ctrl-btn" id="lc-collapse-all" aria-label="Collapse all categories">
          <i class="bi bi-arrows-collapse" aria-hidden="true"></i> Collapse
        </button>
      </div>

      {{-- Tree navigation --}}
      <nav class="lc-tree-nav" aria-label="Learning topic tree">
        @if (count($categoryTree) > 0)
          <ul class="lc-tree-list lc-tree-list--root" role="tree">
            @foreach ($categoryTree as $rootNode)
              @include('partials.lc-tree-node', ['node' => $rootNode, 'depth' => 0])
            @endforeach
          </ul>
        @else
          <div class="lc-tree-empty">
            <i class="bi bi-folder-x" aria-hidden="true"></i>
            <p>No categories found.</p>
          </div>
        @endif
      </nav>

    </aside>

    {{-- ── Right Content Panel ────────────────────────────── --}}
    <div class="lc-content" id="lc-content">

      {{-- Breadcrumb --}}
      <nav class="lc-breadcrumb" id="lc-breadcrumb" aria-label="Category breadcrumb" hidden>
        <ol class="lc-breadcrumb__list" id="lc-breadcrumb-list"></ol>
      </nav>

      {{-- Content area (JS-driven) --}}
      <div class="lc-content-body" id="lc-content-body">

        {{-- Welcome / initial state --}}
        <div class="lc-welcome" id="lc-welcome">
          <div class="lc-welcome__icon">
            <i class="bi bi-diagram-3-fill" aria-hidden="true"></i>
          </div>
          <h2 class="lc-welcome__title">Start exploring</h2>
          <p class="lc-welcome__sub">Select any topic from the tree on the left to browse its learning resources — books, videos, posters, and training modules.</p>

          {{-- Top-level category quick cards --}}
          @if (count($categoryTree) > 0)
            <div class="lc-quick-cats" id="lc-quick-cats">
              @foreach ($categoryTree as $node)
                <button type="button"
                        class="lc-quick-cat-card"
                        data-lc-cat="{{ $node->id }}"
                        aria-label="Browse {{ e($node->cat_name) }}">
                  <span class="lc-quick-cat-card__icon">
                    @include('partials.lucide-icon', ['class' => $node->cat_icon ?: 'icon-folder'])
                  </span>
                  <span class="lc-quick-cat-card__name">{{ $node->cat_name }}</span>
                  @if ($node->total_count > 0)
                    <span class="lc-quick-cat-card__count">{{ $node->total_count }} item{{ $node->total_count !== 1 ? 's' : '' }}</span>
                  @endif
                  @if (count($node->children) > 0)
                    <span class="lc-quick-cat-card__subs">
                      <i class="bi bi-diagram-3" aria-hidden="true"></i>
                      {{ count($node->children) }} {{ count($node->children) === 1 ? 'subtopic' : 'subtopics' }}
                    </span>
                  @endif
                </button>
              @endforeach
            </div>
          @endif
        </div>

        {{-- Loading skeleton (hidden by default) --}}
        <div class="lc-loading" id="lc-loading" hidden style="display:none">
          <div class="lc-loading__header">
            <div class="lc-skeleton lc-skeleton--title"></div>
            <div class="lc-skeleton lc-skeleton--sub"></div>
          </div>
          <div class="lc-resource-grid">
            @for ($i = 0; $i < 6; $i++)
              <div class="lc-skeleton-card">
                <div class="lc-skeleton lc-skeleton--cover"></div>
                <div class="lc-skeleton-card__body">
                  <div class="lc-skeleton lc-skeleton--tag"></div>
                  <div class="lc-skeleton lc-skeleton--line"></div>
                  <div class="lc-skeleton lc-skeleton--line lc-skeleton--short"></div>
                </div>
              </div>
            @endfor
          </div>
        </div>

        {{-- Category detail (JS-populated) --}}
        <div class="lc-category-detail" id="lc-category-detail" hidden style="display:none">

          <div class="lc-cat-header" id="lc-cat-header">
            {{-- Populated by JS --}}
          </div>

          {{-- Resource grid --}}
          <div class="lc-resource-grid" id="lc-resource-grid">
            {{-- Populated by JS --}}
          </div>

          {{-- Empty state --}}
          <div class="lc-resource-empty" id="lc-resource-empty" hidden style="display:none">
            <i class="bi bi-inbox" aria-hidden="true"></i>
            <h3>No resources yet</h3>
            <p>This topic doesn't have any learning material uploaded yet.</p>
          </div>

        </div>

        {{-- Error state (hidden by default) --}}
        <div class="lc-error" id="lc-error" hidden style="display:none">
          <i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
          <h3>Something went wrong</h3>
          <p>Could not load the resources. Please try again.</p>
          <button type="button" class="lc-btn-retry" id="lc-retry">Try again</button>
        </div>

      </div>
    </div>
  </div>

</main>
@endsection

@push('scripts')
<script>
  window.lcConfig = {
    ajaxBase: '{{ url('/learning-corner/cat') }}',
    csrfToken: '{{ csrf_token() }}',
  };
</script>
@endpush
