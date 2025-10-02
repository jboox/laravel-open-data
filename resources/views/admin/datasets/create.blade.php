@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4 dark:text-gray-100">Tambah Dataset</h1>

<form action="{{ route('admin.datasets.store') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="block dark:text-gray-200">Judul</label>
        <input type="text" name="title" class="w-full border px-3 py-2 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200" required>
    </div>
    <div>
        <label class="block dark:text-gray-200">Deskripsi</label>
        <textarea name="description" class="w-full border px-3 py-2 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200"></textarea>
    </div>
    <div>
        <label class="block dark:text-gray-200">Kategori</label>
        <select name="category_id" class="w-full border px-3 py-2 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200">
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block dark:text-gray-200">Tanggal Publikasi</label>
        <input type="date" name="published_at" class="w-full border px-3 py-2 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200">
    </div>
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
</form>
@endsection
