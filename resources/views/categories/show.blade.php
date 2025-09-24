@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <x-breadcrumb :links="['Kategori' => null, $category->name => null]" />
    <h1 class="text-3xl font-bold mb-6">Kategori: {{ $category->name }}</h1>
    <p class="mb-6 text-gray-600">{{ $category->description }}</p>

    <!-- Search dalam kategori -->
    <form method="GET" action="{{ route('categories.show', $category->slug) }}" class="flex items-center gap-4 mb-6">
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Cari dataset dalam kategori ini..."
               class="border rounded px-3 py-2 w-64">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Cari
        </button>
    </form>

    <!-- Dataset List -->
    @forelse($datasets as $dataset)
        <div class="bg-white shadow-md rounded p-4 mb-4">
            <h2 class="text-xl font-semibold">
                <a href="{{ route('datasets.show', $dataset->id) }}" class="text-blue-600 hover:underline">
                    {{ $dataset->title }}
                </a>
            </h2>
            <p class="text-gray-600 text-sm mb-2">
                Oleh: {{ $dataset->author->name ?? 'Unknown' }} |
                Dipublikasikan: {{ $dataset->published_at?->format('d M Y') }}
            </p>
            <p class="mb-3">{{ Str::limit($dataset->description, 150) }}</p>
        </div>
    @empty
        <p class="text-gray-500">Belum ada dataset dalam kategori ini.</p>
    @endforelse

    <div class="mt-6">
        {{ $datasets->links() }}
    </div>
</div>
@endsection
