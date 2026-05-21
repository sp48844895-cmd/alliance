@if ($paginator->hasPages())
    <nav class="pagination-nav" aria-label="Pagination">
        <p class="pagination-meta">
            Showing
            <span class="font-semibold text-[var(--color-ink-2)]">{{ $paginator->firstItem() }}</span>
            to
            <span class="font-semibold text-[var(--color-ink-2)]">{{ $paginator->lastItem() }}</span>
            of
            <span class="font-semibold text-[var(--color-ink-2)]">{{ $paginator->total() }}</span>
            results
        </p>

        <div class="pagination-links">
            @if ($paginator->onFirstPage())
                <span class="pagination-btn is-disabled" aria-disabled="true">
                    <i class="bi bi-chevron-left"></i>
                    <span class="hidden sm:inline">Previous</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-btn" aria-label="Previous page">
                    <i class="bi bi-chevron-left"></i>
                    <span class="hidden sm:inline">Previous</span>
                </a>
            @endif

            <div class="pagination-pages">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="pagination-ellipsis">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="pagination-page is-active" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination-page" aria-label="Go to page {{ $page }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-btn" aria-label="Next page">
                    <span class="hidden sm:inline">Next</span>
                    <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <span class="pagination-btn is-disabled" aria-disabled="true">
                    <span class="hidden sm:inline">Next</span>
                    <i class="bi bi-chevron-right"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
