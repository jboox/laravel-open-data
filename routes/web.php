<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Dataset;
use App\Models\Article;
use App\Models\Category;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Controllers\DatasetController;

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
Route::get('/datasets', function (Request $request) {
    $query = Dataset::with(['category', 'author']);

    // Search
    if ($request->filled('q')) {
        $q = $request->q;
        $query->where(function ($sub) use ($q) {
            $sub->where('title', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%");
        });
    }

    // Filter by category
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }

    // Sorting
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

    // Pagination with per_page
    $perPage = $request->input('per_page', 10);
    $datasets = $query->paginate($perPage)->withQueryString();
    $categories = \App\Models\Category::all();

    return view('datasets.index', compact('datasets', 'categories'));
})->name('datasets.index');

Route::get('/datasets/{id}', function ($id) {
    $dataset = Dataset::with(['category', 'author', 'values.region'])
        ->findOrFail($id);
        
        // Tambahkan jumlah views
        $dataset->increment('views');

    return view('datasets.show', compact('dataset'));
})->name('datasets.show');

/*
|--------------------------------------------------------------------------
| Articles (Data Bicara)
|--------------------------------------------------------------------------
*/
Route::get('/articles', function (Request $request) {
    $query = Article::with('author');

    // Search
    if ($request->filled('q')) {
        $q = $request->q;
        $query->where(function ($sub) use ($q) {
            $sub->where('title', 'like', "%{$q}%")
                ->orWhere('content', 'like', "%{$q}%");
        });
    }

    // Sorting
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

    // Pagination
    $perPage = $request->input('per_page', 10);
    $articles = $query->paginate($perPage)->withQueryString();

    return view('articles.index', compact('articles'));
})->name('articles.index');

Route::get('/articles/{id}', function ($id) {
    $article = Article::with('author')->findOrFail($id);

    // Increment views
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

    // Search dalam kategori
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
| upload dan download dataset
|--------------------------------------------------------------------------
*/
Route::resource('datasets', DatasetController::class);
Route::get('datasets/{dataset}/download', [DatasetController::class, 'download'])->name('datasets.download');

/*
|--------------------------------------------------------------------------
| Hanya untuk testing view
|--------------------------------------------------------------------------
*/

Route::view('/test-rounded', 'test');
