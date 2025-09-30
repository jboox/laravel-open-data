<?php

namespace App\Http\Controllers;

use App\Models\Dataset;

class DashboardController extends Controller
{
    public function index()
    {
        $datasets = Dataset::with('values.region')
            ->whereIn('id', [1, 2])
            ->get();

        return view('dashboard.index', compact('datasets'));
    }
}
