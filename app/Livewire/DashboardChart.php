<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Dataset;

class DashboardChart extends Component
{
    public $datasets;
    public $selected = [];

    public function mount()
    {
        // load semua dataset
        $this->datasets = Dataset::with(['category', 'values.region'])->get();
    }

    public function render()
    {
        $selectedDatasets = Dataset::with(['category', 'values.region'])
            ->whereIn('id', $this->selected)
            ->get();

        return view('livewire.dashboard-chart', [
            'datasets' => $this->datasets,
            'selectedDatasets' => $selectedDatasets,
        ]);
    }
}
