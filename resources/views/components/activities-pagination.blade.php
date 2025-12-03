@if ($paginator->hasPages())
<nav class="flex items-center space-x-1">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
    <span class="px-3 py-1 text-xs border rounded opacity-50">Prev</span>
    @else
    <a href="{{ $paginator->previousPageUrl() }}"
        class="activities-pagination-link px-3 py-1 text-xs border rounded hover:bg-gray-50">
        Prev
    </a>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
    {{-- "Three Dots" Separator --}}
    @if (is_string($element))
    <span class="px-3 py-1 text-xs border rounded opacity-50">{{ $element }}</span>
    @endif

    {{-- Array Of Links --}}
    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())
    <span class="px-3 py-1 text-xs border rounded bg-blue-500 text-white">
        {{ $page }}
    </span>
    @else
    <a href="{{ $url }}"
        class="activities-pagination-link px-3 py-1 text-xs border rounded hover:bg-gray-50">
        {{ $page }}
    </a>
    @endif
    @endforeach
    @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}"
        class="activities-pagination-link px-3 py-1 text-xs border rounded hover:bg-gray-50">
        Next
    </a>
    @else
    <span class="px-3 py-1 text-xs border rounded opacity-50">Next</span>
    @endif
</nav>
@endif
