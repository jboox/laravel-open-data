@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4">

    <!-- Hero Section -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold mb-4 text-blue-700">Sikka Open Data</h1>
        <p class="text-gray-600 text-lg mb-6">
            Portal data terbuka Kabupaten Sikka – transparan, mudah diakses, dan bermanfaat untuk semua.
        </p>

        <!-- Global Search -->
        <form method="GET" action="{{ route('home') }}" class="flex justify-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Cari dataset atau artikel..."
                   class="border rounded px-4 py-2 w-1/2">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Cari
            </button>
        </form>
    </div>

    <!-- Search Results -->
    @if($searchResults)
        <div class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">Hasil Pencarian</h2>

            <!-- Dataset Results -->
            <div class="mb-6">
                <h3 class="text-xl font-bold mb-2">Dataset</h3>
                @forelse($searchResults['datasets'] as $dataset)
                    <div class="bg-white shadow rounded p-4 mb-3">
                        <a href="{{ route('datasets.show', $dataset->id) }}" class="text-blue-600 font-semibold hover:underline">
                            {{ $dataset->title }}
                        </a>
                        <p class="text-sm text-gray-500">
                            Kategori: {{ $dataset->category->name ?? '-' }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500">Tidak ada dataset ditemukan.</p>
                @endforelse
            </div>

            <!-- Article Results -->
            <div>
                <h3 class="text-xl font-bold mb-2">Artikel</h3>
                @forelse($searchResults['articles'] as $article)
                    <div class="bg-white shadow rounded p-4 mb-3">
                        <a href="{{ route('articles.show', $article->slug) }}" class="text-blue-600 font-semibold hover:underline">
                            {{ $article->title }}
                        </a>
                        <p class="text-sm text-gray-500">
                            Dipublikasikan: {{ $article->published_at?->format('d M Y') }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500">Tidak ada artikel ditemukan.</p>
                @endforelse
            </div>
        </div>
    @endif

    <!-- Latest Datasets -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold mb-4">Dataset Terbaru</h2>
        <div class="grid md:grid-cols-2 gap-6">
            @forelse($latestDatasets as $dataset)
                <div class="bg-white shadow rounded p-5">
                    <h3 class="text-lg font-bold mb-2">
                        <a href="{{ route('datasets.show', $dataset->id) }}" class="text-blue-600 hover:underline">
                            {{ $dataset->title }}
                        </a>
                    </h3>
                    <p class="text-sm text-gray-500 mb-2">
                        Kategori: {{ $dataset->category->name ?? '-' }} |
                        Dipublikasikan: {{ $dataset->published_at?->format('d M Y') }}
                    </p>
                    <p class="text-gray-700">{{ Str::limit($dataset->description, 120) }}</p>
                </div>
            @empty
                <p class="text-gray-500">Belum ada dataset.</p>
            @endforelse
        </div>
        <div class="mt-4 text-right">
            <a href="{{ route('datasets.index') }}" class="text-blue-600 hover:underline">Lihat semua dataset →</a>
        </div>
    </div>

    <!-- Latest Articles -->
    <div>
        <h2 class="text-2xl font-semibold mb-4">Artikel Terbaru</h2>
        <div class="grid md:grid-cols-3 gap-6">
            @forelse($latestArticles as $article)
                <div class="bg-white shadow rounded p-5">
                    <h3 class="text-lg font-bold mb-2">
                        <a href="{{ route('articles.show', $article->slug) }}" class="text-blue-600 hover:underline">
                            {{ $article->title }}
                        </a>
                    </h3>
                    <p class="text-sm text-gray-500 mb-2">
                        {{ $article->published_at?->format('d M Y') }}
                    </p>
                    <p class="text-gray-700">{{ Str::limit(strip_tags($article->content), 100) }}</p>
                </div>
            @empty
                <p class="text-gray-500">Belum ada artikel.</p>
            @endforelse
        </div>
        <div class="mt-4 text-right">
            <a href="{{ route('articles.index') }}" class="text-blue-600 hover:underline">Lihat semua artikel →</a>
        </div>
    </div>

</div>
@endsection
