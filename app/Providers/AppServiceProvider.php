<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('post', function (string $value) {
            return is_numeric($value)
                ? Post::findOrFail($value)
                : Post::where('slug', $value)->firstOrFail();
        });

        Route::bind('category', function (string $value) {
            return is_numeric($value)
                ? Category::findOrFail($value)
                : Category::where('slug', $value)->firstOrFail();
        });

        try {
            if (Schema::hasTable('categories')) {
                View::share(
                    'navCategories',
                    Category::active()
                        ->roots()
                        ->ordered()
                        ->with([
                            'children' => fn ($query) => $query->active()->ordered(),
                        ])
                        ->get()
                );
            }
        } catch (\Throwable $e) {
            //
        }
    }
}
