@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white dark:bg-gray-900 rounded-2xl shadow">
    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">
        Analitik Perbandingan Dataset
    </h2>

    <!-- Dropdown + Tombol -->
    <div class="flex gap-2 mb-4">
        <select id="datasetSelect"
            class="w-full rounded-lg border-gray-300 dark:border-gray-700 
                   bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
            <option value="">Pilih dataset...</option>
            @foreach($datasets as $dataset)
                <option value="{{ $dataset->id }}">{{ $dataset->title }} ({{ $dataset->category->name ?? '-' }})</option>
            @endforeach
        </select>
        <button id="addDataset"
            class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow 
                   hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition">
            Tambah
        </button>
    </div>

    <!-- Daftar dataset terpilih -->
    <div id="selectedDatasets" class="flex flex-wrap gap-2 mb-6"></div>

    <!-- Chart -->
    <div id="chart" class="bg-white dark:bg-gray-800 rounded-xl shadow p-4"></div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet" />
<style>
/* Patch Dark Mode untuk TomSelect */
.dark .ts-control,
.dark .ts-dropdown {
    background-color: #1f2937; /* gray-800 */
    color: #f9fafb;           /* gray-100 */
    border-color: #374151;    /* gray-700 */
}
.dark .ts-dropdown .option:hover {
    background-color: #374151; /* hover gray-700 */
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi TomSelect
    new TomSelect("#datasetSelect", {
        create: false,
        sortField: { field: "text", direction: "asc" },
        placeholder: "Cari dataset...",
    });

    let selected = [];
    let chart = new ApexCharts(document.querySelector("#chart"), {
        chart: {
            type: 'line',
            height: 400,
            toolbar: { show: true }
        },
        theme: {
            mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
        },
        series: [],
        xaxis: { categories: [] },
        stroke: { width: 2 },
        markers: { size: 4 }
    });
    chart.render();

    // Fungsi random warna
    function randomColor() {
        return `hsl(${Math.floor(Math.random() * 360)}, 70%, 50%)`;
    }

    // Update chart
    async function updateChart() {
        let series = [];
        let labels = [];

        for (let item of selected) {
            try {
                let res = await fetch(`/api/datasets/${item.id}`);
                let json = await res.json();

                let values = json.data.values || [];
                let data = values.map(v => parseFloat(v.value));
                labels = values.map(v => new Date(v.date).getFullYear());

                series.push({
                    name: json.data.title,
                    data: data,
                    color: item.color
                });
            } catch (e) {
                console.error("Gagal fetch dataset", item.id, e);
            }
        }

        chart.updateOptions({
            xaxis: { categories: labels }
        });
        chart.updateSeries(series);
    }

    // Render chip dataset
    function renderSelected() {
        let container = document.getElementById('selectedDatasets');
        container.innerHTML = '';
        selected.forEach(item => {
            let chip = document.createElement('div');
            chip.className = 'flex items-center gap-2 px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-full text-sm shadow-sm';
            chip.innerHTML = `
                <span style="color:${item.color}" class="font-bold">●</span> ${item.name}
                <button class="ml-2 text-red-500 hover:text-red-700 text-sm">✕</button>
            `;
            chip.querySelector('button').addEventListener('click', () => {
                selected = selected.filter(s => s.id !== item.id);
                renderSelected();
                updateChart();
            });
            container.appendChild(chip);
        });
    }

    // Tambah dataset
    document.getElementById('addDataset').addEventListener('click', () => {
        let select = document.getElementById('datasetSelect');
        let id = select.value;
        let name = select.options[select.selectedIndex]?.text;

        if (id && !selected.find(s => s.id == id)) {
            selected.push({ id, name, color: randomColor() });
            renderSelected();
            updateChart();
        }
    });
});
</script>
@endpush
