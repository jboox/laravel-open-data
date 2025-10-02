<div id="dataset-chart-{{ $dataset->id }}" class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 mb-6"></div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const data = @json($aggregatedValues);

    // ambil semua tahun unik & urutkan
    const years = [...new Set(data.map(item => item.year))].sort((a, b) => a - b);

    // group by region
    const grouped = {};
    data.forEach(item => {
        const key = item.region_name + ' (Lvl ' + item.region_level + ')';
        if (!grouped[key]) grouped[key] = {};
        grouped[key][item.year] = parseFloat(item.total_value);
    });

    // bikin series untuk chart
    const series = Object.keys(grouped).map(region => {
        return {
            name: region,
            data: years.map(year => grouped[region][year] ?? 0)
        }
    });

    const options = {
        chart: {
            type: 'line',
            height: 350,
            toolbar: { show: true }
        },
        theme: {
            mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
        },
        series: series,
        xaxis: {
            categories: years
        },
        stroke: { width: 2 },
        markers: { size: 4 }
    };

    const chart = new ApexCharts(document.querySelector("#dataset-chart-{{ $dataset->id }}"), options);
    chart.render();
});
</script>
@endpush
