<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Dataset;

class DatasetChart extends Component
{
    public $dataset;

    public function __construct(Dataset $dataset)
    {
        $this->dataset = $dataset;
    }

    public function render()
    {
        return view('components.dataset-chart');
    }
}
