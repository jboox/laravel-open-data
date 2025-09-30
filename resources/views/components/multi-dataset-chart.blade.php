@if($datasets->count())
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">Perbandingan Dataset</h2>
        <canvas id="multiDatasetChart" height="120"></canvas>
    </div>
@endif

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@php
    $chartData = $datasets->map(function($dataset) {
        return [
            'title' => $dataset->title,
            'values' => $dataset->values->map(fn($v) => [
                'date' => $v->date,
                'region' => $v->region->name ?? '-',
                'value' => $v->value,
            ]),
        ];
    });
@endphp

<script>
    const ctxMulti = document.getElementById('multiDatasetChart').getContext('2d');
    let multiChart;

    const datasetsRaw = @json($chartData);

    const labels = [...new Set(datasetsRaw.flatMap(ds => ds.values.map(v => v.date.substring(0, 4))))].sort();

    const datasetsForChart = datasetsRaw.map((ds, index) => {
        return {
            label: ds.title,
            data: labels.map(year => {
                const found = ds.values.find(v => v.date.substring(0, 4) === year);
                return found ? found.value : null;
            }),
            borderColor: `hsl(${index * 60}, 70%, 50%)`,
            backgroundColor: `hsl(${index * 60}, 70%, 70%)`,
            fill: false
        }
    });

    if (multiChart) multiChart.destroy();
    multiChart = new Chart(ctxMulti, {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasetsForChart
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Perbandingan Beberapa Dataset'
                }
            }
        }
    });
</script>
