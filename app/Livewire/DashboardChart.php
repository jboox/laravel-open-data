<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Dataset;
use Carbon\Carbon;

class DashboardChart extends Component
{
    public $datasets;
    public array $selected = []; // sekarang array associative

    public $series = [];
    public $labels = [];

    public function mount()
    {
        $this->datasets = Dataset::with(['category', 'values'])->get();

        // default pilih dataset pertama
        if ($this->datasets->count() > 0) {
            $first = $this->datasets->first()->id;
            $this->selected[$first] = true;
        }

        $this->refreshChart();
    }

    public function updatedSelected()
    {
        $this->refreshChart();
    }

    protected function refreshChart()
    {
        // Ambil id yang dicentang (key dengan nilai true)
        $selectedIds = collect($this->selected)
            ->filter(fn($checked) => $checked)
            ->keys()
            ->map(fn($id) => (int) $id)
            ->all();

        \Log::info('Selected raw', $this->selected);
        \Log::info('Selected IDs', $selectedIds);

        $labels = [];
        $series = [];

        if (!empty($selectedIds)) {
            $selectedDatasets = Dataset::with(['category', 'values'])
                ->whereIn('id', $selectedIds)
                ->get();

            // kumpulkan semua tahun
            $years = collect();
            foreach ($selectedDatasets as $dataset) {
                foreach ($dataset->values as $val) {
                    if ($val->date) {
                        $years->push(Carbon::parse($val->date)->format('Y'));
                    }
                }
            }
            $labels = $years->unique()->sort()->values()->all();

            // bangun series per dataset
            foreach ($selectedDatasets as $dataset) {
                $valuesByYear = [];
                foreach ($labels as $y) {
                    $vals = $dataset->values
                        ->filter(fn($v) => Carbon::parse($v->date)->format('Y') === $y)
                        ->pluck('value')
                        ->map(fn($v) => is_numeric($v) ? (float)$v : null)
                        ->filter();

                    $valuesByYear[$y] = $vals->count() ? round($vals->avg(), 2) : null;
                }

                $series[] = [
                    'name' => $dataset->title,
                    'data' => array_map(fn($y) => $valuesByYear[$y] ?? null, $labels),
                ];
            }
        }

        $this->labels = $labels;
        $this->series = $series;

        \Log::info('DashboardChart series', $this->series);
        \Log::info('DashboardChart labels', $this->labels);

        $this->dispatch('chart-updated', series: $this->series, labels: $this->labels);
    }

    public function render()
    {
        return view('livewire.dashboard-chart');
    }
}
