@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <x-breadcrumb :links="['Datasets' => null]" />
    <h1 class="text-2xl font-bold mb-6">Dataset Explorer</h1>

    <!-- Search & Filter -->
    <form method="GET" action="{{ route('datasets.index') }}" class="flex flex-wrap items-center gap-4 mb-6">
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Cari dataset..."
               class="border rounded px-3 py-2 w-64">

        <select name="category" class="border rounded px-3 py-2">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Cari
        </button>
    </form>

    <!-- Dataset List -->
    @foreach($datasets as $dataset)
        <div class="bg-white shadow-md rounded p-4 mb-4">
            <h2 class="text-xl font-semibold">
                {{ $dataset->title }}
            </h2>
            <p class="text-gray-600 text-sm mb-2">
                Kategori: {{ $dataset->category->name ?? '-' ?? '-' }} |
                Oleh: {{ $dataset->author->name ?? 'Unknown' }} |
                Dipublikasikan: {{ $dataset->published_at?->format('d M Y') }}
            </p>
            <p class="mb-3">{{ Str::limit($dataset->description, 150) }}</p>
            <a href="{{ route('datasets.show', $dataset->id) }}"
               class="text-blue-500 hover:underline">
                Lihat Detail →
            </a>
        </div>
    @endforeach

    <div class="mt-6">
        {{ $datasets->links() }}
    </div>
</div>
@endsection
