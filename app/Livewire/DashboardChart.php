<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Dataset;

class DashboardChart extends Component
{
    public $datasets;
    public $selected = [];

    public $series = [];
    public $labels = [];

    public function mount()
    {
        $this->datasets = Dataset::with(['category', 'values.region'])->get();

        // default pilih dataset pertama biar chart langsung tampil
        if (empty($this->selected) && $this->datasets->count() > 0) {
            $this->selected = [$this->datasets->first()->id];
        }

        $this->refreshChart();
    }

    public function updatedSelected()
    {
        $this->refreshChart();
    }

    protected function refreshChart()
    {
        $labels = [];
        $series = [];

        if (!empty($this->selected)) {
            // Ambil tahun unik dari semua dataset terpilih
            $years = [];
            foreach ($this->selected as $datasetId) {
                $dataset = Dataset::with('values')->find($datasetId);
                if (!$dataset) continue;

                foreach ($dataset->values as $val) {
                    $year = \Carbon\Carbon::parse($val->date)->format('Y');
                    $years[$year] = true;
                }
            }
            $labels = array_keys($years);
            sort($labels);

            // Build data series
            foreach ($this->selected as $datasetId) {
                $dataset = Dataset::with('values')->find($datasetId);
                if (!$dataset) continue;

                $valuesByYear = [];
                foreach ($labels as $y) {
                    $vals = $dataset->values->filter(fn($v) => \Carbon\Carbon::parse($v->date)->format('Y') === $y)->pluck('value');
                    $valuesByYear[$y] = $vals->count() ? $vals->avg() : null;
                }

                $series[] = [
                    'name' => $dataset->title,
                    'data' => array_map(fn($y) => $valuesByYear[$y] ?? null, $labels),
                ];
            }
        }

        $this->labels = $labels;
        $this->series = $series;

        // Emit event untuk chart JS
        $this->dispatch('chart-updated', series: $this->series, labels: $this->labels);
    }

    public function render()
    {
        return view('livewire.dashboard-chart');
    }
}
