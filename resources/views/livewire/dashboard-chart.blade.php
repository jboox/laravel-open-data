<div class="space-y-6">

    <!-- Checkbox grid -->
    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($datasets as $ds)
            <label class="flex items-center gap-2 p-3 border rounded-lg shadow-sm bg-white hover:bg-gray-50 cursor-pointer">
                <input type="checkbox" value="{{ $ds->id }}" wire:model="selected" class="text-blue-600 focus:ring-blue-500 rounded">
                <span class="text-sm font-medium text-gray-700">
                    {{ $ds->title }}
                    <span class="block text-xs text-gray-500">({{ $ds->category->name ?? '-' }})</span>
                </span>
            </label>
        @endforeach
    </div>

    <!-- Chart -->
    <div class="rounded-lg border p-4 bg-white">
        <div id="apex-chart" style="min-height: 360px;"></div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            let chartEl = document.querySelector('#apex-chart');
            let chart = null;

            function render(series, labels) {
                const options = {
                    chart: { type: 'line', height: 360 },
                    series: series,
                    xaxis: { categories: labels },
                    stroke: { width: 3 },
                    noData: { text: 'Tidak ada data' }
                };
                if (chart) { chart.destroy(); }
                chart = new ApexCharts(chartEl, options);
                chart.render();
            }

            // Render pertama
            render(@json($series), @json($labels));

            // Update saat Livewire emit event
            Livewire.on('chart-updated', (payload) => {
                render(payload.series ?? [], payload.labels ?? []);
            });
        });
    </script>
    @endpush
</div>
