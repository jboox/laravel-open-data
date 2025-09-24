@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">
    <x-breadcrumb :links="['Datasets' => route('datasets.index'), $dataset->title => null]" />
    <h1 class="text-3xl font-bold mb-2">{{ $dataset->title }}</h1>
    <p class="text-gray-600 text-sm mb-4">
        Kategori: {{ $dataset->category->name ?? '-' }} |
        Oleh: {{ $dataset->author->name ?? 'Unknown' }} |
        Dipublikasikan: {{ $dataset->published_at?->format('d M Y') }}
    </p>
    <p class="mb-6">{{ $dataset->description }}</p>

    <h2 class="text-xl font-semibold mb-3">Data Series</h2>
    @if($dataset->values->count())
        <!-- Filter Options -->
        <div class="flex flex-wrap gap-4 mb-6">
            <!-- Chart Type Selector -->
            <div>
                <label for="chartType" class="block text-sm font-medium text-gray-700">Tipe Chart</label>
                <select id="chartType" class="border rounded px-3 py-2">
                    <option value="line">Line</option>
                    <option value="bar">Bar</option>
                </select>
            </div>

            <!-- Year Range -->
            <div>
                <label for="startYear" class="block text-sm font-medium text-gray-700">Dari Tahun</label>
                <input type="number" id="startYear" class="border rounded px-3 py-2 w-28"
                       value="{{ $dataset->values->min('date')?->format('Y') }}">
            </div>
            <div>
                <label for="endYear" class="block text-sm font-medium text-gray-700">Sampai Tahun</label>
                <input type="number" id="endYear" class="border rounded px-3 py-2 w-28"
                       value="{{ $dataset->values->max('date')?->format('Y') }}">
            </div>

            <div class="flex items-end">
                <button id="applyFilter" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Terapkan
                </button>
            </div>
        </div>

        <!-- Tabel Data -->
        <table class="min-w-full border mb-6">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border">Tanggal</th>
                    <th class="px-4 py-2 border">Wilayah</th>
                    <th class="px-4 py-2 border">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dataset->values as $val)
                <tr>
                    <td class="px-4 py-2 border">{{ $val->date?->format('Y') ?? '-' }}</td>
                    <td class="px-4 py-2 border">{{ $val->region->name ?? '-' }}</td>
                    <td class="px-4 py-2 border">{{ number_format($val->value) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Chart -->
        <h2 class="text-xl font-semibold mb-3">Visualisasi</h2>
        <canvas id="datasetChart" height="100"></canvas>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const rawLabels = @json($dataset->values->pluck('date')->map(fn($d) => $d?->format('Y')));
            const rawValues = @json($dataset->values->pluck('value'));

            let chartType = 'line';

            const ctx = document.getElementById('datasetChart').getContext('2d');
            let datasetChart = new Chart(ctx, {
                type: chartType,
                data: {
                    labels: rawLabels,
                    datasets: [{
                        label: '{{ $dataset->title }}',
                        data: rawValues,
                        borderColor: 'rgba(37, 99, 235, 1)',
                        backgroundColor: 'rgba(37, 99, 235, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    }
                }
            });

            // Ganti Chart Type
            document.getElementById('chartType').addEventListener('change', function() {
                chartType = this.value;
                datasetChart.destroy();
                datasetChart = new Chart(ctx, {
                    type: chartType,
                    data: {
                        labels: rawLabels,
                        datasets: [{
                            label: '{{ $dataset->title }}',
                            data: rawValues,
                            borderColor: 'rgba(37, 99, 235, 1)',
                            backgroundColor: 'rgba(37, 99, 235, 0.2)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: datasetChart.options
                });
            });

            // Filter tahun
            document.getElementById('applyFilter').addEventListener('click', function() {
                const startYear = parseInt(document.getElementById('startYear').value);
                const endYear = parseInt(document.getElementById('endYear').value);

                const filteredLabels = [];
                const filteredValues = [];

                rawLabels.forEach((year, index) => {
                    if (year >= startYear && year <= endYear) {
                        filteredLabels.push(year);
                        filteredValues.push(rawValues[index]);
                    }
                });

                datasetChart.data.labels = filteredLabels;
                datasetChart.data.datasets[0].data = filteredValues;
                datasetChart.update();
            });
        </script>
    @else
        <p class="text-gray-500">Belum ada data series.</p>
    @endif
</div>
@endsection
