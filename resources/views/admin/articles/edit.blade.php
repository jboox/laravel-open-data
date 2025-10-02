@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Artikel</h1>

<form action="{{ route('admin.articles.update', $article) }}" method="POST" class="space-y-4">
    @csrf @method('PUT')
    <div>
        <label class="block">Judul</label>
        <input type="text" name="title" value="{{ $article->title }}" class="w-full border px-3 py-2" required>
    </div>
    <div>
        <label class="block">Konten</label>
        <textarea name="content" rows="6" class="w-full border px-3 py-2" required>{{ $article->content }}</textarea>
    </div>
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
</form>
@endsection
