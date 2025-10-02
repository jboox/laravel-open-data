@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4 dark:text-gray-100">Tambah Artikel</h1>

<form action="{{ route('admin.articles.store') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="block dark:text-gray-200">Judul</label>
        <input type="text" name="title" class="w-full border px-3 py-2 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200" required>
    </div>
    <div>
        <label class="block dark:text-gray-200">Konten</label>
        <textarea name="content" rows="6" class="w-full border px-3 py-2 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200" required></textarea>
    </div>
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
</form>
@endsection
