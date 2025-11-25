@if ($paginator->hasPages())
<div class="bg-white px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-4">

    {{-- Showing info --}}
    <div class="text-xs sm:text-sm text-gray-600">
        Showing
        <span class="font-medium text-gray-900">
            {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }}
        </span>
        of
        <span class="font-medium text-gray-900">
            {{ $paginator->total() }}
        </span>
        entries
    </div>

    {{-- Pagination buttons --}}
    <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap justify-center">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
        <button class="px-2.5 sm:px-3 py-1.5 border border-gray-300 rounded text-xs sm:text-sm text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors" disabled>
            < Previous
                </button>
                @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-2.5 sm:px-3 py-1.5 border border-gray-300 rounded text-xs sm:text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    < Previous
                        </a>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach ($elements as $element)

                        {{-- Dots --}}
                        @if (is_string($element))
                        <span class="px-1.5 sm:px-2 text-xs sm:text-sm text-gray-500">{{ $element }}</span>
                        @endif

                        {{-- Page Links --}}
                        @if (is_array($element))
                        @foreach ($element as $page => $url)

                        @if ($page == $paginator->currentPage())
                        <span class="px-2.5 sm:px-3 py-1.5 bg-teal-600 text-white rounded text-xs sm:text-sm font-medium transition-colors">
                            {{ $page }}
                        </span>
                        @else
                        <a href="{{ $url }}" class="px-2.5 sm:px-3 py-1.5 border border-gray-300 rounded text-xs sm:text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            {{ $page }}
                        </a>
                        @endif

                        @endforeach
                        @endif

                        @endforeach

                        {{-- Next --}}
                        @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" class="px-2.5 sm:px-3 py-1.5 border border-gray-300 rounded text-xs sm:text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            Next >
                        </a>
                        @else
                        <button class="px-2.5 sm:px-3 py-1.5 border border-gray-300 rounded text-xs sm:text-sm text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors" disabled>
                            Next >
                        </button>
                        @endif

    </div>
</div>
@endif
