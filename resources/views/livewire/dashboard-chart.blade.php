<div class="space-y-4">
    <div class="grid gap-2 md:grid-cols-3">
        @foreach($datasets as $d)
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" value="{{ $d['id'] }}" wire:model.live="selected" class="rounded border-gray-300">
                <span>{{ $d['title'] }}</span>
            </label>
        @endforeach
    </div>

    <div class="rounded-lg border p-4">
        <div id="apex-chart" style="min-height: 320px;"></div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
      document.addEventListener('livewire:init', () => {
        let chartEl = document.querySelector('#apex-chart');
        let chart = null;

        function render(series, labels) {
          const options = {
            chart: { type: 'line', height: 320 },
            series: series,
            xaxis: { categories: labels },
            stroke: { width: 3 },
            noData: { text: 'No data selected' }
          };
          if (chart) { chart.destroy(); }
          chart = new ApexCharts(chartEl, options);
          chart.render();
        }

        // First render from server-side props
        render(@json($series), @json($labels));

        Livewire.on('chart-updated', (payload) => {
          render(payload.series ?? [], payload.labels ?? []);
        });
      });
    </script>
    @endpush
</div>
