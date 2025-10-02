@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">Daftar Wilayah</h1>
<a href="{{ route('admin.regions.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">+ Tambah Wilayah</a>

<table class="w-full mt-4 border-collapse border">
    <thead>
        <tr class="bg-gray-100">
            <th class="border px-3 py-2">Nama</th>
            <th class="border px-3 py-2">Level</th>
            <th class="border px-3 py-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($regions as $region)
        <tr>
            <td class="border px-3 py-2">{{ $region->name }}</td>
            <td class="border px-3 py-2">{{ $region->level }}</td>
            <td class="border px-3 py-2">
                <a href="{{ route('admin.regions.edit', $region) }}" class="text-blue-600">Edit</a>
                <form action="{{ route('admin.regions.destroy', $region) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Yakin hapus?')" class="text-red-600 ml-2">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $regions->links() }}
@endsection
