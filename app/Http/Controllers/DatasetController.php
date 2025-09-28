<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DatasetController extends Controller
{
    public function index()
    {
        $datasets = Dataset::latest()->paginate(10);
        return view('datasets.index', compact('datasets'));
    }

    public function create()
    {
        return view('datasets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'nullable|string|max:100',
            'file'        => 'required|mimes:csv,xlsx,json|max:20480', // 20MB
        ]);

        $path = $request->file('file')->store('datasets', 'public');

        Dataset::create([
            'title'       => $request->title,
            'description' => $request->description,
            'category'    => $request->category,
            'file_path'   => $path,
            'user_id'     => auth()->id(),
        ]);

        return redirect()->route('datasets.index')
                         ->with('success', 'Dataset berhasil diupload!');
    }

    public function show(Dataset $dataset)
    {
        $dataset->increment('views');
        return view('datasets.show', compact('dataset'));
    }

    // DatasetController.php
    public function download(Dataset $dataset)
    {
        $dataset->increment('downloads');

        $response = new StreamedResponse(function () use ($dataset) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Tanggal', 'Wilayah', 'Nilai']);

            foreach ($dataset->values as $value) {
                fputcsv($handle, [
                    $value->date ? \Carbon\Carbon::parse($value->date)->format('Y-m-d') : '-',
                    optional($value->region)->name ?? '-',
                    $value->value,
                ]);
            }

            fclose($handle);
        });

        $filename = \Str::slug($dataset->title) . '.csv';

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");

        return $response;
    }

}
