<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Dataset;

class DashboardChart extends Component
{
    /** @var array<int,int> */
    public array $selected = [];

    /** @var array<int, array{name:string,data:array<int,float|int|null>}> */
    public array $series = [];

    /** @var array<int,string> */
    public array $labels = [];

    /** @var array<int, array{id:int,title:string}> */
    public array $datasets = [];

    public function mount(): void
    {
        $this->datasets = Dataset::select('id','title')
            ->orderBy('title')
            ->get()
            ->map(fn ($d) => ['id' => (int)$d->id, 'title' => (string)$d->title])
            ->all();

        // Auto-select dataset pertama agar chart tidak kosong
        if (empty($this->selected) && !empty($this->datasets)) {
            $this->selected = [$this->datasets[0]['id']];
        }

        $this->refreshSeries();
    }

    public function updatedSelected(): void
    {
        $this->refreshSeries();
    }

    protected function refreshSeries(): void
    {
        // Contoh agregasi sederhana per tahun
        $labels = [];
        $series = [];

        if (!empty($this->selected)) {
            $years = [];
            // Kumpulkan label tahun dari semua dataset terpilih
            foreach ($this->selected as $datasetId) {
                $dataset = Dataset::with(['values' => fn($q) => $q->orderBy('date')])->find($datasetId);
                if (!$dataset) {
                    continue;
                }
                foreach ($dataset->values as $v) {
                    $y = (string)($v->date instanceof \Carbon\Carbon ? $v->date->format('Y') : \Carbon\Carbon::parse($v->date)->format('Y'));
                    $years[$y] = true;
                }
            }
            $labels = array_values(array_keys($years));
            sort($labels);

            // Bangun series per dataset (contoh: rata-rata per tahun)
            foreach ($this->selected as $datasetId) {
                $dataset = Dataset::with(['values' => fn($q) => $q->orderBy('date')])->find($datasetId);
                if (!$dataset) {
                    continue;
                }

                $byYear = [];
                foreach ($labels as $y) {
                    $vals = $dataset->values->filter(function ($v) use ($y) {
                        $yy = (string)($v->date instanceof \Carbon\Carbon ? $v->date->format('Y') : \Carbon\Carbon::parse($v->date)->format('Y'));
                        return $yy === $y;
                    })->pluck('value')->all();

                    $byYear[$y] = count($vals) ? array_sum($vals)/count($vals) : null;
                }

                $series[] = [
                    'name' => (string)$dataset->title,
                    'data' => array_map(fn($y) => $byYear[$y] ?? null, $labels),
                ];
            }
        }

        $this->labels = $labels;
        $this->series = $series;

        // Emit event untuk JS chart front-end
        $this->dispatch('chart-updated', series: $this->series, labels: $this->labels);
    }

    public function render()
    {
        return view('livewire.dashboard-chart');
    }
}
