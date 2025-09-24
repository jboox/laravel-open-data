@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <x-breadcrumb :links="['Articles' => route('articles.index'), $article->title => null]" />
    <h1 class="text-3xl font-bold mb-2">{{ $article->title }}</h1>
    <p class="text-gray-600 text-sm mb-6">
        Oleh: {{ $article->author->name ?? 'Unknown' }} | 
        Dipublikasikan: {{ $article->published_at?->format('d M Y') }}
    </p>

    <div class="prose max-w-none">
        {!! $article->content !!}
    </div>
</div>
@endsection
