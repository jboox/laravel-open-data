@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <x-breadcrumb :links="['Datasets' => route('datasets.index'), $dataset->title => null]" />

    <!-- Judul -->
    <h1 class="text-3xl font-bold mb-3 text-gray-900">{{ $dataset->title }}</h1>

    <!-- Meta Info -->
    <div class="flex flex-wrap items-center text-sm text-gray-600 mb-6 gap-2">
        <span>📂 {{ $dataset->category->name ?? '-' }}</span>
        <span>•</span>
        <span>📝 Oleh: {{ $dataset->author->name ?? 'Unknown' }}</span>
        <span>•</span>
        <span>📅 {{ $dataset->published_at?->format('d M Y') }}</span>
        <span>•</span>
        <span>👁️ {{ $dataset->views }} views</span>
        <span>•</span>
        <span>⬇️ {{ $dataset->downloads }} downloads</span>
    </div>

    <!-- Deskripsi -->
    <div class="prose max-w-none text-gray-800 mb-8">
        {!! nl2br(e($dataset->description)) !!}
    </div>

    <!-- Chart Visualisasi -->
    @if($dataset->values->count())
        <x-dataset-chart :dataset="$dataset" :aggregated-values="$aggregatedValues" />
    @endif

    <!-- Tabel Data Agregasi -->
    @if($aggregatedValues->count())
        <div class="overflow-x-auto mb-6">
            <table class="w-full border-collapse text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 text-left">
                    <tr>
                        <th class="px-4 py-2 border">Tahun</th>
                        <th class="px-4 py-2 border">Wilayah</th>
                        <th class="px-4 py-2 border">Level</th>
                        <th class="px-4 py-2 border">Total Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aggregatedValues as $row)
                        <tr class="border-b dark:border-gray-600">
                            <td class="px-4 py-2 border">{{ $row->year }}</td>
                            <td class="px-4 py-2 border">{{ $row->region_name }}</td>
                            <td class="px-4 py-2 border">{{ ucfirst($row->region_level_name) }}</td>
                            <td class="px-4 py-2 border">{{ number_format($row->total_value, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500 dark:text-gray-400">Tidak ada data agregasi untuk dataset ini.</p>
    @endif


    <!-- Dropdown Download -->
    <div class="mt-6">
        <x-download-dropdown :dataset="$dataset" />
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@php
    $chartData = $dataset->values->map(function($v) {
        return [
            'date' => $v->date, // misalnya: 2020-01-01 00:00:00
            'region' => $v->region->name ?? '-',
            'value' => $v->value,
        ];
    });
@endphp


<script>
    const ctx = document.getElementById('datasetChart').getContext('2d');
let chart;

// Ambil data mentah
const rawData = @json($chartData);

function renderChart(type = 'line', startYear = null, endYear = null, region = null) {
    // Filter data
    const filtered = rawData.filter(item => {
        const year = item.date.substring(0, 4); // ambil 4 digit pertama
        if (startYear && year < startYear) return false;
        if (endYear && year > endYear) return false;
        if (region && item.region !== region) return false;
        return true;
    });

    const labels = filtered.map(item => item.date.substring(0, 4)); // tampilkan tahun
    const data = filtered.map(item => item.value);

    if (chart) chart.destroy();
    chart = new Chart(ctx, {
        type,
        data: {
            labels,
            datasets: [{
                label: "{{ $dataset->title }}",
                data,
                borderColor: "rgb(37, 99, 235)",
                backgroundColor: "rgba(37, 99, 235, 0.5)",
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
}

function updateChart() {
    const type = document.getElementById('chart-type').value;
    const startYear = document.getElementById('start-year').value;
    const endYear = document.getElementById('end-year').value;
    const region = document.getElementById('region-filter').value;
    renderChart(type, startYear, endYear, region);
}

// Render awal
renderChart();


</script>
@endsection
