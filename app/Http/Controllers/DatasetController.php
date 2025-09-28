<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrayExport;

class DatasetController extends Controller
{
    /**
     * List datasets (dengan search, filter, sort, pagination).
     */
    public function index(Request $request)
    {
        $query = Dataset::with(['category', 'author']);

        // 🔎 Search
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // 🏷️ Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // ↕️ Sorting
        switch ($request->input('sort')) {
            case 'oldest':
                $query->oldest();
                break;
            case 'views':
                $query->orderByDesc('views');
                break;
            case 'downloads':
                $query->orderByDesc('downloads');
                break;
            default:
                $query->latest();
        }

        // 📄 Pagination
        $perPage = $request->input('per_page', 10);
        $datasets = $query->paginate($perPage)->withQueryString();
        $categories = Category::all();

        return view('datasets.index', compact('datasets', 'categories'));
    }

    /**
     * Show detail dataset (increment views).
     */
    public function show(Dataset $dataset)
    {
        $dataset->load(['category', 'author', 'values.region']);
        $dataset->increment('views');

        return view('datasets.show', compact('dataset'));
    }

    /**
     * Show form upload dataset.
     */
    public function create()
    {
        $categories = Category::all();
        return view('datasets.create', compact('categories'));
    }

    /**
     * Store uploaded dataset (CSV/XLSX/JSON).
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'file'        => 'nullable|file|mimes:csv,xlsx,json|max:20480',
            'api_url'     => 'nullable|url',
        ]);

        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('datasets', 'public');
        }

        Dataset::create([
            'title'       => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'file_path'   => $path,
            'api_url'     => $request->api_url,
            'created_by'  => auth()->id(),
            'published_at'=> now(),
        ]);

        return redirect()->route('datasets.index')->with('success', 'Dataset berhasil diupload!');
    }

     /**
     * Download dataset dalam format CSV, XLSX, atau JSON.
     */
    public function download(Dataset $dataset, $format = 'csv')
    {
        $dataset->increment('downloads');

        // 🔹 Kalau dataset berupa file upload asli (langsung simpan di storage)
        if ($dataset->file_path && Storage::disk('public')->exists($dataset->file_path)) {
            return Storage::disk('public')->download($dataset->file_path);
        }

        // 🔹 Kalau dataset berupa data values → generate export
        $data = $dataset->values()->with('region')->get()->map(function ($value) {
            return [
                'Tanggal' => $value->date ? \Carbon\Carbon::parse($value->date)->format('Y-m-d') : '-',
                'Wilayah' => optional($value->region)->name ?? '-',
                'Nilai'   => $value->value,
            ];
        });

        $filename = Str::slug($dataset->title);

        switch ($format) {
            case 'json':
                return response()->json($data);

            case 'xlsx':
                return Excel::download(new ArrayExport($data->toArray()), "{$filename}.xlsx");

            case 'csv':
            default:
                $response = new StreamedResponse(function () use ($data) {
                    $handle = fopen('php://output', 'w');
                    fputcsv($handle, ['Tanggal', 'Wilayah', 'Nilai']);
                    foreach ($data as $row) {
                        fputcsv($handle, $row);
                    }
                    fclose($handle);
                });

                $response->headers->set('Content-Type', 'text/csv');
                $response->headers->set(
                    'Content-Disposition',
                    "attachment; filename=\"{$filename}.csv\""
                );

                return $response;
        }
    }
}
