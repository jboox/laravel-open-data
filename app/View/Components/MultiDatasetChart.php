<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Database\Eloquent\Collection;

class MultiDatasetChart extends Component
{
    public $datasets;

    /**
     * @param Collection $datasets
     */
    public function __construct($datasets)
    {
        $this->datasets = $datasets;
    }

    public function render()
    {
        return view('components.multi-dataset-chart');
    }
}
