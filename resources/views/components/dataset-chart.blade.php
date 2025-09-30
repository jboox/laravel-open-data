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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@php
    $chartData = $dataset->values->map(fn($v) => [
        'date' => $v->date,
        'region' => $v->region->name ?? '-',
        'value' => $v->value,
    ]);
@endphp

<script>
    const ctx = document.getElementById('datasetChart').getContext('2d');
    let chart;
    const rawData = @json($chartData);

    function renderChart(type = 'line', startYear = null, endYear = null, region = null) {
        const filtered = rawData.filter(item => {
            const year = item.date.substring(0, 4);
            if (startYear && year < startYear) return false;
            if (endYear && year > endYear) return false;
            if (region && item.region !== region) return false;
            return true;
        });

        const labels = filtered.map(item => item.date.substring(0, 4));
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
