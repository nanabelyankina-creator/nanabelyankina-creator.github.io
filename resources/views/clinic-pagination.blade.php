

@if ($paginator->hasPages())
    <nav class="clinic-pagination" aria-label="Pagination Navigation">
        {{-- Prev --}}
        @if ($paginator->onFirstPage())
            <span class="clinic-page-btn clinic-page-btn--disabled">
                <svg class="clinic-page-arrow" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                          clip-rule="evenodd" />
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="clinic-page-btn" rel="prev">
                <svg class="clinic-page-arrow" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                          clip-rule="evenodd" />
                </svg>
            </a>
        @endif

        {{-- Номера страниц --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="clinic-page-ellipsis">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="clinic-page-number clinic-page-number--active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="clinic-page-number">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="clinic-page-btn" rel="next">
                <svg class="clinic-page-arrow" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                          clip-rule="evenodd" />
                </svg>
            </a>
        @else
            <span class="clinic-page-btn clinic-page-btn--disabled">
                <svg class="clinic-page-arrow" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                          clip-rule="evenodd" />
                </svg>
            </span>
        @endif
    </nav>
@endif