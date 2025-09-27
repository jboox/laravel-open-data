<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Hasil Pencarian: "{{ $q }}"</h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto px-4">
        <h3 class="text-lg font-semibold mb-3">Datasets</h3>
        @forelse($datasets as $dataset)
            <div class="mb-4 border p-4 rounded shadow-sm hover:shadow-md transition">
                <a href="{{ route('datasets.show', $dataset->id) }}" class="font-bold text-blue-600">
                    {{ $dataset->title }}
                </a>
                <p class="text-sm text-gray-600">{{ $dataset->description }}</p>
            </div>
        @empty
            <p class="text-gray-500">Tidak ada dataset ditemukan.</p>
        @endforelse

        <h3 class="text-lg font-semibold mt-6 mb-3">Articles</h3>
        @forelse($articles as $article)
            <div class="mb-4 border p-4 rounded shadow-sm hover:shadow-md transition">
                <a href="{{ route('articles.show', $article->slug) }}" class="font-bold text-blue-600">
                    {{ $article->title }}
                </a>
                <p class="text-sm text-gray-600">{{ Str::limit($article->content, 100) }}</p>
            </div>
        @empty
            <p class="text-gray-500">Tidak ada artikel ditemukan.</p>
        @endforelse
    </div>
</x-app-layout>
