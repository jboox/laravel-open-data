@props(['links' => []])

<nav class="text-sm mb-6" aria-label="Breadcrumb">
    <ol class="flex items-center text-gray-600 space-x-2">
        <!-- Home -->
        <li class="flex items-center">
            <a href="{{ url('/') }}" class="flex items-center hover:text-blue-600">
                <!-- Ikon Home -->
                <svg class="w-4 h-4 mr-1 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 3.172L3.172 10h2.828v6h4v-4h2v4h4v-6h2.828L10 3.172z"/>
                </svg>
                Home
            </a>
        </li>

        @foreach($links as $label => $url)
            <!-- Separator -->
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </li>

            <!-- Link atau teks aktif -->
            <li class="flex items-center">
                @if($url)
                    <a href="{{ $url }}" class="hover:text-blue-600">{{ $label }}</a>
                @else
                    <span class="text-gray-800 font-semibold">{{ $label }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
