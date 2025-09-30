<div>
    <!-- Pilihan dataset -->
    <h2 class="text-lg font-semibold mb-2">Pilih Dataset untuk dibandingkan:</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mb-4">
        @foreach($datasets as $ds)
            <label class="flex items-center gap-2 border p-2 rounded cursor-pointer">
                <input type="checkbox" value="{{ $ds->id }}" wire:model="selected">
                <span>{{ $ds->title }} ({{ $ds->category->name ?? '-' }})</span>
            </label>
        @endforeach
    </div>

    <!-- Chart -->
    @if($selectedDatasets->count())
        <x-multi-dataset-chart :datasets="$selectedDatasets" />
    @else
        <p class="text-gray-600">Pilih minimal satu dataset untuk menampilkan grafik.</p>
    @endif
</div>
