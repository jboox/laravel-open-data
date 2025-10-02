@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">Daftar Artikel</h1>
<a href="{{ route('admin.articles.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">+ Tambah Artikel</a>

<table class="w-full mt-4 border-collapse border">
    <thead>
        <tr class="bg-gray-100">
            <th class="border px-3 py-2">Judul</th>
            <th class="border px-3 py-2">Tanggal</th>
            <th class="border px-3 py-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($articles as $article)
        <tr>
            <td class="border px-3 py-2">{{ $article->title }}</td>
            <td class="border px-3 py-2">{{ $article->created_at->format('d M Y') }}</td>
            <td class="border px-3 py-2">
                <a href="{{ route('admin.articles.edit', $article) }}" class="text-blue-600">Edit</a>
                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Yakin hapus?')" class="text-red-600 ml-2">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $articles->links() }}
@endsection
