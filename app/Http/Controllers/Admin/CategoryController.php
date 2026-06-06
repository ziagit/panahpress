<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as AppFacade;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    protected function setLocale(Request $request): string
    {
        $locale = $request->route('locale') ?: 'en';
        AppFacade::setLocale($locale);

        return $locale;
    }

    protected function uniqueSlug(string $nameEn, string $nameFa, ?Category $ignore = null): string
    {
        $base = Str::slug($nameEn) ?: Str::slug($nameFa) ?: 'category';
        $slug = $base;
        $counter = 2;

        while (
            Category::where('slug', $slug)
                ->when($ignore, fn ($query) => $query->where('id', '!=', $ignore->id))
                ->exists()
        ) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    public function index(Request $request)
    {
        $locale = $this->setLocale($request);
        $categories = Category::with(['parent'])->withCount('posts')->ordered()->get();
        $canManageCategories = (bool) $request->user()?->isAdmin();

        return view('admin.categories.index', compact('categories', 'locale', 'canManageCategories'));
    }

    public function create(Request $request)
    {
        $locale = $this->setLocale($request);
        $categories = Category::active()->roots()->ordered()->get();

        return view('admin.categories.create', compact('locale', 'categories'));
    }

    public function store(Request $request)
    {
        $locale = $this->setLocale($request);

        $attributes = $request->validate([
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
            'name_en' => ['required', 'string', 'max:255', 'unique:categories,name_en'],
            'name_fa' => ['required', 'string', 'max:255', 'unique:categories,name_fa'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $attributes['slug'] = $this->uniqueSlug($attributes['name_en'], $attributes['name_fa']);
        $attributes['parent_id'] = $attributes['parent_id'] ?? null;
        $attributes['is_active'] = $request->boolean('is_active');
        $attributes['sort_order'] = $attributes['sort_order'] ?? 0;

        Category::create($attributes);

        return Redirect::route('admin.categories.index', ['locale' => $locale])
            ->with('success', __('messages.category_saved'));
    }

    public function edit(Request $request, $locale, Category $category)
    {
        abort_unless($request->user()?->isAdmin(), 403);
        $categories = Category::active()->roots()->ordered()->where('id', '!=', $category->id)->get();

        return view('admin.categories.edit', compact('category', 'locale', 'categories'));
    }

    public function update(Request $request, $locale, Category $category)
    {
        abort_unless($request->user()?->isAdmin(), 403);

        $locale = $this->setLocale($request);

        $attributes = $request->validate([
            'parent_id' => ['nullable', 'integer', 'exists:categories,id', 'not_in:'.$category->id],
            'name_en' => ['required', 'string', 'max:255', 'unique:categories,name_en,' . $category->id],
            'name_fa' => ['required', 'string', 'max:255', 'unique:categories,name_fa,' . $category->id],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $attributes['slug'] = $this->uniqueSlug($attributes['name_en'], $attributes['name_fa'], $category);
        $attributes['parent_id'] = $attributes['parent_id'] ?? null;
        $attributes['is_active'] = $request->boolean('is_active');
        $attributes['sort_order'] = $attributes['sort_order'] ?? 0;

        $category->update($attributes);

        return redirect()->route('admin.categories.index', ['locale' => $locale]);
    }

    public function destroy(Request $request, $locale, Category $category)
    {
        abort_unless($request->user()?->isAdmin(), 403);

        $category->delete();

        return redirect()->route('admin.categories.index', ['locale' => $locale]);
    }
}
