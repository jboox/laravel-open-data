<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Dataset;

class DatasetChart extends Component
{
    public $dataset;
    public $aggregatedValues;

    public function __construct(Dataset $dataset, $aggregatedValues)
    {
        $this->dataset = $dataset;
        $this->aggregatedValues = $aggregatedValues;
    }

    public function render()
    {
        return view('components.dataset-chart');
    }
}
