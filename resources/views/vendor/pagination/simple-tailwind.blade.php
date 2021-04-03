@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="pagination flex justify-between">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <div></div>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="button">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="button">
                {!! __('pagination.next') !!}
            </a>
        @else
            <div></div>
        @endif
    </nav>
@endif
