<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as AppFacade;

class CategoryController extends Controller
{
    protected function resolveCategory(mixed $category): Category
    {
        if ($category instanceof Category) {
            return $category;
        }

        $category = trim((string) $category);

        return Category::query()
            ->whereKey($category)
            ->orWhere('slug', $category)
            ->firstOrFail();
    }

    protected function setLocale(Request $request): string
    {
        $locale = $request->route('locale') ?: 'en';
        AppFacade::setLocale($locale);
        Carbon::setLocale($locale);

        return $locale;
    }

    public function index(Request $request)
    {
        $locale = $this->setLocale($request);
        $categories = Category::active()->ordered()->withCount(['posts' => fn ($query) => $query->published()])->get();

        return view('categories.index', compact('categories', 'locale'));
    }

    public function show(Request $request,$locale, Category $category)
    {
        $locale = $this->setLocale($request);
        $category = $this->resolveCategory($category);
        $posts = Post::published()
            ->where('category_id', $category->id)
            ->orderByRaw('COALESCE(published_at, created_at) desc')
            ->paginate(10)
            ->withQueryString();

        $latestPosts = Post::published()
            ->where('category_id', $category->id)
            ->orderByRaw('COALESCE(published_at, created_at) desc')
            ->take(6)
            ->get();

        return view('categories.show', compact('category', 'locale', 'posts', 'latestPosts'));
    }
}
