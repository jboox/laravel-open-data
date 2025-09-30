<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dataset;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $datasets = \App\Models\Dataset::with(['category', 'values.region'])->get();

        $selectedIds = (array) $request->input('datasets', []);

        // Ambil langsung dari DB biar konsisten
        $selectedDatasets = \App\Models\Dataset::with(['category', 'values.region'])
            ->whereIn('id', $selectedIds)
            ->get();

        return view('dashboard.index', compact('datasets', 'selectedDatasets', 'selectedIds'));
    }
}
