@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6 dark:text-gray-100">Dashboard Admin</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white dark:bg-gray-800 shadow p-4 rounded">
        <h2 class="text-lg font-semibold dark:text-gray-200">👤 Users</h2>
        <p class="text-2xl font-bold text-blue-600">{{ $stats['users'] }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow p-4 rounded">
        <h2 class="text-lg font-semibold dark:text-gray-200">📂 Kategori</h2>
        <p class="text-2xl font-bold text-blue-600">{{ $stats['categories'] }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow p-4 rounded">
        <h2 class="text-lg font-semibold dark:text-gray-200">🌍 Wilayah</h2>
        <p class="text-2xl font-bold text-blue-600">{{ $stats['regions'] }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow p-4 rounded">
        <h2 class="text-lg font-semibold dark:text-gray-200">📊 Dataset</h2>
        <p class="text-2xl font-bold text-blue-600">{{ $stats['datasets'] }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow p-4 rounded">
        <h2 class="text-lg font-semibold dark:text-gray-200">📰 Artikel</h2>
        <p class="text-2xl font-bold text-blue-600">{{ $stats['articles'] }}</p>
    </div>
</div>
@endsection
