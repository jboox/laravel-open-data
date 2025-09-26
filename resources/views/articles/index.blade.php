@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <x-breadcrumb :links="['Articles' => null]" />

    <h1 class="text-2xl font-bold mb-6">Artikel Data Bicara</h1>

    <!-- Search + Sort + Per page -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6 items-center">
        <!-- Search -->
        <form method="GET" action="{{ route('articles.index') }}" class="flex gap-2 col-span-1 md:col-span-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari artikel..."
                   class="border rounded px-3 py-2 w-full">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Cari</button>
        </form>

        <!-- Sort + Per page -->
        <div class="flex gap-2 justify-end col-span-1">
            <form method="GET" action="{{ route('articles.index') }}">
                @foreach(request()->except(['sort']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <select name="sort" onchange="this.form.submit()" class="border rounded px-3 py-2">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Paling banyak views</option>
                </select>
            </form>

            <form method="GET" action="{{ route('articles.index') }}">
                @foreach(request()->except(['per_page']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <select name="per_page" onchange="this.form.submit()" class="border rounded px-3 py-2">
                    @foreach([10, 25, 50] as $size)
                        <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                            {{ $size }} per halaman
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- Article List -->
    @forelse($articles as $article)
        <div class="mb-6 border rounded-lg p-4 shadow-sm bg-white">
            <h2 class="text-xl font-semibold text-blue-600">
                <a href="{{ route('articles.show', $article->id) }}">Baca Selengkapnya →</a>
            </h2>
            <p class="text-gray-600 text-sm">
                Oleh: {{ $article->author->name ?? 'Unknown' }} |
                Dipublikasikan: {{ $article->published_at?->format('d M Y') }}
            </p>
            <p class="mt-2 text-gray-800">{{ Str::limit(strip_tags($article->content), 150, '...') }}</p>

            <div class="flex items-center gap-4 mt-3 text-sm text-gray-500">
                👁️ {{ $article->views ?? 0 }} views
                <a href="{{ route('articles.show', $article->slug) }}" class="text-blue-600 hover:underline ml-auto">
                    Baca Selengkapnya →
                </a>
            </div>
        </div>
    @empty
        <p class="text-gray-600">Tidak ada artikel ditemukan.</p>
    @endforelse

    <!-- Pagination Info -->
    @if ($articles->count())
        <div class="flex justify-between items-center mt-6 text-sm text-gray-600">
            <p>
                Menampilkan {{ $articles->firstItem() }}–{{ $articles->lastItem() }}
                dari {{ $articles->total() }} artikel
            </p>
            {{ $articles->links() }}
        </div>
    @endif
</div>
@endsection
