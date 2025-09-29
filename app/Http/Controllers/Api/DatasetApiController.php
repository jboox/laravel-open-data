<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrayExport;
use App\Http\Resources\DatasetResource;

/**
 * @OA\Info(
 *     title="Sikka Open Data API",
 *     version="1.0.0",
 *     description="API publik untuk akses dataset open data Kabupaten Sikka"
 * )
 */
class DatasetApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/datasets",
     *     summary="List all datasets",
     *     tags={"Datasets"},
     *     @OA\Parameter(name="q", in="query", description="Search keyword", @OA\Schema(type="string")),
     *     @OA\Parameter(name="category", in="query", description="Filter by category_id", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="sort", in="query", description="Sort (latest, oldest, views, downloads)", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="List of datasets")
     * )
     */
    public function index(Request $request)
    {
        $query = Dataset::with(['category', 'author']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

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

        $perPage = $request->input('per_page', 10);
        $datasets = $query->paginate($perPage);

        return DatasetResource::collection($datasets);
    }

    /**
     * @OA\Get(
     *     path="/api/datasets/{id}",
     *     summary="Get dataset detail",
     *     tags={"Datasets"},
     *     @OA\Parameter(name="id", in="path", required=true, description="Dataset ID", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Dataset detail")
     * )
     */
    public function show(Dataset $dataset)
    {
        $dataset->load(['category', 'author', 'values.region']);
        return new DatasetResource($dataset);
    }

    /**
     * @OA\Get(
     *     path="/api/datasets/{id}/download/{format}",
     *     summary="Download dataset",
     *     tags={"Datasets"},
     *     @OA\Parameter(name="id", in="path", required=true, description="Dataset ID", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="format", in="path", required=false, description="Format file (csv, xlsx, json)", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Dataset file download")
     * )
     */
    public function download(Dataset $dataset, $format = 'csv')
    {
        $dataset->increment('downloads');

        // Kalau ada file asli di storage
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
