@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <x-breadcrumb :links="['Articles' => null]" />
    <h1 class="text-2xl font-bold mb-6">Artikel Data Bicara</h1>

    <!-- Search -->
    <form method="GET" action="{{ route('articles.index') }}" class="flex items-center gap-4 mb-6">
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Cari artikel..."
               class="border rounded px-3 py-2 w-64">

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Cari
        </button>
    </form>

    <!-- Per page selector -->
    <form method="GET" action="{{ route('articles.index') }}">
        @foreach(request()->except('per_page') as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <select name="per_page" onchange="this.form.submit()" class="border rounded px-3 py-1">
            @foreach([10, 25, 50] as $size)
                <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                    {{ $size }} per halaman
                </option>
            @endforeach
        </select>
    </form>

    <!-- Article List -->
    @foreach($articles as $article)
        <div class="bg-white shadow-md rounded p-4 mb-4">
            <h2 class="text-xl font-semibold mb-2">
                <a href="{{ route('articles.show', $article->slug) }}" class="text-blue-600 hover:underline">
                    {{ $article->title }}
                </a>
            </h2>
            <p class="text-gray-600 text-sm mb-2">
                Oleh: {{ $article->author->name ?? 'Unknown' }} | 
                Dipublikasikan: {{ $article->published_at?->format('d M Y') }}
            </p>
            <p class="mb-3">{{ Str::limit(strip_tags($article->content), 150) }}</p>
            <a href="{{ route('articles.show', $article->slug) }}" class="text-blue-500 hover:underline">
                Baca Selengkapnya →
            </a>
        </div>
    @endforeach

    @if ($articles->count())
    <div class="flex justify-between items-center mt-6 text-sm text-gray-600">
        <p>
            Menampilkan {{ $articles->firstItem() }}–{{ $articles->lastItem() }}
            dari {{ $articles->total() }} artikel
        </p>
        {{ $articles->links() }}
    </div>
    @endif


    <div class="mt-6">
        {{ $articles->links() }}
    </div>
</div>
@endsection
