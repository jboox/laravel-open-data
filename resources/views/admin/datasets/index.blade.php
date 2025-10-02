@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4 dark:text-gray-100">Daftar Dataset</h1>
<a href="{{ route('admin.datasets.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">+ Tambah Dataset</a>

<table class="w-full mt-4 border-collapse border">
    <thead>
        <tr class="bg-gray-100">
            <th class="border px-3 py-2">Judul</th>
            <th class="border px-3 py-2">Kategori</th>
            <th class="border px-3 py-2">Dipublikasikan</th>
            <th class="border px-3 py-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($datasets as $dataset)
        <tr>
            <td class="border px-3 py-2">{{ $dataset->title }}</td>
            <td class="border px-3 py-2">{{ $dataset->category->name ?? '-' }}</td>
            <td class="border px-3 py-2">{{ $dataset->published_at }}</td>
            <td class="border px-3 py-2">
                <a href="{{ route('admin.datasets.edit', $dataset) }}" class="text-blue-600">Edit</a>
                <form action="{{ route('admin.datasets.destroy', $dataset) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Yakin hapus?')" class="text-red-600 ml-2">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $datasets->links() }}
@endsection
