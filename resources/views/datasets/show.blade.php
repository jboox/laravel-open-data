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

    <!-- Dropdown Download -->
    <div class="mt-6">
        <div class="relative inline-block text-left">
            <button type="button"
                class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
                id="menu-button" aria-expanded="true" aria-haspopup="true">
                ⬇️ Download Dataset
                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown menu -->
            <div class="origin-top-left absolute mt-2 w-44 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden"
                id="download-menu" role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
                <div class="py-1" role="none">
                    <a href="{{ route('datasets.download', [$dataset->id, 'csv']) }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    role="menuitem">CSV</a>
                    <a href="{{ route('datasets.download', [$dataset->id, 'xlsx']) }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    role="menuitem">Excel (XLSX)</a>
                    <a href="{{ route('datasets.download', [$dataset->id, 'json']) }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    role="menuitem">JSON</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle dropdown
        document.addEventListener('DOMContentLoaded', () => {
            const button = document.getElementById('menu-button');
            const menu = document.getElementById('download-menu');

            button.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });

            // Tutup menu kalau klik di luar
            document.addEventListener('click', (event) => {
                if (!button.contains(event.target) && !menu.contains(event.target)) {
                    menu.classList.add('hidden');
                }
            });
        });
    </script>
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
