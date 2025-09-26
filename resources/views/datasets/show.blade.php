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
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Visualisasi Data</h2>

            <!-- Chart Options -->
            <div class="flex flex-wrap gap-3 mb-4">
                <select id="chart-type" class="border rounded px-3 py-2">
                    <option value="line">Line Chart</option>
                    <option value="bar">Bar Chart</option>
                </select>

                <input type="number" id="start-year" class="border rounded px-3 py-2 w-28" placeholder="Tahun Awal" min="1900" max="2100" />
                <input type="number" id="end-year" class="border rounded px-3 py-2 w-28" placeholder="Tahun Akhir" min="1900" max="2100" />

                <select id="region-filter" class="border rounded px-3 py-2">
                    <option value="">Semua Wilayah</option>
                    @foreach($dataset->values->pluck('region.name')->unique() as $region)
                        <option value="{{ $region }}">{{ $region }}</option>
                    @endforeach
                </select>

                <button onclick="updateChart()" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
            </div>

            <canvas id="datasetChart" height="100"></canvas>
        </div>
    @endif

    <!-- Tabel Data -->
    @if($dataset->values->count())
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Data Series</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">Tahun</th>
                            <th class="px-4 py-2 border">Wilayah</th>
                            <th class="px-4 py-2 border">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataset->values as $value)
                            <tr>
                                <td class="px-4 py-2 border">{{ substr($value->date, 0, 4) }}</td>
                                <td class="px-4 py-2 border">{{ $value->region->name ?? '-' }}</td>
                                <td class="px-4 py-2 border">{{ $value->value }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Tombol Download 
    <div class="mt-6">
        <a href="{{ route('datasets.download', $dataset->id) }}"
           class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
            ⬇️ Download Dataset
        </a>
    </div> -->
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
