<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Category;
use Illuminate\Http\Request;

class DatasetController extends Controller
{
    public function index()
    {
        $datasets = Dataset::with('category')->paginate(10);
        return view('admin.datasets.index', compact('datasets'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.datasets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'published_at'=> 'nullable|date',
        ]);

        $data['created_by'] = auth()->id();

        Dataset::create($data);
        return redirect()->route('admin.datasets.index')->with('success', 'Dataset created successfully');
    }

    public function edit(Dataset $dataset)
    {
        $categories = Category::all();
        return view('admin.datasets.edit', compact('dataset', 'categories'));
    }

    public function update(Request $request, Dataset $dataset)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'published_at'=> 'nullable|date',
        ]);

        $dataset->update($data);
        return redirect()->route('admin.datasets.index')->with('success', 'Dataset updated successfully');
    }

    public function destroy(Dataset $dataset)
    {
        $dataset->delete();
        return redirect()->route('admin.datasets.index')->with('success', 'Dataset deleted successfully');
    }
}
