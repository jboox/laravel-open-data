<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Category;
use App\Models\Region;
use App\Models\Dataset;
use App\Models\Article;

class AdminController extends Controller
{
    public function index()
    {
        // Statistik ringkas
        $stats = [
            'users' => User::count(),
            'categories' => Category::count(),
            'regions' => Region::count(),
            'datasets' => Dataset::count(),
            'articles' => Article::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
