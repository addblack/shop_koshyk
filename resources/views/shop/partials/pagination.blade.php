@if ($paginator->hasPages())
<nav class="pagination">
    @if ($paginator->onFirstPage())
        <span class="pagination__btn disabled"><i class="fas fa-chevron-left"></i></span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="pagination__btn"><i class="fas fa-chevron-left"></i></a>
    @endif

    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="pagination__dots">{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="pagination__btn active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="pagination__btn">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="pagination__btn"><i class="fas fa-chevron-right"></i></a>
    @else
        <span class="pagination__btn disabled"><i class="fas fa-chevron-right"></i></span>
    @endif
</nav>
@endif
