<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrayExport;

class DatasetApiController extends Controller
{
    /**
     * List datasets (JSON API).
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
        $datasets = $query->paginate($perPage);

        return DatasetResource::collection($datasets);
    }

    /**
     * Show detail dataset.
     */
    public function show(Dataset $dataset)
    {
        $dataset->load(['category', 'author', 'values.region']);
        return new DatasetResource($dataset);
    }

        /**
     * Download dataset via API.
     */
    public function download(Dataset $dataset, $format = 'csv')
    {
        $dataset->increment('downloads');

        // Kalau ada file asli
        if ($dataset->file_path && Storage::disk('public')->exists($dataset->file_path)) {
            return Storage::disk('public')->download($dataset->file_path);
        }

        // Generate data dari values
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
                $response->headers->set('Content-Disposition', "attachment; filename=\"{$filename}.csv\"");

                return $response;
        }
    }
}
