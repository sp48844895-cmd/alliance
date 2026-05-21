{{--
  Recursive tree node.
  Variables: $node (object), $depth (int, default 0)
--}}
@php $depth = $depth ?? 0; $isChild = $depth > 0; @endphp

<li class="lc-tree-item {{ $isChild ? 'lc-tree-item--child' : '' }}"
    id="lc-item-{{ $node->id }}"
    data-lc-item-id="{{ $node->id }}"
    data-depth="{{ $depth }}"
    role="treeitem">

  <div class="lc-tree-row {{ $isChild ? 'lc-tree-row--child' : '' }}">

    {{-- Clickable label --}}
    <button type="button"
            class="lc-tree-btn"
            data-lc-cat="{{ $node->id }}"
            data-lc-has-children="{{ count($node->children) > 0 ? 'true' : 'false' }}"
            title="{{ e($node->cat_name) }}">

      <span class="lc-tree-icon-wrap" aria-hidden="true">
        <i class="{{ $node->cat_icon ?: 'bi bi-folder' }}"></i>
      </span>

      <span class="lc-tree-label">{{ $node->cat_name }}</span>

      @if ($node->total_count > 0)
        <span class="lc-tree-badge">{{ $node->total_count }}</span>
      @endif

    </button>

    {{-- Expand toggle (only when children exist) --}}
    @if (count($node->children) > 0)
      <button type="button"
              class="lc-tree-toggle"
              data-lc-toggle="{{ $node->id }}"
              aria-label="Expand {{ e($node->cat_name) }}"
              aria-expanded="false">
        <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2.5"
             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M9 18l6-6-6-6"/>
        </svg>
      </button>
    @endif

  </div>

  {{-- Nested children --}}
  @if (count($node->children) > 0)
    <div class="lc-tree-children" id="lc-children-{{ $node->id }}">
      <div class="lc-tree-children-inner">
        <ul class="lc-tree-list" role="group">
          @foreach ($node->children as $child)
            @include('partials.lc-tree-node', ['node' => $child, 'depth' => $depth + 1])
          @endforeach
        </ul>
      </div>
    </div>
  @endif

</li>
