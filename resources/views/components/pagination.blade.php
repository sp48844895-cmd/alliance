@props([
    'paginator',
    'label' => 'pagination',
    'noun'  => 'items',
    'prefix' => 'st-pagination',
])

@if ($paginator->hasPages())
  <nav class="{{ $prefix }}" aria-label="{{ ucfirst($noun) }} pagination">
    <p class="{{ $prefix }}__summary">
      Showing <b>{{ $paginator->firstItem() }}</b>–<b>{{ $paginator->lastItem() }}</b>
      of <b>{{ $paginator->total() }}</b> {{ $noun }}
    </p>

    <div class="{{ $prefix }}__controls">
      @if ($paginator->onFirstPage())
        <span class="{{ $prefix }}__btn is-disabled" aria-disabled="true">Previous</span>
      @else
        <a class="{{ $prefix }}__btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
      @endif

      <div class="{{ $prefix }}__pages" role="list">
        @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
          @if ($page == $paginator->currentPage())
            <span class="{{ $prefix }}__page is-active" aria-current="page" role="listitem">{{ $page }}</span>
          @else
            <a class="{{ $prefix }}__page" href="{{ $url }}" role="listitem">{{ $page }}</a>
          @endif
        @endforeach
      </div>

      @if ($paginator->hasMorePages())
        <a class="{{ $prefix }}__btn" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
      @else
        <span class="{{ $prefix }}__btn is-disabled" aria-disabled="true">Next</span>
      @endif
    </div>
  </nav>
@endif
