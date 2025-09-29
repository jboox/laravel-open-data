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
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=false,
     *         description="Search keyword in dataset title or description",
     *         @OA\Schema(type="string", example="penduduk")
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         required=false,
     *         description="Filter by category ID",
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         required=false,
     *         description="Sort order (latest, oldest, views, downloads)",
     *         @OA\Schema(type="string", example="downloads")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Number of datasets per page",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of datasets with pagination",
     *         @OA\JsonContent(
     *             type="object",
     *             example={
     *                 "data": {
     *                     {
     *                         "id": 1,
     *                         "title": "Jumlah Penduduk per Kecamatan",
     *                         "description": "Dataset jumlah penduduk per kecamatan per tahun",
     *                         "category": "Demografi",
     *                         "author": "Admin",
     *                         "views": 120,
     *                         "downloads": 45,
     *                         "published_at": "2025-09-25",
     *                         "file_path": "http://127.0.0.1:8000/storage/datasets/penduduk.csv",
     *                         "api_url": "http://127.0.0.1:8000/api/datasets/1"
     *                     },
     *                     {
     *                         "id": 2,
     *                         "title": "Angka Kemiskinan per Kecamatan",
     *                         "description": "Dataset persentase penduduk miskin",
     *                         "category": "Ekonomi",
     *                         "author": "Admin",
     *                         "views": 87,
     *                         "downloads": 30,
     *                         "published_at": "2025-09-20",
     *                         "file_path": "http://127.0.0.1:8000/storage/datasets/kemiskinan.csv",
     *                         "api_url": "http://127.0.0.1:8000/api/datasets/2"
     *                     }
     *                 },
     *                 "links": {
     *                     "first": "http://127.0.0.1:8000/api/datasets?page=1",
     *                     "last": "http://127.0.0.1:8000/api/datasets?page=5",
     *                     "prev": null,
     *                     "next": "http://127.0.0.1:8000/api/datasets?page=2"
     *                 },
     *                 "meta": {
     *                     "current_page": 1,
     *                     "from": 1,
     *                     "last_page": 5,
     *                     "path": "http://127.0.0.1:8000/api/datasets",
     *                     "per_page": 10,
     *                     "to": 10,
     *                     "total": 50
     *                 }
     *             }
     *         )
     *     )
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
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Dataset ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="year",
     *         in="query",
     *         required=false,
     *         description="Filter dataset values by year (YYYY)",
     *         @OA\Schema(type="integer", example=2023)
     *     ),
     *     @OA\Parameter(
     *         name="region",
     *         in="query",
     *         required=false,
     *         description="Filter dataset values by region (ID or name)",
     *         @OA\Schema(type="string", example="Sikka")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dataset detail with optional filtering",
     *         @OA\JsonContent(
     *             type="object",
     *             example={
     *                 "id": 1,
     *                 "title": "Jumlah Penduduk per Kecamatan",
     *                 "description": "Dataset jumlah penduduk Sikka per kecamatan per tahun",
     *                 "category": "Demografi",
     *                 "author": "Admin",
     *                 "views": 12,
     *                 "downloads": 5,
     *                 "published_at": "2025-09-25",
     *                 "file_path": "http://127.0.0.1:8000/storage/datasets/penduduk.csv",
     *                 "api_url": "http://127.0.0.1:8000/api/datasets/1",
     *                 "values": {
     *                     {
     *                         "date": "2023-01-01",
     *                         "region": "Kecamatan Maumere",
     *                         "value": 95000
     *                     },
     *                     {
     *                         "date": "2023-01-01",
     *                         "region": "Kecamatan Nita",
     *                         "value": 45000
     *                     }
     *                 }
     *             }
     *         )
     *     )
     * )
     */

    public function show(Request $request, Dataset $dataset)
    {
        $query = $dataset->values()->with('region');

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        if ($request->filled('region')) {
            $query->whereHas('region', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->region}%")
                ->orWhere('id', $request->region);
            });
        }

        $dataset->setRelation('values', $query->get());
        $dataset->load(['category', 'author']);

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
