@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">

    <h1 class="text-2xl font-bold mb-6">Analitik Perbandingan Dataset</h1>
    <!-- Form pilih dataset -->
    <form method="GET" action="{{ route('dashboard') }}" class="mb-6">
        <h2 class="text-lg font-semibold mb-2">Pilih Dataset untuk dibandingkan:</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
            @foreach($datasets as $ds)
                <label class="flex items-center gap-2 border p-2 rounded">
                    <input type="checkbox" name="datasets[]" value="{{ $ds->id }}"
                            {{ in_array($ds->id, request()->input('datasets', [])) ? 'checked' : '' }}>
                    <span>{{ $ds->title }} ({{ $ds->category->name ?? '-' }})</span>
                </label>
            @endforeach
            <livewire:dashboard-chart />
        </div>
        <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">
            Tampilkan Chart
        </button>
    </form>

    <!-- Chart -->
    @if($selectedDatasets->count())
    <x-multi-dataset-chart :datasets="$selectedDatasets" />
    @else
        <p class="text-gray-600">Pilih minimal satu dataset untuk menampilkan grafik.</p>
    @endif
    <livewire:dashboard-chart />
</div>
@endsection
