<?php

use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\AuthorController as AdminAuthorController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\VerificationCardController as AdminVerificationCardController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterSubscriptionController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/en');
});

Route::get('/banner/2nd-anniversary', function () {
    $path = storage_path('app/public/ads/2nd-anniversary.jpeg');

    abort_unless(file_exists($path), 404);

    return response()->file($path, [
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->name('ads.banner.2nd-anniversary');

/*
|--------------------------------------------------------------------------
| ADMIN ENTRY
|--------------------------------------------------------------------------
*/

Route::get('/admin', function (Request $request) {
    $locale = app()->getLocale() ?: 'en';

    if (Auth::check()) {
        return redirect()->route('admin.posts.index', ['locale' => $locale]);
    }

    return redirect()->route('login', ['locale' => $locale]);
})->name('admin.entry');

/*
|--------------------------------------------------------------------------
| LOCALE GROUP (SINGLE SOURCE OF TRUTH)
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => '{locale}',
    'where' => ['locale' => 'en|fa']
], function () {

    /*
    |--------------------------------------------------------------------------
    | PUBLIC PAGES
    |--------------------------------------------------------------------------
    */

    Route::get('/', [HomeController::class, 'index'])
        ->name('home');

    Route::get('/posts/{post}', [HomeController::class, 'show'])
        ->where(['post' => '[a-z0-9\-]+'])
        ->name('posts.show');

    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('categories.index');

    Route::get('/categories/{category}', [CategoryController::class, 'show'])
        ->where(['category' => '[a-z0-9\-]+'])
        ->name('categories.show');

    Route::get('/weather', [WeatherController::class, 'show'])
        ->name('weather.current');

    Route::get('/about', function (Request $request) {
        $locale = $request->route('locale') ?: 'en';
        app()->setLocale($locale);

        return view('pages.about', [
            'locale' => $locale,
        ]);
    })->name('about');

    Route::get('/contact', function (Request $request) {
        $locale = $request->route('locale') ?: 'en';
        app()->setLocale($locale);

        return view('pages.contact', [
            'locale' => $locale,
        ]);
    })->name('contact');

    Route::get('/verify', [VerificationController::class, 'index'])
        ->name('verify');

    Route::get('/verify/{verificationCard}', [VerificationController::class, 'show'])
        ->where(['verificationCard' => 'P[0-9]{4}'])
        ->name('verify.show');

    Route::get('/donate', function (Request $request) {
        $locale = $request->route('locale') ?: 'en';
        app()->setLocale($locale);

        $stripeDonateUrl = config('services.stripe.donate_url');

        if ($stripeDonateUrl) {
            return redirect()->away($stripeDonateUrl);
        }

        return view('pages.donate', [
            'locale' => $locale,
        ]);
    })->name('donate');

    Route::post('/newsletter/subscribe', [NewsletterSubscriptionController::class, 'store'])
        ->name('newsletter.subscribe');

    Route::get('/not-found', function (Request $request) {
        $locale = $request->route('locale') ?: 'en';
        app()->setLocale($locale);

        return response()->view('errors.404', [
            'locale' => $locale,
        ], 404);
    })->name('notfound');

    /*
    |--------------------------------------------------------------------------
    | AUTH
    |--------------------------------------------------------------------------
    */

    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login']);

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    /*
    |--------------------------------------------------------------------------
    | ADMIN (PROTECTED)
    |--------------------------------------------------------------------------
    */

    Route::middleware(['auth', 'role:admin,author'])->group(function () {

        /*
        |--------------------------------------------------------------------------
        | POSTS
        |--------------------------------------------------------------------------
        */

        Route::get('/admin/posts', [PostController::class, 'index'])
            ->name('admin.posts.index');

        Route::get('/admin/posts/create', [PostController::class, 'create'])
            ->name('admin.posts.create');

        Route::post('/admin/posts', [PostController::class, 'store'])
            ->name('admin.posts.store');

        Route::get('/admin/posts/{post}/edit', [PostController::class, 'edit'])
            ->name('admin.posts.edit');

        Route::put('/admin/posts/{post}', [PostController::class, 'update'])
            ->name('admin.posts.update');

        Route::delete('/admin/posts/{post}', [PostController::class, 'destroy'])
            ->name('admin.posts.destroy');

        Route::get('/admin/categories', [AdminCategoryController::class, 'index'])
            ->name('admin.categories.index');

        /*
        |--------------------------------------------------------------------------
        | PROFILE
        |--------------------------------------------------------------------------
        */

        Route::get('/admin/profile', [AdminProfileController::class, 'edit'])
            ->name('admin.profile.edit');

        Route::put('/admin/profile', [AdminProfileController::class, 'update'])
            ->name('admin.profile.update');

        Route::put('/admin/profile/password', [AdminProfileController::class, 'updatePassword'])
            ->name('admin.profile.password');
    });

    Route::middleware(['auth', 'role:admin'])->group(function () {
        /*
        |--------------------------------------------------------------------------
        | CATEGORIES WRITE ACCESS
        |--------------------------------------------------------------------------
        */

        Route::get('/admin/categories/create', [AdminCategoryController::class, 'create'])
            ->name('admin.categories.create');

        Route::post('/admin/categories', [AdminCategoryController::class, 'store'])
            ->name('admin.categories.store');

        Route::get('/admin/categories/{category}/edit', [AdminCategoryController::class, 'edit'])
            ->name('admin.categories.edit');

        Route::put('/admin/categories/{category}', [AdminCategoryController::class, 'update'])
            ->name('admin.categories.update');

        Route::delete('/admin/categories/{category}', [AdminCategoryController::class, 'destroy'])
            ->name('admin.categories.destroy');

        /*
        |--------------------------------------------------------------------------
        | AUTHORS
        |--------------------------------------------------------------------------
        */

        Route::get('/admin/authors', [AdminAuthorController::class, 'index'])
            ->name('admin.authors.index');

        Route::get('/admin/authors/create', [AdminAuthorController::class, 'create'])
            ->name('admin.authors.create');

        Route::post('/admin/authors', [AdminAuthorController::class, 'store'])
            ->name('admin.authors.store');

        Route::get('/admin/authors/{author}/edit', [AdminAuthorController::class, 'edit'])
            ->name('admin.authors.edit');

        Route::put('/admin/authors/{author}', [AdminAuthorController::class, 'update'])
            ->name('admin.authors.update');

        /*
        |--------------------------------------------------------------------------
        | VERIFICATION CARDS
        |--------------------------------------------------------------------------
        */

        Route::get('/admin/verifications', [AdminVerificationCardController::class, 'index'])
            ->name('admin.verifications.index');

        Route::get('/admin/verifications/create', [AdminVerificationCardController::class, 'create'])
            ->name('admin.verifications.create');

        Route::post('/admin/verifications', [AdminVerificationCardController::class, 'store'])
            ->name('admin.verifications.store');

        Route::get('/admin/verifications/{verification}/edit', [AdminVerificationCardController::class, 'edit'])
            ->name('admin.verifications.edit');

        Route::put('/admin/verifications/{verification}', [AdminVerificationCardController::class, 'update'])
            ->name('admin.verifications.update');

        Route::delete('/admin/verifications/{verification}', [AdminVerificationCardController::class, 'destroy'])
            ->name('admin.verifications.destroy');

    });

    Route::fallback(function (Request $request) {
        $locale = $request->route('locale') ?: 'en';

        return redirect()->route('notfound', ['locale' => $locale]);
    });
}); 
