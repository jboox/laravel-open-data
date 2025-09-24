@props(['links' => []])

<nav class="text-sm mb-6" aria-label="Breadcrumb">
    <ol class="flex items-center text-gray-600 list-none p-0 m-0">
        <li>
            <a href="{{ url('/') }}" class="hover:text-blue-600">Home</a>
        </li>

        @foreach($links as $label => $url)
            <li><span class="mx-2">/</span></li>
            @if($url)
                <li>
                    <a href="{{ $url }}" class="hover:text-blue-600">{{ $label }}</a>
                </li>
            @else
                <li class="text-gray-800 font-semibold">{{ $label }}</li>
            @endif
        @endforeach
    </ol>
</nav>
