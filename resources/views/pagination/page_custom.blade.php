<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@if ($paginator->hasPages())
    <ul class="flex justify-center mt-4 space-x-2">
        {{-- Nút Previous --}}
        @if ($paginator->onFirstPage())
            <li class="px-3 py-1 text-gray-400 bg-gray-200 rounded">Prev</li>
        @else
            <li>
                <a class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600"
                   href="{{ $paginator->previousPageUrl() }}">Prev</a>
            </li>
        @endif

        {{-- Các trang --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="px-3 py-1 text-gray-500">{{ $element }}</li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="px-3 py-1 bg-blue-700 text-white rounded">{{ $page }}</li>
                    @else
                        <li>
                            <a class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600"
                               href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Nút Next --}}
        @if ($paginator->hasMorePages())
            <li>
                <a class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600"
                   href="{{ $paginator->nextPageUrl() }}">Next</a>
            </li>
        @else
            <li class="px-3 py-1 text-gray-400 bg-gray-200 rounded">Next</li>
        @endif
    </ul>
@endif
