<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Dataset;
use App\Models\Article;
use App\Models\Category;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🔹 Homepage
Route::get('/', function (Request $request) {
    $latestDatasets = Dataset::latest()->take(5)->get();
    $latestArticles = Article::latest()->take(3)->get();

    $searchResults = null;

    if ($request->filled('q')) {
        $q = $request->q;

        $datasets = Dataset::with('category')
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%");
            })
            ->take(5)->get();

        $articles = Article::with('author')
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                      ->orWhere('content', 'like', "%{$q}%");
            })
            ->take(5)->get();

        $searchResults = [
            'datasets' => $datasets,
            'articles' => $articles,
        ];
    }

    return view('home', compact('latestDatasets', 'latestArticles', 'searchResults'));
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
| Dataset Explorer
|--------------------------------------------------------------------------
*/

Route::get('/datasets', function (Request $request) {
    $query = Dataset::with(['category', 'author']);

    // Search by title/description
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

    $datasets = $query->latest()->paginate(10)->withQueryString();
    $categories = \App\Models\Category::all();

    return view('datasets.index', compact('datasets', 'categories'));
})->name('datasets.index');

Route::get('/datasets/{id}', function ($id) {
    $dataset = Dataset::with(['category', 'author', 'values.region'])
        ->findOrFail($id);

    return view('datasets.show', compact('dataset'));
})->name('datasets.show');

/*
|--------------------------------------------------------------------------
| Articles (Data Bicara)
|--------------------------------------------------------------------------
*/

Route::get('/articles', function (Request $request) {
    $query = Article::with('author');

    // Search by title/content
    if ($request->filled('q')) {
        $q = $request->q;
        $query->where(function ($sub) use ($q) {
            $sub->where('title', 'like', "%{$q}%")
                ->orWhere('content', 'like', "%{$q}%");
        });
    }

    $articles = $query->latest()->paginate(10)->withQueryString();

    return view('articles.index', compact('articles'));
})->name('articles.index');

Route::get('/articles/{slug}', function ($slug) {
    $article = Article::with('author')->where('slug', $slug)->firstOrFail();

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
