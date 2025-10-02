<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DatasetValue;
use App\Models\Dataset;
use App\Models\Region;
use Illuminate\Http\Request;

class DatasetValueController extends Controller
{
    public function index()
    {
        $values = DatasetValue::with(['dataset', 'region'])->paginate(10);
        return view('admin.dataset_values.index', compact('values'));
    }

    public function create()
    {
        $datasets = Dataset::all();
        $regions = Region::all();
        return view('admin.dataset_values.create', compact('datasets', 'regions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'dataset_id' => 'required|exists:datasets,id',
            'region_id' => 'required|exists:regions,id',
            'date' => 'required|date',
            'value' => 'required|numeric',
        ]);

        DatasetValue::create($data);
        return redirect()->route('admin.dataset-values.index')->with('success', 'Dataset Value created');
    }

    public function edit(DatasetValue $datasetValue)
    {
        $datasets = Dataset::all();
        $regions = Region::all();
        return view('admin.dataset_values.edit', compact('datasetValue', 'datasets', 'regions'));
    }

    public function update(Request $request, DatasetValue $datasetValue)
    {
        $data = $request->validate([
            'dataset_id' => 'required|exists:datasets,id',
            'region_id' => 'required|exists:regions,id',
            'date' => 'required|date',
            'value' => 'required|numeric',
        ]);

        $datasetValue->update($data);
        return redirect()->route('admin.dataset-values.index')->with('success', 'Dataset Value updated');
    }

    public function destroy(DatasetValue $datasetValue)
    {
        $datasetValue->delete();
        return redirect()->route('admin.dataset-values.index')->with('success', 'Dataset Value deleted');
    }
}
