@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <x-breadcrumb :links="['Datasets' => null]" />

    <h1 class="text-2xl font-bold mb-6">Dataset Explorer</h1>

    <!-- Search + Filter + Sort + Per page -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6 items-center">
        <!-- Baris 1: Search + Kategori + Tombol -->
        <form method="GET" action="{{ route('datasets.index') }}" class="mb-4">
            <div class="flex flex-col md:flex-row gap-2">
                <!-- Search input -->
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari dataset..."
                    class="flex-grow border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                <!-- Dropdown kategori -->
                <select name="category"
                        class="border rounded px-3 py-2 w-48 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                <!-- Tombol Cari -->
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                    Cari
                </button>
            </div>
        </form>

        <!-- Baris 2: Sortir & Per Page -->
        <div class="flex justify-end gap-2 mb-6">
            <!-- Sort -->
            <form method="GET" action="{{ route('datasets.index') }}">
                @foreach(request()->except(['sort']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <select name="sort" onchange="this.form.submit()" class="border rounded px-3 py-2">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Paling banyak views</option>
                    <option value="downloads" {{ request('sort') == 'downloads' ? 'selected' : '' }}>Paling banyak downloads</option>
                </select>
            </form>

            <!-- Per Page -->
            <form method="GET" action="{{ route('datasets.index') }}">
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

    <!-- Dataset List -->
    @forelse($datasets as $dataset)
        <div class="mb-6 border rounded-lg p-4 shadow-sm bg-white">
            <h2 class="text-xl font-semibold text-blue-600">
                <a href="{{ route('datasets.show', $dataset) }}">{{ $dataset->title }}</a>
            </h2>
            <p class="text-gray-600 text-sm">
                Kategori: {{ $dataset->category->name ?? '-' }} |
                Oleh: {{ $dataset->author->name ?? 'Unknown' }} |
                Dipublikasikan: {{ $dataset->published_at?->format('d M Y') }}
            </p>
            <p class="mt-2 text-gray-800">{{ $dataset->description }}</p>

            <div class="flex items-center gap-4 mt-3 text-sm text-gray-500">
                👁️ {{ $dataset->views }} views
                ⬇️ {{ $dataset->downloads }} downloads
                <a href="{{ route('datasets.show', $dataset) }}" class="text-blue-600 hover:underline ml-auto">
                    Lihat Detail →
                </a>
            </div>
        </div>
    @empty
        <p class="text-gray-600">Tidak ada dataset ditemukan.</p>
    @endforelse

    <!-- Pagination Info -->
    @if ($datasets->count())
        <div class="flex justify-between items-center mt-6 text-sm text-gray-600">
            <p>
                Menampilkan {{ $datasets->firstItem() }}–{{ $datasets->lastItem() }}
                dari {{ $datasets->total() }} dataset
            </p>
            {{ $datasets->links() }}
        </div>
    @endif
</div>
@endsection
