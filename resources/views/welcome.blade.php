@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">

    <!-- Hero -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Sikka Open Data</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            Portal data terbuka Kabupaten Sikka. Temukan, gunakan, dan ceritakan data untuk semua.
        </p>
    </div>

    <!-- Statistik Singkat -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 bg-gray-100 p-4 mb-12">
        <div class="p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow hover:shadow-lg transition text-center">
            <div class="text-blue-600 text-4xl mb-2">📊</div>
            <p class="text-3xl font-extrabold text-blue-700">{{ $stats['datasets'] }}</p>
            <p class="text-gray-700 font-medium">Dataset</p>
        </div>
        <div class="p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow hover:shadow-lg transition text-center">
            <div class="text-green-600 text-4xl mb-2">📰</div>
            <p class="text-3xl font-extrabold text-green-700">{{ $stats['articles'] }}</p>
            <p class="text-gray-700 font-medium">Artikel</p>
        </div>
        <div class="p-6 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl shadow hover:shadow-lg transition text-center">
            <div class="text-indigo-600 text-4xl mb-2">📂</div>
            <p class="text-3xl font-extrabold text-indigo-700">{{ $stats['categories'] }}</p>
            <p class="text-gray-700 font-medium">Kategori</p>
        </div>
        <div class="p-6 bg-gradient-to-br from-red-50 to-red-100 rounded-xl shadow hover:shadow-lg transition text-center">
            <div class="text-red-600 text-4xl mb-2">⬇️</div>
            <p class="text-3xl font-extrabold text-red-700">{{ $stats['downloads'] }}</p>
            <p class="text-gray-700 font-medium">Total Downloads</p>
        </div>
    </div>

    <!-- Search Global -->
    <div class="flex justify-center mt-10 mb-12">
        <form action="{{ route('search.global') }}" method="GET" class="w-full max-w-2xl">
            <div class="relative flex items-center">
                <!-- Input -->
                <input 
                    type="text" 
                    id="global-search"
                    name="q" 
                    value="{{ request('q') }}"
                    placeholder="Cari dataset atau artikel..." 
                    class="flex-grow px-4 py-2 rounded-full border border-gray-300 
                   focus:outline-none focus:ring-2 focus:ring-blue-400
                   dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600">

                <!-- Tombol Cari -->
                <button type="submit" 
                    class="ml-4 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-full shadow-md transform transition duration-200 hover:scale-105 dark:bg-blue-600 dark:hover:bg-blue-700">
                    Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Dataset Terbaru -->
    <div class="mb-12">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold dark:text-gray-100">Dataset Terbaru</h2>
            <a href="{{ route('datasets.index') }}" class="text-blue-600 hover:underline">Lihat semua →</a>
        </div>
        <div class="grid md:grid-cols-2 gap-6">
            @forelse($latestDatasets as $dataset)
                <div class="border rounded-lg p-5 shadow-sm bg-white hover:shadow-md hover:scale-[1.01] transition transform dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200">
                    <h3 class="text-lg font-bold text-blue-600">
                        <a href="{{ route('datasets.show', $dataset->id) }}">{{ $dataset->title }}</a>
                    </h3>
                    <p class="text-sm text-gray-600 mb-2">
                        📂 {{ $dataset->category->name ?? '-' }} |
                        📅 {{ $dataset->published_at?->format('d M Y') }}
                    </p>
                    <p class="text-gray-800">{{ Str::limit($dataset->description, 120, '...') }}</p>
                </div>
            @empty
                <p class="text-gray-600 dark:text-gray-400">Belum ada dataset tersedia.</p>
            @endforelse
        </div>
    </div>

    <!-- Artikel Terbaru -->
    <div>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold dark:text-gray-100">Artikel Terbaru</h2>
            <a href="{{ route('articles.index') }}" class="text-blue-600 hover:underline dark:text-gray-400">Lihat semua →</a>
        </div>
        <div class="grid md:grid-cols-2 gap-6">
            @forelse($latestArticles as $article)
                <div class="border rounded-lg p-5 shadow-sm bg-white hover:shadow-md hover:scale-[1.01] transition transform">
                    <h3 class="text-lg font-bold text-blue-600">
                        <a href="{{ route('articles.show', $article->id) }}">{{ $article->title }}</a>
                    </h3>
                    <p class="text-sm text-gray-600 mb-2 dark:text-gray-400">
                        Oleh: {{ $article->author->name ?? 'Unknown' }} |
                        📅 {{ $article->published_at?->format('d M Y') }}
                    </p>
                    <p class="text-gray-800">{{ Str::limit(strip_tags($article->content), 120, '...') }}</p>
                </div>
            @empty
                <p class="text-gray-600 dark:text-gray-400">Belum ada artikel tersedia.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
