@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">Analitik Perbandingan Dataset</h2>

    {{-- Dropdown + tombol --}}
    <div class="flex items-center space-x-2 mb-4">
        <select id="datasetSelect" class="border rounded p-2">
            <option value="">Pilih dataset...</option>
            @foreach(\App\Models\Dataset::with('category')->get() as $ds)
                <option value="{{ $ds->id }}">{{ $ds->title }} ({{ $ds->category->name ?? '-' }})</option>
            @endforeach
        </select>
        <button id="addBtn" class="bg-blue-500 hover:bg-blue-600 text-black px-4 py-2 rounded">
            Tambah
        </button>
    </div>

    {{-- Daftar dataset terpilih --}}
    <div id="selectedList" class="flex flex-wrap gap-2 mb-6"></div>

    {{-- Chart --}}
    <div id="chart"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    const selected = [];
    const series = [];
    let chart;

    // init chart kosong
    chart = new ApexCharts(document.querySelector("#chart"), {
        chart: { type: 'line', height: 350 },
        series: [],
        xaxis: { categories: [] }
    });
    chart.render();

    async function addDataset(id, label) {
        if (!id || selected.includes(id)) return;

        selected.push(id);

        // tampilkan chip/tag
        const list = document.getElementById('selectedList');
        const span = document.createElement('span');
        span.className = "px-3 py-1 bg-gray-200 rounded-full flex items-center space-x-2";
        span.innerHTML = `${label} <button class="text-red-600 ml-2" onclick="removeDataset(${id}, this)">✕</button>`;
        list.appendChild(span);

        console.log("Fetching dataset:", id);

        try {
            const res = await fetch(`/api/datasets/${id}`);
            const json = await res.json();
            console.log("API response:", json);

            if (json.data) {
                const dataset = json.data;
                const dataPoints = dataset.values.map(v => parseFloat(v.value));
                const labels = dataset.values.map(v => new Date(v.date).getFullYear());

                series.push({
                    name: dataset.title,
                    data: dataPoints
                });

                chart.updateOptions({
                    series: series,
                    xaxis: { categories: labels }
                });
            }
        } catch (err) {
            console.error("Fetch error:", err);
        }
    }

    function removeDataset(id, el) {
        const index = selected.indexOf(id);
        if (index > -1) {
            selected.splice(index, 1);
            series.splice(index, 1);
            el.parentElement.remove();
            chart.updateOptions({ series: series });
        }
    }

    document.getElementById('addBtn').addEventListener('click', () => {
        const select = document.getElementById('datasetSelect');
        const id = parseInt(select.value);
        const label = select.options[select.selectedIndex].text;
        if (id) {
            addDataset(id, label);
            select.value = '';
        }
    });
</script>
@endsection
