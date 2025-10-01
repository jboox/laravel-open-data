<div>
    <h2 class="text-xl font-bold mb-4">Analitik Perbandingan Dataset</h2>

    {{-- Dropdown & tombol tambah --}}
    <div class="flex items-center space-x-2 mb-4">
        <select wire:model="current" class="border rounded p-2">
            <option value="">Pilih dataset...</option>
            @foreach($datasets as $ds)
                <option value="{{ $ds['id'] }}">{{ $ds['title'] }} ({{ $ds['category'] }})</option>
            @endforeach
        </select>
        <button wire:click="addDataset" class="bg-blue-500 text-white px-4 py-2 rounded">
            Tambah
        </button>
    </div>

    {{-- Daftar dataset terpilih --}}
    <div class="flex flex-wrap gap-2 mb-6">
        @foreach($selectedDatasets as $id)
            @php
                $ds = collect($datasets)->firstWhere('id', $id);
            @endphp
            @if($ds)
                <span class="px-3 py-1 bg-gray-200 rounded-full flex items-center space-x-2">
                    <span>{{ $ds['title'] }}</span>
                    <button wire:click="removeDataset({{ $id }})" class="text-red-600">✕</button>
                </span>
            @endif
        @endforeach
    </div>

    {{-- Chart --}}
    <div wire:ignore>
        <div id="chart"></div>
    </div>

    {{-- Debug --}}
    <pre class="mt-4 bg-gray-100 p-2 text-sm">
        Selected: {{ json_encode($selectedDatasets) }}
        Series: {{ json_encode($series) }}
    </pre>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('livewire:navigated', () => {
    const chartEl = document.querySelector("#chart");

    if (chartEl && !chartEl.__apexChart) {
        chartEl.__apexChart = new ApexCharts(chartEl, {
            chart: { type: 'line', height: 350 },
            series: @json($series),
            xaxis: { categories: @json($labels) }
        });
        chartEl.__apexChart.render();
    }

    Livewire.hook('morph.updated', ({ el, component }) => {
        if (chartEl.__apexChart) {
            chartEl.__apexChart.updateOptions({
                series: @json($series),
                xaxis: { categories: @json($labels) }
            });
        }
    });
});
</script>
@endpush
