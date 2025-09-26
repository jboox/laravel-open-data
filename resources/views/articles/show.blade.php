@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <x-breadcrumb :links="['Articles' => route('articles.index'), $article->title => null]" />
    
    <!-- Judul -->
    <h1 class="text-3xl font-bold mb-3 text-gray-900">{{ $article->title }}</h1>
    
    <!-- Meta info -->
    <div class="flex flex-wrap items-center text-sm text-gray-600 mb-6 gap-2">
        <span>📝 Oleh: {{ $article->author->name ?? 'Unknown' }}</span>
        <span>•</span>
        <span>📅 {{ $article->published_at?->format('d M Y') }}</span>
        <span>•</span>
        <span>👁️ {{ $article->views ?? 0 }} views</span>
    </div>

    <!-- Isi Artikel -->
    <div class="prose max-w-none text-gray-800">
        {!! $article->content !!}
    </div>

    <!-- Tombol Kembali -->
    <div class="mt-8">
        <a href="{{ route('articles.index') }}"
           class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            ← Kembali ke Artikel
        </a>
    </div>
</div>
@endsection
