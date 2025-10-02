@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4 dark:text-gray-100">Edit Dataset</h1>

<form action="{{ route('admin.datasets.update', $dataset) }}" method="POST" class="space-y-4">
    @csrf @method('PUT')
    <div>
        <label class="block dark:text-gray-200">Judul</label>
        <input type="text" name="title" value="{{ $dataset->title }}" class="w-full border px-3 py-2 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200" required>
    </div>
    <div>
        <label class="block dark:text-gray-200">Deskripsi</label>
        <textarea name="description" class="w-full border px-3 py-2 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200">{{ $dataset->description }}</textarea>
    </div>
    <div>
        <label class="block dark:text-gray-200">Kategori</label>
        <select name="category_id" class="w-full border px-3 py-2 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200">
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @if($dataset->category_id==$cat->id) selected @endif>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block dark:text-gray-200">Tanggal Publikasi</label>
        <input type="date" name="published_at" value="{{ optional($dataset->published_at)->format('Y-m-d') }}" class="w-full border px-3 py-2 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200">
    </div>
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
</form>
@endsection
