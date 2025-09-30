@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6">Analitik Perbandingan Dataset</h1>

    <!-- Panggil komponen multi-dataset-chart -->
    <x-multi-dataset-chart :datasets="$datasets" />

    <!-- Tambahin info dataset yang dibandingkan -->
    <div class="mt-6">
        <h2 class="text-lg font-semibold mb-3">Dataset yang dibandingkan:</h2>
        <ul class="list-disc pl-5 text-gray-700">
            @foreach($datasets as $dataset)
                <li>{{ $dataset->title }} ({{ $dataset->category->name ?? 'Tanpa Kategori' }})</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
