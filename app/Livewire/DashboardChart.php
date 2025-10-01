<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Dataset;

class DashboardChart extends Component
{
    public array $datasets = [];      // daftar dataset dari DB
    public array $selectedDatasets = []; // daftar ID dataset terpilih
    public ?string $current = null;   // dropdown pilihan saat ini

    public array $series = [];
    public array $labels = [];

    public function mount()
    {
        // Ambil semua dataset langsung dari DB
        $this->datasets = Dataset::with('category')->get()
            ->map(fn($ds) => [
                'id' => $ds->id,
                'title' => $ds->title,
                'category' => $ds->category->name ?? '-'
            ])
            ->toArray();
    }

    public function addDataset()
    {
        if ($this->current && !in_array($this->current, $this->selectedDatasets)) {
            $this->selectedDatasets[] = $this->current;
            $this->current = null;
            $this->refreshChart();
        }
    }

    public function removeDataset($id)
    {
        $this->selectedDatasets = array_values(array_filter(
            $this->selectedDatasets,
            fn($ds) => $ds != $id
        ));
        $this->refreshChart();
    }

    public function refreshChart()
    {
        $this->series = [];
        $this->labels = [];

        $datasets = Dataset::with(['values', 'category'])->whereIn('id', $this->selectedDatasets)->get();

        foreach ($datasets as $dataset) {
            $dataPoints = $dataset->values->map(fn($v) => (float) $v->value)->toArray();
            $labels = $dataset->values->map(fn($v) => date('Y', strtotime($v->date)))->toArray();

            $this->series[] = [
                'name' => $dataset->title,
                'data' => $dataPoints,
            ];

            $this->labels = $labels;
        }
    }

    public function render()
    {
        return view('livewire.dashboard-chart')
            ->layout('layouts.app');
    }
}
