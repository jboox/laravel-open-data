<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\Api\DatasetApiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Dataset;
use App\Models\Article;
use App\Models\Category;
use App\Livewire\TestSelect;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🔹 Homepage
Route::get('/', function () {
    $latestDatasets = Dataset::with('category')->latest()->take(4)->get();
    $latestArticles = Article::with('author')->latest()->take(4)->get();

    $stats = [
        'datasets'   => Dataset::count(),
        'articles'   => Article::count(),
        'categories' => Category::count(),
        'downloads'  => Dataset::sum('downloads'),
    ];

    return view('welcome', compact('latestDatasets', 'latestArticles', 'stats'));
})->name('home');

// 🔹 Dashboard (khusus user login & verified)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 🔹 Profile routes (auth)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Search Global
|--------------------------------------------------------------------------
*/
Route::get('/search', function (Request $request) {
    $q = $request->input('q');

    $datasets = Dataset::with('category')
        ->where(function ($query) use ($q) {
            $query->where('title', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%");
        })
        ->latest()
        ->get();

    $articles = Article::with('author')
        ->where(function ($query) use ($q) {
            $query->where('title', 'like', "%{$q}%")
                  ->orWhere('content', 'like', "%{$q}%");
        })
        ->latest()
        ->get();

    return view('search.results', compact('q', 'datasets', 'articles'));
})->name('search.global');

/*
|--------------------------------------------------------------------------
| Dataset Explorer
|--------------------------------------------------------------------------
*/
// Dataset routes
Route::resource('datasets', DatasetController::class);

// Download dataset dengan pilihan format
Route::get('datasets/{dataset}/download/{format?}', [DatasetController::class, 'download'])
    ->where('format', 'csv|xlsx|json')
    ->name('datasets.download');

/*
|--------------------------------------------------------------------------
| Articles (Data Bicara)
|--------------------------------------------------------------------------
*/
Route::get('/articles', function (Request $request) {
    $query = Article::with('author');

    if ($request->filled('q')) {
        $q = $request->q;
        $query->where(function ($sub) use ($q) {
            $sub->where('title', 'like', "%{$q}%")
                ->orWhere('content', 'like', "%{$q}%");
        });
    }

    switch ($request->input('sort')) {
        case 'oldest':
            $query->oldest();
            break;
        case 'views':
            $query->orderByDesc('views');
            break;
        default:
            $query->latest();
    }

    $perPage = $request->input('per_page', 10);
    $articles = $query->paginate($perPage)->withQueryString();

    return view('articles.index', compact('articles'));
})->name('articles.index');

Route::get('/articles/{id}', function ($id) {
    $article = Article::with('author')->findOrFail($id);
    $article->increment('views');

    return view('articles.show', compact('article'));
})->name('articles.show');

/*
|--------------------------------------------------------------------------
| Kategori
|--------------------------------------------------------------------------
*/
Route::get('/categories/{slug}', function ($slug, Request $request) {
    $category = Category::where('slug', $slug)->firstOrFail();

    $query = $category->datasets()->with(['author', 'category']);

    if ($request->filled('q')) {
        $q = $request->q;
        $query->where(function ($sub) use ($q) {
            $sub->where('title', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%");
        });
    }

    $datasets = $query->latest()->paginate(10)->withQueryString();

    return view('categories.show', compact('category', 'datasets'));
})->name('categories.show');

// 🔹 Auth routes (Breeze)
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Testing Only (aktif kalau APP_DEBUG = true)
|--------------------------------------------------------------------------
*/
if (config('app.debug')) {
    Route::view('/test-rounded', 'test');
}

/*
|--------------------------------------------------------------------------
| Dashboard Controller Example
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

// Route untuk download data agregasi
Route::get('datasets/{dataset}/download-aggregated/{format?}', [DatasetController::class, 'downloadAggregated'])
    ->where('format', 'csv|json|xlsx')
    ->name('datasets.downloadAggregated');