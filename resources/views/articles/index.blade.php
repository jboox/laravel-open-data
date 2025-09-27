@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <x-breadcrumb :links="['Articles' => null]" />

    <h1 class="text-2xl font-bold mb-6">Artikel Data Bicara</h1>

    <!-- Filter & Search -->
    <form method="GET" action="{{ route('articles.index') }}" class="mb-6">
        <!-- Baris 1: Sortir & Per Page -->
        <div class="flex flex-wrap gap-2 mb-3">
            <!-- Sort -->
            <select name="sort" onchange="this.form.submit()" class="border rounded px-3 py-2 w-48">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Paling banyak views</option>
            </select>

            <!-- Per Page -->
            <select name="per_page" onchange="this.form.submit()" class="border rounded px-3 py-2 w-40">
                @foreach([10, 25, 50] as $size)
                    <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                        {{ $size }} per halaman
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Baris 2: Search -->
        <div class="flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari artikel..."
                   class="flex-grow border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                Cari
            </button>
        </div>
    </form>

    <!-- Article List -->
    @forelse($articles as $article)
        <div class="mb-6 border rounded-lg p-4 shadow-sm bg-white">
            <h2 class="text-xl font-semibold text-blue-600">
                <a href="{{ route('articles.show', $article->id) }}">{{ $article->title }}</a>
            </h2>
            <p class="text-gray-600 text-sm">
                Oleh: {{ $article->author->name ?? 'Unknown' }} |
                Dipublikasikan: {{ $article->published_at?->format('d M Y') }}
            </p>
            <p class="mt-2 text-gray-800">{{ Str::limit(strip_tags($article->content), 150, '...') }}</p>

            <div class="flex items-center gap-4 mt-3 text-sm text-gray-500">
                👁️ {{ $article->views ?? 0 }} views
                <a href="{{ route('articles.show', $article->id) }}" class="text-blue-600 hover:underline ml-auto">
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
