@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <x-breadcrumb :links="['Datasets' => null]" />
    <h1 class="text-2xl font-bold mb-6">Dataset Explorer</h1>

    <!-- Search & Filter -->
    <form method="GET" action="{{ route('datasets.index') }}" class="flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari dataset..."
               class="border rounded px-3 py-1">
        <select name="category" class="border rounded px-3 py-1">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded">Cari</button>
    </form>

    <!-- Sort + Per page -->
    <div class="flex gap-2">
        <form method="GET" action="{{ route('datasets.index') }}">
            @foreach(request()->except(['sort', 'per_page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <select name="sort" onchange="this.form.submit()" class="border rounded px-3 py-1">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Paling banyak views</option>
                <option value="downloads" {{ request('sort') == 'downloads' ? 'selected' : '' }}>Paling banyak downloads</option>
            </select>
        </form>

        <form method="GET" action="{{ route('datasets.index') }}">
            @foreach(request()->except(['per_page']) as $key => $value)
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
    </div>

    <!-- Dataset List -->
    @foreach($datasets as $dataset)
        <div class="bg-white shadow-md rounded p-4 mb-4">
            <h2 class="text-xl font-semibold">
                {{ $dataset->title }}
            </h2>
            <p class="text-gray-600 text-sm mb-2">
                Kategori: {{ $dataset->category->name ?? '-' }} |
                Oleh: {{ $dataset->author->name ?? 'Unknown' }} |
                Dipublikasikan: {{ $dataset->published_at?->format('d M Y') }}
            </p>

            <p class="mb-3">{{ Str::limit($dataset->description, 150) }}</p>
            <a href="{{ route('datasets.show', $dataset->id) }}"
               class="text-blue-500 hover:underline">
                Lihat Detail →
            </a>
        </div>
    @endforeach\

    @if ($datasets->count())
    <div class="flex justify-between items-center mt-6 text-sm text-gray-600">
        <p>
            Menampilkan {{ $datasets->firstItem() }}–{{ $datasets->lastItem() }}
            dari {{ $datasets->total() }} dataset
        </p>
        {{ $datasets->links() }}
    </div>
    @endif

    <div class="mt-6">
        {{ $datasets->links() }}
    </div>
</div>
@endsection
